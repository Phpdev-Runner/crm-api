<?php

namespace App\Http\Controllers;

use App\ApplicationType;
use App\CommunicationChannel;
use App\CommunicationValue;
use App\Domain;
use App\Lead;
use App\LeadCategory;
use App\Transformers\CreateLeadTransformer;
use App\Transformers\LeadTransformer;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Validator;

class LeadsController extends ApiController
{
    #region CLASS PROPERTIES
	private $leadTransformer;
	private $createLeadTransformer;
	#endregion
	
	#region MAIN METHODS
    public function __construct(LeadTransformer $leadTransformer, CreateLeadTransformer $createLeadTransformer)
    {
        $this->leadTransformer = $leadTransformer;
        $this->createLeadTransformer = $createLeadTransformer;
    }

    /**
     * show all leads
     */
	public function viewLeads()
	{
		$leads = $this->findAllLeads();

		$leads = $this->leadTransformer->transformManyCollections($leads);
		
		if(!$leads){
			return $this->respondNotFound('Leads table is empty');
		}
		
		return $this->respond([
			'leads'=>$leads
		]);
	}

    /**
     * show form for new lead creation
     */
	public function leadEmptyFormShow()
	{
		$leadCategories = $this->getLeadCategories();
		$leadApplicationTypes = $this->getApplicationTypes();
		$assignees = $this->getAssignees();
		$communicationChannels = $this->getCommunicationChannels();

		$createNewLeadFormData = [
			'lead_categories'=>$leadCategories,
			'application_types'=>$leadApplicationTypes,
            'assignees'=>$assignees,
            'communication_channels'=>$communicationChannels
		];
		$createNewLeadFormData = $this->createLeadTransformer->transformDataForEmptyForm($createNewLeadFormData);

		return $this->respond($createNewLeadFormData);
	}

    /**
     * save new lead
     */
	public function storeLead(Request $request)
	{
        //call validator
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer|min:1',
            'application_id' => 'required|integer|min:1',
            'assignee_id' => 'required|integer|min:1',
            'name' => 'required|string',
            'responsive' => 'required|integer|digits_between:0,1',
            'domains'
        ]);

        //manually do redirect back with errors and old input
        if ($validator->fails()) {
            $message = $validator->errors()->all();
            return $this->respondValidationFailed($message);
        }

		$categoryID = Input::get('category_id');
		$applicationID = Input::get('application_id');
		$creatorID =  Auth::id();
		$assigneeID = Input::get('assignee_id');
		$name = Input::get('name');
		$responsive = Input::get('responsive');
		$domains = json_decode(Input::get('domains'),true);
		$contacts = json_decode(Input::get('contacts'), true);

        if(!$categoryID || !$applicationID || !$creatorID || !$assigneeID || !$name || !$responsive || !$domains ){
            return $this->respondBadRequest('Lead registration fields did not passed validation');
        }

        $duplicateDomains = $this->checkDomainDuplicates($domains);

        if($duplicateDomains !== false){
            return $this->respondDataConflict("Provided domains ( {$duplicateDomains} ) are already registered in the system");
        }

        $duplicatedEmails = $this->checkEmailDuplicates($contacts);

        if($duplicatedEmails !== false){
            return $this->respondDataConflict("Provided emails ( {$duplicatedEmails} ) are already binded with other leads");
        }

        $leadData = [
            'category_id' => $categoryID,
            'application_type_id' => $applicationID,
            'creator_id' => $categoryID,
            'assignee_id' => $assigneeID,
            'name' => $name,
            'responsive' => $responsive
        ];

        $lead = $this->saveLead($leadData);

        $this->saveDomains($lead->id, $domains);

        $this->saveCommunicationValues($lead->id, $contacts);

        return $this->respondCreated("new lead successfully created!");
	}

    /**
     * show data for lead edit form
     */
	public function editLead($id)
    {
        $lead = $this->findLead($id);

        if($lead == null){
            return $this->respondNoContent("Lead with ID {$id} does not exits!");
        }
        $lead = $this->leadTransformer->transformOneModel($lead);

        return $this->respond($lead);
    }

    /**
     * update lead
     */
    public function updateLead($id)
    {
        $lead = $this->findLead($id);

        if($lead === null){
            return $this->respondNoContent("Lead with ID {$id} does not exists!");
        }

        exit("OK!");

    }
	#endregion
	
	#region SERVICE METHODS
	private function findAllLeads()
	{
		return Lead::viewAllLeads();
	}

    private function findLead($id)
    {
        return Lead::viewLead($id);
    }

	private function getLeadCategories()
	{
		$leadCategories = LeadCategory::getLeadCategories();
		$leadCategoriesArray = [];
		foreach ($leadCategories->toArray() AS $key=>$data){
			$leadCategoriesArray[] = [
				'id'=>$data['id'],
				'name'=>$data['name']
			];
		};
		return $leadCategoriesArray;
	}
	
	private function getApplicationTypes()
	{
		$applicationTypes = ApplicationType::getApplicationTypes();
		$applicationTypesArray = [];
		foreach ($applicationTypes->toArray() AS $key=>$data){
			$applicationTypesArray[] = [
				'id'=>$data['id'],
				'name'=>$data['name']
			];
		};
		return $applicationTypesArray;
	}

	private function getCommunicationChannels()
    {
        $communicationChannels = CommunicationChannel::getCommunicationChannels();
        $communicationChannelsArray = [];
        foreach ($communicationChannels->toArray() AS $key=>$data){
            $communicationChannelsArray[] = [
                'id' => $data['id'],
                'name' => $data['name']
            ];
        }
        return $communicationChannelsArray;
    }

	private function getAssignees()
    {
        $assignees = User::getUsersWithRoles([
            config('constants.roles.admin'),
            config('constants.roles.manager')
        ]);

        return $assignees;
    }

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

    private function saveLead($leadData)
    {
        $lead = Lead::create($leadData);

        return $lead;
    }

    private function saveDomains(int $leadID, array $domains)
    {
        foreach ($domains AS $domain){
            $domainData = [
                'lead_id' => $leadID,
                'value' => $domain
            ];
            $domain = Domain::create($domainData);
        }
        return $domain;
    }

    private function saveCommunicationValues(int $leadID, array $commValues)
    {
        $commChannels = CommunicationChannel::all()->toArray();
        $commChannelsArray = [];
        foreach ($commChannels AS $key=>$value){
            $commChannelsArray[$value['id']] = $value['name'];
        }

        $rowsToStore = [];
        foreach ($commValues AS $key=>$data){

            foreach ($data AS $chanelName=>$chanelValue){
                if($key = array_search($chanelName,$commChannelsArray)){
                    $rowsToStore[] = [
                        'lead_id'=>$leadID,
                        'channel_id'=> $key,
                        'value'=> $data[$chanelName],
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ];
                }
            }
        }
        CommunicationValue::insert($rowsToStore);

    }

	#endregion
}
