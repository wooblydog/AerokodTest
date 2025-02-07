<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivatePostAndCommentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'post_ids' => ['required', 'array'],
            'post_ids.*' => ['exists:posts,id'],
            'comment_ids' => ['required', 'array'],
            'comment_ids.*' => ['exists:comments,id'],
        ];
    }
}
