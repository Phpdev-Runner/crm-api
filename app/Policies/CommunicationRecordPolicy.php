<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\CommunicationRecord;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommunicationRecordPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        $adminRoleID = Role::adminRoleId();

        if($user->role_id == $adminRoleID){
            return true;
        }
    }

    /**
     * Determine whether the user can view the communicationRecord.
     *
     * @param  \App\User  $user
     * @param  \App\CommunicationRecord  $communicationRecord
     * @return mixed
     */
    public function view(User $user)
    {
        dd("WTF?");
        return $user->role_id !== Role::unauthorizedRoleId();
    }

    /**
     * Determine whether the user can create communicationRecords.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the communicationRecord.
     *
     * @param  \App\User  $user
     * @param  \App\CommunicationRecord  $communicationRecord
     * @return mixed
     */
    public function update(User $user, CommunicationRecord $communicationRecord)
    {
        //
    }

    /**
     * Determine whether the user can delete the communicationRecord.
     *
     * @param  \App\User  $user
     * @param  \App\CommunicationRecord  $communicationRecord
     * @return mixed
     */
    public function delete(User $user, CommunicationRecord $communicationRecord)
    {
        //
    }
}
