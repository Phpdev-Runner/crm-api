<?php

namespace App\Http\Controllers;

use App\Role;
use App\Transformers\UserTransformer;
use App\User;
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
	#endregion
	
	
	#region MAIN METHODS
	
	public function __construct(UserTransformer $userTransformer)
	{
		$this->userTransformer = $userTransformer;
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewManagers()
    {
        $users = $this->getUsersWithRoles([config('roles.manager')]);

        $users = $this->userTransformer->transformCollection($users->toArray());
        
        if(!$users){
        	return $this->respondNotFound('Users table has no managers');
        }
        
        return $this->respond([
        	'users'=>$users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeManager(Request $request)
    {
	    $validation = Validator::make(Request::all(),[
		    'name' => 'required',
		    'email' => 'required',
	    ]);
	    
	    $name = Input::get('name');
	    $email = Input::get('email');
	    $role_id = Input::get('role_id');
	    $role_id = ($role_id === null)? Role::unauthorizedRoleId():$role_id;
	    $password = bcrypt(Input::get('password'));
	    
	    if(!$name || !$email || !$role_id || !$password){
	    	return $this->respondBadRequest('user registration fields did not passed validation');
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
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateManager(Request $request, $id)
	{
		$manager = User::find($id);
		if($manager == null){
			return $this->respondBadRequest("There is no user with ID {$id}");
		}
		
		if($manager->role->name == config('roles.manager')){
			$manager->name = Input::get('name');
			$manager->email = Input::get('email');
			$manager->role_id = Input::get('role_id');
			$manager->save();
		}else{
			return $this->respondBadRequest("User with ID {$id} is not a manager");
		}
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    #endregion
	
	#region SERVICE METHODS
	private function getUsersWithRoles($requiredRoles)
	{
		$users = User::getUsersWithRoles($requiredRoles);
		return $users;
	}
	#endregion
}
