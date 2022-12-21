<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexUserPostRequest extends FormRequest
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
        return [
            'page' => ['integer', 'min:1'],
            'per_page' => ['integer', Rule::in([10, 25, 50, 100])],
            'sort_by' => ['string', Rule::in(['id', 'title', 'slug', 'published_at', 'created_at', 'updated_at'])],
            'sort_order' => ['string', Rule::in(['asc', 'desc'])],
        ];
    }
}
