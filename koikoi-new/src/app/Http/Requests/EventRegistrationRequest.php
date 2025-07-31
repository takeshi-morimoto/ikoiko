<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'name_kana' => ['required', 'string', 'max:255', 'regex:/^[ァ-ヴー　\s]+$/u'],
            'gender' => ['required', 'in:male,female'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'phone' => ['required', 'regex:/^0\d{9,10}$/'],
            'birthdate' => ['nullable', 'date', 'before:today', 'after:1900-01-01'],
            'postal_code' => ['nullable', 'regex:/^\d{3}-?\d{4}$/'],
            'address' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'terms' => ['required', 'accepted'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'お名前',
            'name_kana' => 'お名前（フリガナ）',
            'gender' => '性別',
            'email' => 'メールアドレス',
            'phone' => '電話番号',
            'birthdate' => '生年月日',
            'postal_code' => '郵便番号',
            'address' => '住所',
            'notes' => '備考',
            'terms' => '利用規約',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name_kana.regex' => ':attributeは全角カタカナで入力してください。',
            'phone.regex' => ':attributeは半角数字で入力してください（ハイフンなし）。',
            'postal_code.regex' => ':attributeは正しい形式で入力してください。',
            'terms.accepted' => ':attributeに同意していただく必要があります。',
        ];
    }
}