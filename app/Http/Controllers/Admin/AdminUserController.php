<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\UserProfile;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::where('user_type','admin')->paginate(env('PER_PAGE'))->withQueryString();
        return view('admin.admin-users', array('users' => $users));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.create-admin-user',array('roles' => $roles, 'permissions' => $permissions));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
       
        $request->validate(
            [
                'first_name' => 'required|string|min:2|max:50',
                'middle_name' => 'nullable|string|min:2|max:50',
                'last_name' => 'nullable|string|min:2|max:50',
                'email' => 'required|email|unique:users',
                'mobile' => 'nullable|integer|',
                'age' => 'nullable|integer',
                'gender' => 'nullable|string',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]
        );

        $password = Hash::make($request->password);
        $userId = Str::uuid();
        $saved = User::create(array_merge($request->all(),['uuid'=> $userId,'password' => $password,'user_type'=>'admin']));
        UserProfile::create(array_merge($request->all(),['id'=> Str::uuid(),'user_id'=> $userId]));

        // create permissions
        $roles = $request->roles;
        $permissions = $request->permissions;
        foreach ($roles as $role){
            $saved->syncRoles($role);
        }
        $saved->syncPermissions($permissions);


        if($saved)
            return redirect(route('create-admin-user'))->with('success','User created successfully');
        else
            return redirect(route('create-admin-user'))->with('error','Can\'t create user');
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
}
