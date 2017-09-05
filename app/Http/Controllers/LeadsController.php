<?php

namespace App\Http\Controllers;

use App\ApplicationType;
use App\CommunicationChannel;
use App\CommunicationValue;
use App\Domain;
use App\Http\Requests\StoreLeadPost;
use App\Http\Requests\UpdateLeadPost;
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
	public function storeLead(StoreLeadPost $request)
	{
		$domains = array_unique(json_decode(Input::get('domains'),true));
		$contacts = $this->removeDuplicateContacts(json_decode(Input::get('contacts'), true));

        $leadData = [
            'category_id' => Input::get('category_id'),
            'application_type_id' => Input::get('application_id'),
            'creator_id' => Auth::id(),
            'assignee_id' => Input::get('assignee_id'),
            'name' => Input::get('name'),
            'responsive' => Input::get('responsive')
        ];

        $lead = $this->saveLead($leadData);

        if(isset($lead->id) && $lead->id >0){
            $this->saveDomains($lead->id, $domains);
            $this->saveCommunicationValues($lead->id, $contacts);
            return $this->respondCreated("new lead successfully created!");
        }else{
            return $this->respondDataConflict("Due to unknown reason Lead was not saved!");
        }
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
    public function updateLead(UpdateLeadPost $request, $id)
    {
        $lead = $this->findLead($id);

        if($lead === null){
            return $this->respondNoContent("Lead with ID {$id} does not exists!");
        }

        $domains = json_decode(Input::get('domains'),true);
        $contacts = json_decode(Input::get('contacts'), true);

        $leadData = [
            'category_id'=> Input::get('category_id'),
            'application_type_id'=> Input::get('application_id'),
            'assignee_id' => Input::get('assignee_id'),
            'name' => Input::get('name'),
            'responsive' => Input::get('responsive')
        ];

        $updateLeadStatus = $lead->update($leadData);

        if($updateLeadStatus === true){
            // update domains
            $this->updateDomains($lead->id, $domains);
            // update contacts
            $this->updateCommunicationValues($lead->id, $contacts);
            return $this->respondUpdated("Domains and Contacts info for lead updated!");
        }else{
            return $this->respondDataConflict("Due to unknown reason Lead was not updated!");
        }
    }

    /**
     * delete Lead
     */
    public function deleteLead($leadID)
    {
        $lead = Lead::find($leadID);
        if($lead == null){
            return $this->respondNoContent("Lead with requested ID {$leadID} was not found!");
        }

        if(Auth::check() && Auth::user()->authHasRole() == config('constants.roles.admin')){
            $lead->delete();
            return $this->respondDeleted("Lead with ID ". $lead->id ." was successfully deleted!");
        }else{
            return $this->respondActionForbidden("Only Admin can delete Leads! You are not authorized!");
        }
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

    private function removeDuplicateContacts(array $contacts)
    {
        $resultArray = [];
        foreach ($contacts AS $dataArray){
            foreach ($dataArray AS $comChannel=>$comValue){
                $resultArray[$comChannel][] = $comValue;
            }
        }
        foreach ($resultArray AS $comChannel => $camValueArray){
            $resultArray[$comChannel] = array_unique($resultArray[$comChannel]);
        }
        $returnValue = [];
        foreach ($resultArray AS $key=>$valueArray){
            foreach ($valueArray AS $value){
                $returnValue[] = [$key=>$value];
            }
        }
        return $returnValue;
    }

    private function saveLead($leadData)
    {
        $lead = Lead::create($leadData);

        return $lead;
    }

    private function saveDomains(int $leadID, array $domains)
    {
        $rowsToStore = $this->prepareDataForDomains($leadID,$domains);
        $insertStatus = Domain::insert($rowsToStore);
        return $insertStatus;
    }

    private function updateDomains(int $leadID, array $domains)
    {
        //delete previously added domains
        $deletedQty = Domain::deletePreviouslyAddedDomains($leadID);

        //insert new domains
        $rowsToStore = $this->prepareDataForDomains($leadID,$domains);
        $insertStatus = Domain::insert($rowsToStore);

        if($deletedQty > 0 && $insertStatus == true){
            return true;
        }else{
            return false;
        }
    }

    private function prepareDataForDomains(int $leadID, array $domains)
    {
        $domainData = [];
        foreach ($domains AS $domain){
            $domainData[] = [
                'lead_id' => $leadID,
                'value' => $domain,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        return $domainData;
    }

    private function saveCommunicationValues(int $leadID, array $commValues)
    {
        $rowsToStore = $this->prepareDataForCommunicationValues($leadID,$commValues);

        $insertStatus = CommunicationValue::insert($rowsToStore);

        return $insertStatus;

    }

    private function updateCommunicationValues(int $leadID, array $commValues)
    {
        // delete previously added communication values
        $deletedQTY = CommunicationValue::deletePreviouslyAddedCommunicationValues($leadID);

        // insert new communication values
        $rowsToStore = $this->prepareDataForCommunicationValues($leadID,$commValues);

        $insertStatus = CommunicationValue::insert($rowsToStore);

        if($deletedQTY >= 0 && $insertStatus == true){
            return true;
        }else{
            return false;
        }
    }

    private function prepareDataForCommunicationValues(int $leadID, array $commValues)
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
        return $rowsToStore;
    }

	#endregion
}
