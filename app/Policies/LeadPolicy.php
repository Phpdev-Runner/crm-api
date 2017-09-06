<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Lead;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if($user->role_id == Role::adminRoleId()){
            return true;
        }
    }

    /**
     * Determine whether the user can view the lead.
     *
     * @param  \App\User  $user
     * @param  \App\Lead  $lead
     * @return mixed
     */
    public function view(User $user, Lead $lead)
    {
        return $user->role_id !== Role::unauthorizedRoleId();
    }

    /**
     * Determine whether the user can create leads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role_id !== Role::unauthorizedRoleId();
    }

    /**
     * Determine whether the user can update the lead.
     *
     * @param  \App\User  $user
     * @param  \App\Lead  $lead
     * @return mixed
     */
    public function update(User $user, Lead $lead)
    {
        return $user->role_id !== Role::unauthorizedRoleId();
    }

    /**
     * Determine whether the user can delete the lead.
     *
     * @param  \App\User  $user
     * @param  \App\Lead  $lead
     * @return mixed
     */
    public function delete(User $user, Lead $lead)
    {
        // only admin can
    }
}
