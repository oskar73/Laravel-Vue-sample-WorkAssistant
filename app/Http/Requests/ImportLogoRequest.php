<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportLogoRequest extends FormRequest
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
            'logotype' => ['file', 'required', 'mimes:svg'],
            'preview' => ['file', 'required', 'mimes:png'],
            'first_font' => ['nullable', 'file', ],
            'second_font' => ['nullable', 'file', ],
        ];
    }
}
