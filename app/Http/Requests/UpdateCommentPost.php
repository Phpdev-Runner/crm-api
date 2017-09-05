<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentPost extends FormRequest
{
    use ResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     * @todo customize authorize method
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'comment' => 'required|string|max:120'
        ];
    }
}
