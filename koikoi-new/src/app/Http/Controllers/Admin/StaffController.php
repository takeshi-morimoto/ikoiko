<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StaffProfile;
use App\Models\StaffShift;
use App\Models\ShiftRequest;
use App\Models\StaffWorkRecord;
use App\Models\StaffSkillEvaluation;
use App\Models\ShiftTemplate;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StaffController extends Controller
{
    /**
     * スタッフ一覧
     */
    public function index(Request $request)
    {
        $staff = User::with(['staffProfile', 'shifts' => function($q) {
            $q->where('shift_date', '>=', today())->orderBy('shift_date');
        }])
        ->whereHas('staffProfile')
        ->when($request->is_active !== null, function($q) use ($request) {
            $q->whereHas('staffProfile', function($q2) use ($request) {
                $q2->where('is_active', $request->is_active);
            });
        })
        ->when($request->skill, function($q, $skill) {
            $q->whereHas('staffProfile', function($q2) use ($skill) {
                $q2->whereJsonContains('skills', $skill);
            });
        })
        ->paginate(20);
        
        return view('admin.staff.index', compact('staff'));
    }

    /**
     * スタッフ詳細
     */
    public function show($staffId)
    {
        $staff = User::with([
            'staffProfile',
            'shifts' => function($q) {
                $q->orderBy('shift_date', 'desc')->limit(20);
            },
            'workRecords' => function($q) {
                $q->orderBy('work_date', 'desc')->limit(20);
            },
            'skillEvaluations' => function($q) {
                $q->orderBy('evaluated_at', 'desc');
            }
        ])->findOrFail($staffId);
        
        $stats = [
            'total_shifts' => $staff->shifts->count(),
            'completed_shifts' => $staff->shifts->where('status', 'completed')->count(),
            'avg_performance' => $staff->workRecords->avg('performance_rating'),
            'total_hours' => $staff->workRecords->sum('work_hours'),
        ];
        
        return view('admin.staff.show', compact('staff', 'stats'));
    }

    /**
     * シフト管理画面
     */
    public function shifts(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : now();
        $weekStart = $date->copy()->startOfWeek();
        $weekEnd = $date->copy()->endOfWeek();
        
        $shifts = StaffShift::with(['staff', 'event'])
            ->whereBetween('shift_date', [$weekStart, $weekEnd])
            ->orderBy('shift_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy('shift_date');
            
        $staff = User::whereHas('staffProfile', function($q) {
            $q->where('is_active', true);
        })->get();
        
        $events = Event::whereBetween('event_date', [$weekStart, $weekEnd])
            ->orderBy('event_date')
            ->get();
            
        return view('admin.staff.shifts', compact('shifts', 'staff', 'events', 'weekStart', 'weekEnd'));
    }

    /**
     * シフト登録
     */
    public function storeShift(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,id',
            'event_id' => 'nullable|exists:events,id',
            'shift_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'shift_type' => 'required|in:event,office,training,leave',
            'notes' => 'nullable|string',
        ]);

        // 重複チェック
        $exists = StaffShift::where('staff_id', $validated['staff_id'])
            ->where('shift_date', $validated['shift_date'])
            ->where(function($q) use ($validated) {
                $q->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                  ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']]);
            })
            ->exists();

        if ($exists) {
            return back()->withErrors(['shift' => 'この時間帯には既にシフトが登録されています。']);
        }

        StaffShift::create($validated);

        return redirect()->route('admin.staff.shifts')
            ->with('success', 'シフトを登録しました。');
    }

    /**
     * シフト希望管理
     */
    public function shiftRequests(Request $request)
    {
        $requests = ShiftRequest::with('staff')
            ->when($request->status, function($q, $status) {
                $q->where('status', $status);
            })
            ->when($request->month, function($q, $month) {
                $q->whereMonth('request_date', $month);
            })
            ->orderBy('request_date')
            ->paginate(50);
            
        return view('admin.staff.shift-requests', compact('requests'));
    }

    /**
     * シフト希望承認
     */
    public function approveShiftRequest($requestId)
    {
        $request = ShiftRequest::findOrFail($requestId);
        
        $request->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'シフト希望を承認しました。');
    }

    /**
     * 勤怠記録
     */
    public function workRecords(Request $request)
    {
        $date = $request->date ?? today();
        
        $records = StaffWorkRecord::with(['staff', 'event'])
            ->where('work_date', $date)
            ->orderBy('actual_start')
            ->get();
            
        $scheduledShifts = StaffShift::with('staff')
            ->where('shift_date', $date)
            ->whereNotIn('staff_id', $records->pluck('staff_id'))
            ->get();
            
        return view('admin.staff.work-records', compact('records', 'scheduledShifts', 'date'));
    }

    /**
     * 勤怠記録登録
     */
    public function storeWorkRecord(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,id',
            'shift_id' => 'nullable|exists:staff_shifts,id',
            'event_id' => 'nullable|exists:events,id',
            'work_date' => 'required|date',
            'actual_start' => 'required|date_format:H:i',
            'actual_end' => 'required|date_format:H:i|after:actual_start',
            'break_time' => 'nullable|date_format:H:i',
            'attendance_status' => 'required|in:present,late,absent,leave',
            'performance_rating' => 'nullable|integer|min:1|max:5',
            'performance_notes' => 'nullable|string',
        ]);

        // 勤務時間計算
        $start = Carbon::parse($validated['work_date'] . ' ' . $validated['actual_start']);
        $end = Carbon::parse($validated['work_date'] . ' ' . $validated['actual_end']);
        $breakMinutes = $validated['break_time'] ? 
            Carbon::parse($validated['break_time'])->hour * 60 + Carbon::parse($validated['break_time'])->minute : 0;
        
        $workMinutes = $end->diffInMinutes($start) - $breakMinutes;
        $validated['work_hours'] = round($workMinutes / 60, 2);
        
        // 残業時間計算（8時間を超えた分）
        $validated['overtime_hours'] = max(0, $validated['work_hours'] - 8);
        
        $validated['recorded_by'] = auth()->id();

        StaffWorkRecord::updateOrCreate(
            [
                'staff_id' => $validated['staff_id'],
                'work_date' => $validated['work_date'],
                'shift_id' => $validated['shift_id'],
            ],
            $validated
        );

        return back()->with('success', '勤怠記録を登録しました。');
    }

    /**
     * スキル評価
     */
    public function skillEvaluations($staffId)
    {
        $staff = User::with(['staffProfile', 'skillEvaluations' => function($q) {
            $q->orderBy('evaluated_at', 'desc');
        }])->findOrFail($staffId);
        
        $skills = ['MC', '受付', '撮影', 'サポート', 'リーダーシップ', '顧客対応'];
        
        return view('admin.staff.skill-evaluations', compact('staff', 'skills'));
    }

    /**
     * スキル評価登録
     */
    public function storeSkillEvaluation(Request $request, $staffId)
    {
        $validated = $request->validate([
            'skill_name' => 'required|string|max:50',
            'skill_level' => 'required|integer|min:1|max:5',
            'evaluation_notes' => 'nullable|string',
        ]);

        $validated['staff_id'] = $staffId;
        $validated['evaluated_by'] = auth()->id();
        $validated['evaluated_at'] = now();

        StaffSkillEvaluation::create($validated);

        // スタッフプロフィールのスキルも更新
        $profile = StaffProfile::where('user_id', $staffId)->first();
        if ($profile && $validated['skill_level'] >= 3) {
            $skills = $profile->skills ?? [];
            if (!in_array($validated['skill_name'], $skills)) {
                $skills[] = $validated['skill_name'];
                $profile->update(['skills' => $skills]);
            }
        }

        return back()->with('success', 'スキル評価を登録しました。');
    }

    /**
     * シフトテンプレート
     */
    public function shiftTemplates()
    {
        $templates = ShiftTemplate::where('is_active', true)
            ->orderBy('template_name')
            ->get();
            
        return view('admin.staff.shift-templates', compact('templates'));
    }

    /**
     * イベントにテンプレート適用
     */
    public function applyTemplate(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $template = ShiftTemplate::findOrFail($request->template_id);
        
        DB::transaction(function () use ($event, $template) {
            $roles = json_decode($template->roles, true);
            
            foreach ($roles as $role) {
                // 利用可能なスタッフを取得
                $availableStaff = User::whereHas('staffProfile', function($q) use ($role) {
                    $q->where('is_active', true)
                      ->whereJsonContains('skills', $role['role']);
                })
                ->whereDoesntHave('shifts', function($q) use ($event) {
                    $q->where('shift_date', $event->event_date);
                })
                ->take($role['count'])
                ->get();
                
                foreach ($availableStaff as $staff) {
                    StaffShift::create([
                        'staff_id' => $staff->id,
                        'event_id' => $event->id,
                        'shift_date' => $event->event_date,
                        'start_time' => $role['start'],
                        'end_time' => $role['end'],
                        'shift_type' => 'event',
                    ]);
                }
            }
        });

        return redirect()->route('admin.operations.roles', $eventId)
            ->with('success', 'シフトテンプレートを適用しました。');
    }
}