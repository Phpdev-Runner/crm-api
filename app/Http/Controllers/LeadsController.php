<?php

namespace App\Http\Controllers;

use App\ApplicationType;
use App\Domain;
use App\Lead;
use App\LeadCategory;
use App\Transformers\CreateLeadTransformer;
use App\Transformers\LeadTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

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

	public function viewLeads()
	{
		$leads = $this->viewAllLeads();

		$leads = $this->leadTransformer->transformManyCollections($leads);
		
		if(!$leads){
			return $this->respondNotFound('Leads table is empty');
		}
		
		return $this->respond([
			'leads'=>$leads
		]);
	}
	
	public function leadEmptyFormShow()
	{
		$leadCategories = $this->getLeadCategories();
		$leadApplicationTypes = $this->getApplicationTypes();
		$assignees = $this->getAssignees();
		$createNewLeadFormData = [
			'lead_categories'=>$leadCategories,
			'application_types'=>$leadApplicationTypes,
            'assignees'=>$assignees
		];
		$createNewLeadFormData = $this->createLeadTransformer->transformDataForEmptyForm($createNewLeadFormData);

		return $this->respond($createNewLeadFormData);
	}
	
	public function storeLead()
	{
		$categoryID = Input::get('category_id');
		$applicationID = Input::get('application_id');
		$creatorID =  Auth::id();
		$assigneeID = Input::get('assignee_id');
		$name = Input::get('name');
		$responsive = Input::get('responsive');
		$domains = json_decode(Input::get('domains'),true);

        if(!$categoryID || !$applicationID || !$creatorID || !$assigneeID || !$name || !$responsive || !$domains){
            return $this->respondBadRequest('Lead registration fields did not passed validation');
        }

        $duplicateDomains = $this->checkEmailDuplicates($domains);

        if($duplicateDomains !== false){
            return $this->respondDataConflict("Provided domains ( {$duplicateDomains} ) are already registered in the system");
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

        return $this->respondCreated("new lead successfully created!");
	}

	public function editLead($id)
    {
        var_dump($id);
    }
	#endregion
	
	#region SERVICE METHODS
	private function viewAllLeads()
	{
		$allLeads = Lead::viewAllLeads();
		return $allLeads;
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

	private function getAssignees()
    {
        $assignees = User::getUsersWithRoles([
            config('constants.roles.admin'),
            config('constants.roles.manager')
        ]);

        return $assignees;
    }

    private function checkEmailDuplicates(array $domains)
    {
        $duplicatesDomains = Domain::checkDomainDuplicates($domains);

        return $duplicatesDomains;
    }

    private function saveLead($leadData)
    {
        $lead = Lead::create($leadData);

        return $lead;
    }

    private function saveDomains($leadID, array $domains)
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
	#endregion
}
