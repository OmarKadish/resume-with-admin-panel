<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SectionAddUpdateRequest extends FormRequest
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
        $rules = [
            'title' => 'required|max:30',
            'details' => 'required',
            'country' => 'required',
            'city' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'after:startDate',
            'user_id' => [Rule::exists('users', 'id')],

        ];
        if($this->has('collageName')){
            $rules += [
                'collageName' => 'required|max:100',
                'degree' => 'required|max:100',
                'department' => 'required|max:100',
                'gpa' => 'required',];
        }
        if($this->has('companyName')){
            $rules += [
                'companyName' => 'required|max:150',];
        }

        return $rules;
    }
}
