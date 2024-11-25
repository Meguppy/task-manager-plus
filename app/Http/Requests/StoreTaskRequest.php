<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // 一旦true、ログインユーザーのみにする
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
            'name' => 'required|max:15',
            'user_id' => 'nullable|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'タスク名は必須です。',
            'name.max' => 'タスク名は15文字以内です。',
            'user_id.exists' => '選択された担当者が無効です。',
        ];
    }

}
