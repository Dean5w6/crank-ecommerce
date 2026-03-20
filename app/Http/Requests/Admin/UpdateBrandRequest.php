<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{ 
    public function authorize(): bool
    {
        return true;
    }
 
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:brands,name,' . $this->brand->id,
        ];
    }
}
