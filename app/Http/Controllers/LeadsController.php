<?php

namespace App\Http\Controllers;

use App\ApplicationType;
use App\Lead;
use App\LeadCategory;
use App\Transformers\CreateLeadTransformer;
use App\Transformers\LeadTransformer;
use Illuminate\Http\Request;

class LeadsController extends ApiController
{
    #region CLASS PROPERTIES
	private $leadTransformer;
	private $createLeadTransformer;
	#endregion
	
	#region MAIN METHODS
	public function __construct(LeadTransformer $leadTransformer,
								CreateLeadTransformer $createLeadTransformer)
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
		$createNewLeadFormData = [
			'lead_categories'=>$leadCategories,
			'aplication_types'=>$leadApplicationTypes
		];
		$createNewLeadFormData = $this->createLeadTransformer->transformDataForEmptyForm($createNewLeadFormData);
		return $this->respond($createNewLeadFormData);
	}
	
	public function storeLead()
	{
		$categoryID = Input::get('category_id');
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
	#endregion
}
