<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Domain;
use App\CommunicationValue;

class StoreLeadPost extends FormRequest
{
    use ResponseTrait;

    #region CLASS PROPERTIES
    private $duplicateDomains;
    private $duplicateEmails;
    #endregion


	/**
     * Determine if the user is authorized to make this request.
     * @todo finish authorization
     */
    public function authorize()
    {
    	if(Auth::check()){
		    return true;
	    }else{
    		return false;
	    }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'category_id' => 'required|integer|exists:lead_categories,id',
            'application_id' => 'required|integer|exists:application_types,id',
            'assignee_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|min:6',
            'responsive' => 'required|integer|digits_between:0,1',
            'domains' => 'required',
        ];
    }
    
    public function withValidator($validator)
    {
        $domains = json_decode(Input::get('domains'),true);
        $this->duplicateDomains = $this->checkDomainDuplicates($domains);

        $contacts = json_decode(Input::get('contacts'), true);
        $this->duplicateEmails = $this->checkEmailDuplicates($contacts);

        $validator->after(function($validator){
            if($this->duplicateDomains !== false){
                $validator->errors()->add('domains', "Provided domains ( {$this->duplicateDomains} ) are already registered in the system");
            }

            if($this->duplicateEmails !== false){
                $validator->errors()->add('contacts', "Provided emails ( {$this->duplicateEmails} ) are already binded with other leads");
            }
        });
    }

    #region SERVICE METHODS
    private function checkDomainDuplicates(array $domains)
    {
        $duplicatesDomains = Domain::checkDomainDuplicates($domains);

        return $duplicatesDomains;
    }

    private function checkEmailDuplicates(array $contacts)
    {
        $emailFieldName = config('constants.communication_channel.email');

        $emailsArray = [];
        foreach ($contacts AS $key=>$data){
            if(array_key_exists($emailFieldName,$data)){
                $emailsArray[] = $data[$emailFieldName];
            }
        }
        $duplicatedEmails = CommunicationValue::checkEmailDuplicates($emailsArray);

        return $duplicatedEmails;
    }
    #endregion
}
