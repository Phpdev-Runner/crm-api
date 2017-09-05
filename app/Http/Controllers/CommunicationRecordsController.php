<?php

namespace App\Http\Controllers;

use App\CommunicationRecord;
use App\Http\Requests\StoreComRecordPost;
use App\Http\Requests\UpdateComRecordPost;
use App\Transformers\ComRecordTransformer;
use App\Transformers\CreateComRecordTransformer;
use App\CommunicationChannel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class CommunicationRecordsController extends ApiController
{
    #region CLASS PROPERTIES
    private $createComRecordTransformer;
    private $comRecordTransformer;
    #endregion

    #region MAIN METHODS

    public function __construct(CreateComRecordTransformer $createComRecordTransformer, ComRecordTransformer $comRecordTransformer)
    {
        $this->createComRecordTransformer = $createComRecordTransformer;
        $this->comRecordTransformer = $comRecordTransformer;
    }

    public function comRecordEmptyFormShow()
    {
        // AUTHORIZE
        $this->authorize('view');

        $communicationChannels = $this->getCommunicationChannels();

        $createComRecordFormData = [
            'communication_channels'=>$communicationChannels
        ];
        $createComRecordFormData = $this->createComRecordTransformer->transformDataForEmptyForm($createComRecordFormData);

        return $this->respond($createComRecordFormData);
    }

    public function storeComRecord(StoreComRecordPost $request)
    {
        $comRecordData = [
            'channel_id'=>Input::get('channel_id'),
            'lead_id'=>Input::get('lead_id'),
            'user_id'=>Auth::id(),
            'value'=>Input::get('value')
        ];

        $comRecord = $this->saveComRecord($comRecordData);

        if(isset($comRecord->id) && $comRecord->id >0){
            return $this->respondCreated("new Communication Record successfully created!");
        }else{
            return $this->respondDataConflict("Due to unknown reason Communication Record was not saved!");
        }
    }

    public function editComRecord($id)
    {
        $comRecord = CommunicationRecord::find($id);

        if($comRecord == null){
            return $this->respondNoContent("Communication Record with ID {$id} does not exits!");
        }

        $comRecord = $this->comRecordTransformer->transformOneModel($comRecord);

        return $this->respond($comRecord);
    }

    public function updateComRecord(UpdateComRecordPost $request, $id)
    {
        $comRecord = CommunicationRecord::find($id);

        if($comRecord === null){
            return $this->respondNoContent("Communication Record with ID {$id} does not exists!");
        }

        $comRecordData = [
            'channel_id' => Input::get('channel_id'),
            'lead_id' => Input::get('lead_id'),
            'user_id' => Input::get('user_id'),
            'value' => Input::get('value')
        ];

        $updateComRecordStatus = $comRecord->update($comRecordData);

        if($updateComRecordStatus === true){
            return $this->respondUpdated("Communication record was updated!");
        }else{
            return $this->respondDataConflict("Due to unknown reason Communication Record was not updated!");
        }

    }

    public function deleteComRecord($id)
    {
        $comRecord = CommunicationRecord::find($id);

        if($comRecord == null){
            return $this->respondNoContent("Communication Record with requested ID {$id} was not found!");
        }

        if(Auth::user()->authHasRole() == config('constants.roles.admin') || Auth::id() == $comRecord->user_id){
            $comRecord->delete();
            return $this->respondDeleted("Communication Record with ID {$id} was successfully deleted!");
        }else{
            return $this->respondActionForbidden("Only Admin or Record's Author can delete this comm. record! You are not authorized!");
        }
    }
    #endregion

    #region SERVICE METHODS
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

    private function saveComRecord($comRecordData)
    {
        $comRecord = CommunicationRecord::create($comRecordData);

        return $comRecord;
    }
    #endregion
}
