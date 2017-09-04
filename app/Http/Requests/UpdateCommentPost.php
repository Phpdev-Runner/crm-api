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
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer|min:1',
            'comment' => 'required|string|max:120'
        ];
    }
}
