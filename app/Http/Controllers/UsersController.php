<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\Transformers\UserTransformer;
use App\Transformers\CreateUserTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class UsersController extends ApiController
{
	use SoftDeletes;
	
	#region CLASS PROPERTIES
	protected $dates = ['deleted_at'];
	protected $userTransformer;
	protected $createUserTransformer;
	#endregion
	
	
	#region MAIN METHODS
	
	public function __construct(UserTransformer $userTransformer, CreateUserTransformer $createUserTransformer)
	{
		$this->userTransformer = $userTransformer;
		$this->createUserTransformer = $createUserTransformer;
	}
	
    /**
     * + Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewManagers()
    {
        $users = $this->getUsersWithRoles([config('constants.roles.manager')]);

        $users = $this->userTransformer->transformManyCollections($users);
        
         if(!$users){
         	return $this->respondNoContent('Users table has no managers');
        }
        
        return $this->respond([
        	'users'=>$users
        ]);
    }
	
	/**
	 * + supply roles data for create user form
	 * @return mixed
	 */
    public function userEmptyFormShow()
    {
    	$userEmptyFormData = $this->getDataForUserEmptyForm();
    	$userEmptyFormData = $this->createUserTransformer->
	        transformDataForEmptyForm($userEmptyFormData);
    	return $this->respond($userEmptyFormData);
    }

    /**
     * + Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeManager(Request $request)
    {
	    $name = Input::get('name');
	    $email = Input::get('email');
	    $role_id = Input::get('role_id');
	    $role_id = ($role_id === null)? Role::unauthorizedRoleId():$role_id;
	    $password = bcrypt(Input::get('password'));
	    
	    if(!$name || !$email || !$role_id || !$password){
	    	return $this->respondBadRequest('user registration fields did not passed validation');
	    }
		
	    if($this->checkNewEmailDuplicatePresence(Input::get('email')=== true)){
		    return $this->respondBadRequest("Another user with email ".Input::get('email')." exists in DB!");
	    }

	    $user = new User();
	    $user->name = $name;
	    $user->email = $email;
	    $user->role_id = $role_id;
	    $user->password = $password;
	    $user->save();
     
	    return $this->respondCreated("new user successfully created!");
    }
	
	/**
	 * + Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function editManager($id)
	{
		$manager = $this->findUser($id);
		
		if($manager == null) {
			return $this->respondNoContent("There is no user with ID {$id}");
		}
		
		if($manager->role->name == config('constants.roles.manager')){
			$manager = $this->userTransformer->transformOneModel($manager);
			return $this->respond($manager);
		}else{
			return $this->respondBadRequest("Selected user is not a manager");
		}
	}
 
	/**
	 * + Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateManager($id)
	{
		$manager = $this->findUser($id);
		
		if($manager == null) {
			return $this->respondNoContent("There is no user with ID {$id}");
		}
		
		if(!Input::get('name') || !Input::get('email') || !Input::get('role_id')){
			return $this->respondBadRequest("Some input data missing needed to update manager");
		}
		
		if($this->checkNewEmailDuplicatePresence(Input::get('email'), $manager->id) === true){
			return $this->respondBadRequest("Another user with email ".Input::get('email')." exists in DB!");
		}

		if($manager->role->name == config('constants.roles.manager')){
			$manager->name = Input::get('name');
			$manager->email = Input::get('email');
			$manager->role_id = Input::get('role_id');
			$manager->save();
			return $this->respondUpdated("User with ID {$manager->id} updated successfully");
		}else{
			return $this->respondBadRequest("User with ID {$id} is not a manager");
		}
	}
	
    /**
     * Remove (soft delete) the specified resource from storage.
     *
     * @param  int  $userID
     * @return \Illuminate\Http\Response
     */
    public function deleteManager($userID)
    {
        $user = $this->findUser($userID);

        if($user === null){
        	return $this->respondNoContent("User with requested ID {$userID} was not found!");
        }
        
        if($user->role->name == config('constants.roles.manager')){
            $user->delete();
            return $this->respondDeleted("Manager with ID {$userID} was soft-deleted!");
        }else{
        	return $this->respondBadRequest("User with ID {$userID} is not a manager!");
        }
    }
    #endregion
	
	#region SERVICE METHODS
	private function getDataForUserEmptyForm()
	{
		return Role::getAllRoles()->toArray();
	}
	
	private function getUsersWithRoles($requiredRoles)
	{
		$users = User::getUsersWithRoles($requiredRoles);
		return $users;
	}
	
	private function findUser($id)
	{
		$user = User::find($id);
		return $user;
	}
	
	private function checkNewEmailDuplicatePresence($newEmail, $userID = null)
	{
		$duplicateStatus = User::checkNewEmailDuplicate($newEmail, $userID);

		return $duplicateStatus;
	}
	#endregion
}
