<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest{

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
            'goukei' => 'required|integer',
            'finyear' => 'required|integer',
            'finmonth' => 'required|integer',
            'nenri' => 'required|numeric',
        ];
    }

    public function messages(){
        return [
            'goukei.required' => '借用金額を入力してください',
            'goukei.integer' => '整数を入力してください',
            'finyear.required' => '借用終了年を入力してください',
            'finyear.integer' => '整数を入力してください',
            'finmonth.required' => '借用終了月を入力してください',
            'finmonth.integer' => '整数を入力してください',
            'nenri.required' => '年利を入力してください',
            'nenri.numeric' => '数値を入力してください',
        ];
    }
}
