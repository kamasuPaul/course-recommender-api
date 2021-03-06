<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:schools,name',
            'district' => 'required|string|max:255',
            'address' => 'string|max:255',
            'phone' => 'string|max:255',
            'email' => 'string|max:255',
            'website' => 'string|max:255',
            'uneb_number' => 'string|max:255',
        ];
    }
}
