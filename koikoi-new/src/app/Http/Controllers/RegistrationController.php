<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    /**
     * 申込フォーム表示
     */
    public function show(Event $event, Request $request)
    {
        // イベントが終了している場合
        if ($event->event_date < now()) {
            return redirect()->back()->with('error', 'このイベントは終了しています。');
        }

        // 性別パラメータの取得
        $gender = $request->get('gender', 'male');
        
        // 性別に応じた受付状況確認
        if ($gender === 'male' && !$event->is_accepting_male) {
            return redirect()->back()->with('error', '男性の受付は終了しています。');
        }
        
        if ($gender === 'female' && !$event->is_accepting_female) {
            return redirect()->back()->with('error', '女性の受付は終了しています。');
        }
        
        // 残席確認
        if ($gender === 'male' && $event->remaining_male_seats <= 0) {
            return redirect()->back()->with('error', '男性の定員に達しました。');
        }
        
        if ($gender === 'female' && $event->remaining_female_seats <= 0) {
            return redirect()->back()->with('error', '女性の定員に達しました。');
        }

        return view('registration.show', [
            'event' => $event,
            'gender' => $gender,
            'theme' => $event->eventType->slug
        ]);
    }

    /**
     * 申込内容確認
     */
    public function confirm(Event $event, Request $request)
    {
        $gender = $request->input('gender');
        
        // バリデーション
        $rules = [
            'gender' => 'required|in:male,female',
            'name' => 'required|string|max:100',
            'name_kana' => 'required|string|max:100|regex:/^[ァ-ヶー]+$/u',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20|regex:/^[0-9-]+$/',
            'birth_date' => 'required|date|before:today|after:' . now()->subYears(60)->format('Y-m-d'),
            'postal_code' => 'required|string|size:7|regex:/^[0-9]+$/',
            'prefecture' => 'required|string|max:10',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'emergency_contact' => 'nullable|string|max:20',
            'emergency_name' => 'nullable|string|max:100',
            'comment' => 'nullable|string|max:500',
            'terms' => 'required|accepted'
        ];

        $messages = [
            'name.required' => 'お名前を入力してください。',
            'name_kana.required' => 'お名前（カナ）を入力してください。',
            'name_kana.regex' => 'お名前（カナ）は全角カタカナで入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'phone.required' => '電話番号を入力してください。',
            'phone.regex' => '電話番号は半角数字とハイフンで入力してください。',
            'birth_date.required' => '生年月日を入力してください。',
            'birth_date.before' => '有効な生年月日を入力してください。',
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.size' => '郵便番号は7桁で入力してください。',
            'postal_code.regex' => '郵便番号は半角数字で入力してください。',
            'prefecture.required' => '都道府県を選択してください。',
            'city.required' => '市区町村を入力してください。',
            'address.required' => '番地を入力してください。',
            'terms.accepted' => '利用規約に同意していただく必要があります。'
        ];

        $validatedData = $request->validate($rules, $messages);

        // 年齢チェック
        $birthDate = new \DateTime($validatedData['birth_date']);
        $age = $birthDate->diff(new \DateTime())->y;
        
        if ($gender === 'male') {
            if ($age < $event->age_min_male || $age > $event->age_max_male) {
                return back()->withErrors(['birth_date' => "このイベントの男性参加条件は{$event->age_min_male}歳〜{$event->age_max_male}歳です。"])->withInput();
            }
        } else {
            if ($age < $event->age_min_female || $age > $event->age_max_female) {
                return back()->withErrors(['birth_date' => "このイベントの女性参加条件は{$event->age_min_female}歳〜{$event->age_max_female}歳です。"])->withInput();
            }
        }

        // 重複申込チェック
        $existingRegistration = Customer::where('email', $validatedData['email'])
            ->where('event_id', $event->id)
            ->first();
            
        if ($existingRegistration) {
            return back()->withErrors(['email' => 'このメールアドレスは既に登録されています。'])->withInput();
        }

        // セッションに保存
        Session::put('registration_data', array_merge($validatedData, [
            'event_id' => $event->id,
            'age' => $age
        ]));

        return view('registration.confirm', [
            'event' => $event,
            'data' => $validatedData,
            'age' => $age,
            'theme' => $event->eventType->slug
        ]);
    }

    /**
     * 申込完了処理
     */
    public function complete(Event $event, Request $request)
    {
        // セッションから申込データ取得
        $registrationData = Session::get('registration_data');
        
        if (!$registrationData || $registrationData['event_id'] != $event->id) {
            return redirect()->route('entry.show', $event)->with('error', 'セッションが切れました。もう一度お申し込みください。');
        }

        // CSRF保護は既にミドルウェアで実施済み

        DB::beginTransaction();
        
        try {
            // 申込番号生成
            $registrationNumber = $this->generateRegistrationNumber($event);
            
            // 顧客情報登録
            $customer = Customer::create([
                'event_id' => $event->id,
                'registration_number' => $registrationNumber,
                'name' => $registrationData['name'],
                'name_kana' => $registrationData['name_kana'],
                'email' => $registrationData['email'],
                'phone' => $registrationData['phone'],
                'gender' => $registrationData['gender'],
                'birth_date' => $registrationData['birth_date'],
                'age' => $registrationData['age'],
                'postal_code' => $registrationData['postal_code'],
                'prefecture' => $registrationData['prefecture'],
                'city' => $registrationData['city'],
                'address' => $registrationData['address'],
                'emergency_contact' => $registrationData['emergency_contact'] ?? null,
                'emergency_name' => $registrationData['emergency_name'] ?? null,
                'comment' => $registrationData['comment'] ?? null,
                'status' => 'registered', // 決済なしなので即登録完了
                'registered_at' => now(),
            ]);
            
            // イベントの参加人数を更新
            if ($registrationData['gender'] === 'male') {
                $event->increment('registered_male');
            } else {
                $event->increment('registered_female');
            }
            
            DB::commit();
            
            // セッションクリア
            Session::forget('registration_data');
            
            // メール送信（将来実装）
            // Mail::to($customer->email)->send(new RegistrationComplete($customer, $event));
            
            return redirect()->route('entry.thanks', ['registration' => $customer->registration_number]);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Registration error: ' . $e->getMessage());
            return redirect()->route('entry.show', $event)->with('error', '申込処理中にエラーが発生しました。');
        }
    }

    /**
     * 申込完了画面
     */
    public function thanks($registrationNumber)
    {
        $customer = Customer::where('registration_number', $registrationNumber)->first();
        
        if (!$customer) {
            return redirect()->route('home')->with('error', '申込情報が見つかりません。');
        }
        
        $event = Event::with(['area.prefecture', 'eventType'])->find($customer->event_id);
        
        return view('registration.thanks', [
            'customer' => $customer,
            'event' => $event,
            'theme' => $event->eventType->slug
        ]);
    }

    /**
     * 申込番号生成
     */
    private function generateRegistrationNumber(Event $event)
    {
        $prefix = strtoupper(substr($event->eventType->slug, 0, 1));
        $timestamp = now()->format('ymdHis');
        $random = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        
        return "{$prefix}{$timestamp}{$random}";
    }
}
