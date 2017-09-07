<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePasswordPost;
use App\Http\Requests\StoreUserPost;
use App\Http\Requests\UpdateUserPost;
use App\Jobs\SendMailJob;
use App\Mail\InviteNewUser;
use App\PasswordResets;
use App\Role;
use App\User;
use App\Transformers\UserTransformer;
use App\Transformers\CreateUserTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

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
        // AUTHORIZE
        $this->authorize('view',User::class);

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
        // AUTHORIZE
        $this->authorize('view',User::class);

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
    public function storeManager(StoreUserPost $request)
    {
        // AUTHORIZE
        $this->authorize('create',User::class);

	    $name = Input::get('name');
	    $email = Input::get('email');
	    $role_id = Input::get('role_id');
	    $role_id = ($role_id === null)? Role::unauthorizedRoleId():$role_id;
	    $password = bcrypt(Input::get('password'));

	    $user = new User();
	    $user->name = $name;
	    $user->email = $email;
	    $user->role_id = $role_id;
	    $user->password = $password;
	    $user->save();

	    // GENERATE TOKEN TO SETTLE NEW PASSWORD FOR USER FIRST LOGIN
        $tokenID = $this->setTokenToChangePassword($user);

        if($tokenID !== false && $tokenID > 0){
            // SEND INVITATION TO NEW USER
            $this->sendNewUserInvitation($user);
        }else{
            return $this->respondDataConflict("Due to unknown reason Token to set password for new User was not created!");
        }

	    return $this->respondCreated("new user successfully created!");
    }

    /**
     * Shows form for new user to set new password
     */
    public function setNewPassword($token)
    {
        $tokenPresence = $this->checkTokenPresence($token);

        if($tokenPresence !== null){
            return view('auth.passwords.set-new-password')->with('token',$token);
        }else{
            dd("Link your are following is not valid any more!");
        }
    }

    /**
     * Store new password in DB for new user
     */
    public function storeNewPassword(StorePasswordPost $request)
    {
        $email = Input::get('email');
        $password = Input::get('password');
        $setPasswordStatus = $this->storeNewlyCreatedPassword($email, $password);

        if($setPasswordStatus > 0){
            return $this->respondUpdated("password changed successfully");
        }else{
            return $this->respondDataConflict("Due to unknown reason password was not changed");
        }
    }
	
	/**
	 * + Show the form for editing the specified resource.
	 */
	public function editManager($id)
	{
		$manager = $this->findUser($id);

        if($manager == null) {
            return $this->respondNoContent("There is no user with ID {$id}");
        }

        // AUTHORIZE
        $this->authorize('update',$manager);

        $manager = $this->userTransformer->transformOneModel($manager);
        return $this->respond($manager);
	}
 
	/**
	 * + Update the specified resource in storage.
	 */
	public function updateManager(UpdateUserPost $request, $id)
	{
		$manager = $this->findUser($id);

        if($manager == null) {
            return $this->respondNoContent("There is no user with ID {$id}");
        }

        // AUTHORIZE
        $this->authorize('update',$manager);

        $manager->name = Input::get('name');
        $manager->email = Input::get('email');
        $manager->role_id = Input::get('role_id');
        $manager->save();

        return $this->respondUpdated("User with ID {$manager->id} updated successfully");

	}
	
    /**
     * Remove (soft delete) the specified resource from storage.
     */
    public function deleteManager($userID)
    {
        $user = $this->findUser($userID);

        if($user === null){
            return $this->respondNoContent("User with requested ID {$userID} was not found!");
        }

        // AUTHORIZE
        $this->authorize('delete',$user);

        $user->delete();
        return $this->respondDeleted("Manager with ID {$userID} was soft-deleted!");

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

	private function sendNewUserInvitation(User $user)
    {
        $message = (new InviteNewUser(Auth::user(), $user))
            ->onConnection('high');

        Mail::to($user)
            ->queue($message);
    }

    private function setTokenToChangePassword(User $user)
    {
        $tokenID = PasswordResets::setTokenToMakeNewPassword($user);
        return $tokenID;
    }

    private function checkTokenPresence($token)
    {
        $resetPassword = PasswordResets::checkTokenPresence($token);
        return $resetPassword;
    }

    private function storeNewlyCreatedPassword($email, $password)
    {
        $newPasswordStatus = User::storeNewPassword($email,$password);
        return $newPasswordStatus;
    }

	#endregion
}
