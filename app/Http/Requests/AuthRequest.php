<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'email' => 'required|email',
            'password' => 'required|min:4',
        ];
    }

    public function messages(){
        return [
            'email.required' => 'emailの入力は必須です',
            'email.email' => 'emailの形式が違います',
            'password.required' => 'passwordの入力は必須です',
            'password.min' => '4桁以上のパスワードを入力してください',
        ];
    }
}
