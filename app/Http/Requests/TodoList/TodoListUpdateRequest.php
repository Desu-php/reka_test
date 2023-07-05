<?php

namespace App\Http\Requests\TodoList;

use Illuminate\Foundation\Http\FormRequest;

class TodoListUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'file', 'mimes:png,jpeg,jpg'],
            'is_completed' => ['required', 'boolean'],
            'tags' => ['nullable', 'string']
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_completed' => $this->boolean('is_completed'),
        ]);
    }
}
