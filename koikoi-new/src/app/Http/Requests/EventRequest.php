<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * リクエストの認可
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルール
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:1000'],
            'event_date' => ['required', 'date', 'after:today'],
            'event_type_id' => ['required', 'exists:event_types,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'prefecture_id' => ['required', 'exists:prefectures,id'],
            'venue' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:200'],
            'capacity_male' => ['required', 'integer', 'min:1', 'max:100'],
            'capacity_female' => ['required', 'integer', 'min:1', 'max:100'],
            'price_male' => ['required', 'integer', 'min:0', 'max:50000'],
            'price_female' => ['required', 'integer', 'min:0', 'max:50000'],
            'early_bird_price_male' => ['nullable', 'integer', 'min:0', 'max:50000', 'lt:price_male'],
            'early_bird_price_female' => ['nullable', 'integer', 'min:0', 'max:50000', 'lt:price_female'],
            'age_min' => ['required', 'integer', 'min:18', 'max:100'],
            'age_max' => ['required', 'integer', 'min:18', 'max:100', 'gte:age_min'],
            'is_published' => ['boolean'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
        
        // 更新時は日付の制約を緩める
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['event_date'] = ['required', 'date'];
        }
        
        return $rules;
    }
    
    /**
     * バリデーションメッセージ
     */
    public function messages(): array
    {
        return [
            'title.required' => 'イベント名は必須です',
            'title.max' => 'イベント名は100文字以内で入力してください',
            'event_date.required' => '開催日は必須です',
            'event_date.after' => '開催日は明日以降の日付を選択してください',
            'capacity_male.required' => '男性定員は必須です',
            'capacity_female.required' => '女性定員は必須です',
            'price_male.required' => '男性料金は必須です',
            'price_female.required' => '女性料金は必須です',
            'early_bird_price_male.lt' => '男性早割料金は通常料金より安く設定してください',
            'early_bird_price_female.lt' => '女性早割料金は通常料金より安く設定してください',
            'age_min.min' => '最低年齢は18歳以上に設定してください',
            'age_max.gte' => '最高年齢は最低年齢以上に設定してください',
        ];
    }
    
    /**
     * バリデーション前のデータ加工
     */
    protected function prepareForValidation(): void
    {
        // 全角数字を半角に変換
        $fields = ['capacity_male', 'capacity_female', 'price_male', 'price_female', 
                  'early_bird_price_male', 'early_bird_price_female', 'age_min', 'age_max'];
        
        foreach ($fields as $field) {
            if ($this->has($field)) {
                $this->merge([
                    $field => mb_convert_kana($this->input($field), 'n')
                ]);
            }
        }
    }
}