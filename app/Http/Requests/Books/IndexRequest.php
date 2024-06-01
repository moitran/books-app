<?php

namespace App\Http\Requests\Books;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'query' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
            'order_by' => 'nullable|string|in:title,author,created_at,updated_at',
            'order_type' => 'nullable|string|in:asc,desc',
            'category_id' => 'nullable|exists:categories,id',
            'provider_id' => 'nullable|exists:providers,id',
        ];
    }
}
