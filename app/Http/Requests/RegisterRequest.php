<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    // public function failedValidation(validator $validator)
    // {
    //     return redirect()->back()->withInput()->with('token', csrf_token());
    // }

    public function rules()
    {
        return [
            'Numele' => 'required|min:4|max:20',
            'Prenumele' => 'required|min:4|max:20',
            'Data_nasterii' => 'required|before_or_equal:today',
            'Tara' => 'required',
            'Orasul' => 'required',
            'tip_user' => 'required',

            // tip user student1/profesor
            'nr-institutii.*' => 'exclude_if:tip_user,student0, universitate|required|integer',
            'nume-institutie.*.*' => 'exclude_if:tip_user,student0, universitate|required|min:8|max:40',
            'start-studii-institutie.*.*' => 'exclude_if:tip_user,student0, universitate|required|date|before_or_equal:today',
            'finish-studii-institutie.*.*' => 'exclude_if:tip_user,student0, universitate|required|date|after_or_equal:start-studii-institutie.*.*',
            'specialitate-user.*.*' => 'exclude_if:tip_user,student0, universitate|required',
            'nivel-user.*.*' => 'exclude_if:tip_user,student0, universitate|required',
            'tara-institutie.*.*' => 'exclude_if:tip_user,student0, universitate|required',
            'orasul-institutie.*.*' => 'exclude_if:tip_user,student0, universitate|required',

            // tip user universitate
            'an-finish-institutie' => 'exclude_if:tip_user,student0,student1,profesor|required|date|after_or_equal:an-fondare-institutie',
            // 
            'avatar-path' => 'required|accepted',
            'avatar-image' => 'sometimes|file|mimes:jpeg,jpg,png',
            'email' => 'sometimes|required|email|unique:users,email',
            'password' => 'sometimes|required|min:4|max:12|confirmed',
        ];
    }
}
