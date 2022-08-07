<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\ArrayShape;

class StorePostRequest extends FormRequest
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
    #[ArrayShape(['title' => "string[]", 'banner' => "string[]", 'content' => "string[]"])]
    public function rules(): array
    {
        return [
            'title' => [
                'required'
            ],
            'banner' => [
                'required'
            ],
            'content' => [
                'required'
            ]
        ];
    }

    #[ArrayShape(['required' => "string"])]
    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống',
        ];
    }

    #[ArrayShape(['title' => "string", 'banner' => "string", 'content' => "string", 'tag_ids' => "string", 'category' => "string"])]
    public function attributes(): array
    {
        return [
            'title' => 'tiêu đề',
            'banner' => 'ảnh tiêu đề',
            'content' => 'nội dung',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status'   => false,
            'message'   => 'Lỗi dữ liệu truyền lên',
            'data'      => $validator->errors()
        ]));
    }
}
