<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 17.08.2017
 * Time: 15:34
 */

namespace App\Transformers;


use App\CommunicationChannel;
use App\User;

class LeadTransformer extends Transformer
{
	public function transformMany($lead)
	{
//		dd($lead);
        $domains = [];
        foreach ($lead['domains'] AS $key=>$domainData){
            $domains[] = $domainData['value'];
        }

        $contacts = [];
        foreach ($lead['communication_values'] AS $key=>$communicationData){
            $contacts[] = [
                'channel_id' => $communicationData['channel_id'],
                'channel_name' => CommunicationChannel::find($communicationData['channel_id'])->name,
                'value' => $communicationData['value']
            ];
        }

		return [
			'id'=>$lead['id'],
			'name'=>$lead['name'],
			'responsive'=>$lead['responsive'],
			'category_id'=>$lead['category_id'],
			'category_name'=>$lead['lead_category']['name'],
			'application_type_id'=>$lead['application_type_id'],
			'application_type_name'=>$lead['application_type']['name'],
			'creator_id'=>$lead['creator_id'],
			'creator_name'=>$lead['creator']['name'],
			'creator_email'=>$lead['creator']['email'],
			'assignee_id'=>$lead['assignee_id'],
			'assignee_name'=>$lead['assignee']['name'],
			'assignee_email'=>$lead['assignee']['email'],
            'domains'=>$domains,
            'contacts'=>$contacts
		];
	}
	
	public function transformOne($lead)
	{
//		dd($lead);
        $domains = [];
        foreach ($lead['domains'] AS $key=>$domainData){
            $domains[] = $domainData['value'];
        }

        $contacts = [];
        foreach ($lead['communication_values'] AS $key=>$communicationData){
            $contacts[] = [
                'channel_id' => $communicationData['channel_id'],
                'channel_name' => CommunicationChannel::find($communicationData['channel_id'])->name,
                'value' => $communicationData['value']
            ];
        }

        $comments = [];
        foreach($lead['comments'] AS $key => $commentData){
            $comments[] = [
                'comment_id'=>$commentData['id'],
                'user_id' => $commentData['user_id'],
                'user' => User::getUserNameById($commentData['user_id']),
                'comment' => $commentData['comment'],
                'updated_at' => $commentData['updated_at']
            ];
        }

		return [
			'id'=>$lead['id'],
			'name'=>$lead['name'],
			'responsive'=>$lead['responsive'],
			'category_id'=>$lead['category_id'],
			'category_name'=>$lead['lead_category']['name'],
			'application_type_id'=>$lead['application_type_id'],
			'application_type_name'=>$lead['application_type']['name'],
			'creator_id'=>$lead['creator_id'],
			'creator_name'=>$lead['creator']['name'],
			'creator_email'=>$lead['creator']['email'],
			'assignee_id'=>$lead['assignee_id'],
			'assignee_name'=>$lead['assignee']['name'],
			'assignee_email'=>$lead['assignee']['email'],
            'domains'=>$domains,
            'contacts'=>$contacts,
            'comments' => $comments
		];
	}
}