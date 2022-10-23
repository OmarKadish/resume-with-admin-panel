<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules= [
            'firstName' => 'required|max:30',
            'lastName' => 'required|max:30',
            'phone_number' => 'regex:/(\d+-)?\d{3}-\d{3}-\d{4}$/',
            'summery' => 'required',
            'linkedin' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'instagram' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'github' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
        ];
        if ($this->has('photo')) {
            $rules += [
                'photo' => 'mimes:jpeg,bmp,png,jpg|max:2048',
            ];
        }
        return $rules;
    }
}
