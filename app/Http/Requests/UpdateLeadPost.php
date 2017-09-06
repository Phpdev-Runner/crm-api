<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class UpdateLeadPost extends FormRequest
{
    use ResponseTrait, DomainDuplicateTrait, EmailDuplicateTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return  true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'=>'required|string|min:6',
            'category_id'=>'required|integer|exists:lead_categories,id',
            'application_id'=>'required|integer|exists:application_types,id',
            'assignee_id'=>'required|integer|exists:users,id',
            'responsive'=>'required|integer|digits_between:0,1',
            'domains'=>'required',
        ];
    }

    public function withValidator($validator)
    {
        $leadID = $this->route('id');

        $domains = json_decode(Input::get('domains'),true);
        $this->duplicateDomains = $this->checkDomainDuplicates($domains, $leadID);

        $contacts = json_decode(Input::get('contacts'), true);
        $this->duplicateEmails = $this->checkEmailDuplicates($contacts, $leadID);

        $validator->after(function($validator){
            if($this->duplicateDomains !== false){
                $validator->errors()->add('domains', "Provided domains ( {$this->duplicateDomains} ) are already registered in the system");
            }

            if($this->duplicateEmails !== false){
                $validator->errors()->add('contacts', "Provided emails ( {$this->duplicateEmails} ) are already binded with other leads");
            }
        });
    }
}
