<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComRecordPost extends FormRequest
{
    use ResponseTrait;
    /**
     * Determine if the user is authorized to make this request.
     * @todo complete authorize function
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
            'channel_id'=>'required|integer|min:1',
            'lead_id'=>'required|integer|min:1',
            'value'=>'required|string|min:3',
        ];
    }
}
