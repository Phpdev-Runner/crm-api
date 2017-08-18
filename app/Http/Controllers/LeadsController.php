<?php

namespace App\Http\Controllers;

use App\Lead;
use App\Transformers\LeadTransformer;
use Illuminate\Http\Request;

class LeadsController extends ApiController
{
    #region CLASS PROPERTIES
	private $leadTransformer;
	#endregion
	
	#region MAIN METHODS
	public function __construct(LeadTransformer $leadTransformer)
	{
		$this->leadTransformer = $leadTransformer;
	}
	
	public function viewLeads()
	{
		$leads = $this->viewAllLeads();
		$leads = $this->leadTransformer->transformManyCollections($leads);
//		dd($leads);
		
		if(!$leads){
			return $this->respondNotFound('Leads table is empty');
		}
		
		return $this->respond([
			'leads'=>$leads
		]);
	}
	#endregion
	
	#region SERVICE METHODS
	private function viewAllLeads()
	{
		$allLeads = Lead::viewAllLeads();
		return $allLeads;
	}
	#endregion
}
