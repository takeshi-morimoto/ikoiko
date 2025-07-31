<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSchedule;
use App\Models\EventEquipment;
use App\Models\EventRole;
use App\Models\EventSeating;
use App\Models\CustomerSpecialNote;
use App\Models\EventChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventOperationController extends Controller
{
    /**
     * イベント運営ダッシュボード
     */
    public function index($eventId)
    {
        $event = Event::with(['area', 'eventType', 'customers'])->findOrFail($eventId);
        
        $data = [
            'event' => $event,
            'schedules' => EventSchedule::where('event_id', $eventId)
                ->orderBy('time')
                ->get(),
            'equipment' => EventEquipment::where('event_id', $eventId)
                ->orderBy('item_name')
                ->get(),
            'roles' => EventRole::with('staff')
                ->where('event_id', $eventId)
                ->get(),
            'specialNotes' => CustomerSpecialNote::with('customer')
                ->where('event_id', $eventId)
                ->where('is_critical', true)
                ->get(),
            'checklist' => EventChecklist::where('event_id', $eventId)
                ->orderBy('category')
                ->orderBy('display_order')
                ->get()
                ->groupBy('category'),
        ];
        
        return view('admin.operations.index', $data);
    }

    /**
     * スケジュール管理
     */
    public function schedules($eventId)
    {
        $event = Event::findOrFail($eventId);
        $schedules = EventSchedule::where('event_id', $eventId)
            ->orderBy('time')
            ->get();
            
        return view('admin.operations.schedules', compact('event', 'schedules'));
    }

    /**
     * スケジュール保存
     */
    public function storeSchedule(Request $request, $eventId)
    {
        $validated = $request->validate([
            'time' => 'required|date_format:H:i',
            'activity' => 'required|string|max:200',
            'description' => 'nullable|string',
            'responsible_staff' => 'nullable|string|max:100',
            'duration_minutes' => 'nullable|integer|min:5|max:480',
        ]);

        $validated['event_id'] = $eventId;
        $validated['display_order'] = EventSchedule::where('event_id', $eventId)->count();

        EventSchedule::create($validated);

        return redirect()->route('admin.operations.schedules', $eventId)
            ->with('success', 'スケジュールを追加しました。');
    }

    /**
     * 備品管理
     */
    public function equipment($eventId)
    {
        $event = Event::findOrFail($eventId);
        $equipment = EventEquipment::where('event_id', $eventId)
            ->orderBy('status')
            ->orderBy('item_name')
            ->get();
            
        return view('admin.operations.equipment', compact('event', 'equipment'));
    }

    /**
     * 備品ステータス更新
     */
    public function updateEquipmentStatus(Request $request, $eventId, $equipmentId)
    {
        $equipment = EventEquipment::where('event_id', $eventId)
            ->findOrFail($equipmentId);
            
        $equipment->update([
            'status' => $request->status,
            'responsible_staff' => $request->responsible_staff,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * 役割分担
     */
    public function roles($eventId)
    {
        $event = Event::findOrFail($eventId);
        $roles = EventRole::with('staff')
            ->where('event_id', $eventId)
            ->get();
        $availableStaff = \App\Models\User::whereHas('staffProfile', function($q) {
            $q->where('is_active', true);
        })->get();
            
        return view('admin.operations.roles', compact('event', 'roles', 'availableStaff'));
    }

    /**
     * 座席管理
     */
    public function seating($eventId)
    {
        $event = Event::with('customers')->findOrFail($eventId);
        $seating = EventSeating::with('customer')
            ->where('event_id', $eventId)
            ->orderBy('table_number')
            ->orderBy('seat_number')
            ->get();
            
        return view('admin.operations.seating', compact('event', 'seating'));
    }

    /**
     * 座席自動割り当て
     */
    public function autoAssignSeating($eventId)
    {
        $event = Event::with(['customers' => function($q) {
            $q->where('status', 'registered')
              ->whereNotIn('id', function($q2) {
                  $q2->select('customer_id')
                     ->from('event_seating')
                     ->whereNotNull('customer_id');
              });
        }])->findOrFail($eventId);

        DB::transaction(function () use ($event) {
            $maleCustomers = $event->customers->where('gender', 'male')->shuffle();
            $femaleCustomers = $event->customers->where('gender', 'female')->shuffle();
            
            $tableNumber = 1;
            $seatNumber = 1;
            $maxSeatsPerTable = 8;

            // 男女交互に配置
            while ($maleCustomers->isNotEmpty() || $femaleCustomers->isNotEmpty()) {
                if ($seatNumber > $maxSeatsPerTable) {
                    $tableNumber++;
                    $seatNumber = 1;
                }

                if ($maleCustomers->isNotEmpty()) {
                    EventSeating::create([
                        'event_id' => $event->id,
                        'table_number' => $tableNumber,
                        'seat_number' => $seatNumber++,
                        'customer_id' => $maleCustomers->shift()->id,
                    ]);
                }

                if ($femaleCustomers->isNotEmpty()) {
                    EventSeating::create([
                        'event_id' => $event->id,
                        'table_number' => $tableNumber,
                        'seat_number' => $seatNumber++,
                        'customer_id' => $femaleCustomers->shift()->id,
                    ]);
                }
            }
        });

        return redirect()->route('admin.operations.seating', $eventId)
            ->with('success', '座席を自動割り当てしました。');
    }

    /**
     * チェックリスト管理
     */
    public function checklist($eventId)
    {
        $event = Event::findOrFail($eventId);
        $checklist = EventChecklist::where('event_id', $eventId)
            ->orderBy('category')
            ->orderBy('display_order')
            ->get()
            ->groupBy('category');
            
        return view('admin.operations.checklist', compact('event', 'checklist'));
    }

    /**
     * チェックリスト項目完了
     */
    public function completeChecklistItem(Request $request, $eventId, $itemId)
    {
        $item = EventChecklist::where('event_id', $eventId)
            ->findOrFail($itemId);
            
        $item->update([
            'is_completed' => true,
            'completed_at' => now(),
            'completed_by' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * 参加者特記事項
     */
    public function specialNotes($eventId)
    {
        $event = Event::findOrFail($eventId);
        $notes = CustomerSpecialNote::with('customer')
            ->where('event_id', $eventId)
            ->orderBy('is_critical', 'desc')
            ->orderBy('category')
            ->get();
            
        return view('admin.operations.special-notes', compact('event', 'notes'));
    }
}