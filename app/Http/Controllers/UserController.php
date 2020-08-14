<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\Http\Resources\Users as UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(15);

        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        User::create([
                'membership_number' => $data['membership_number'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'gender' => $data['gender'],
                'mobile_number' => $data['mobile_number'],
                'landline_number' => $data['landline_number'],
                'birthdate' => $data['birthdate'],
                'verification_code' => $data['verification_code'],
                'verification_code_status' => $data['verification_code_status'],
                'marital_status' => $data['marital_status'],
                'account_status' => $data['account_status'],
                'email_verified_at' => $data['email_verified_at'],
                'password' => Hash::make($data['password']),
                'remember_token' => $data['remember_token'],
                'created_by' => $data['created_by'],
                'updated_by' => $data['updated_by'],
                'deleted_by' => $data['deleted_by'],
        ])->save();

        return 201;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
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
        $user = User::find($id); 

        $data = $request->all();
        $user->fill([
                'membership_number' => $data['membership_number'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'gender' => $data['gender'],
                'mobile_number' => $data['mobile_number'],
                'landline_number' => $data['landline_number'],
                'birthdate' => $data['birthdate'],
                'verification_code' => $data['verification_code'],
                'verification_code_status' => $data['verification_code_status'],
                'marital_status' => $data['marital_status'],
                'account_status' => $data['account_status'],
                'email_verified_at' => $data['email_verified_at'],
                'password' => Hash::make($data['password']),
                'remember_token' => $data['remember_token'],
                'created_by' => $data['created_by'],
                'updated_by' => $data['updated_by'],
                'deleted_by' => $data['deleted_by'],
        ])->save();

        return 200;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
	    $user->delete();
    }
}
