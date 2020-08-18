<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\Http\Resources\Users as UserResource;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Auth;


class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        if( ! $users->count() > 0)
        {
            return $this->respondNoRecord();
        }

        return Response::json([

            'data' => $this->transformCollection($users)

        ],200);
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
        if(! $request['first_name'] or ! 
        $request['last_name'] or ! 
        $request['email'] or ! 
        $request['last_name'] or ! 
        $request['gender'] or ! 
        $request['mobile_number'] or ! 
        $request['last_name'] or ! 
        $request['landline_number'] or ! 
        $request['marital_status'])
        {
            return $this->respondInvalid();
        }
        $data = $request->all();
        /* Get credentials from .env */
        // Phone number verification//
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create('+' . $data['mobile_number'], "sms");

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
        
        return $this->respondAccepted();
    }
    public function verifyPhone(Request $request){

        $data = $request->validate([
            'verification_code' => ['required', 'numeric'],
            'mobile_number' => ['required', 'string'],
        ]);
        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create($data['verification_code'], array('to' => $data['mobile_number']));
        if ($verification->valid) {
            $user = tap(User::where('mobile_number', $data['mobile_number']))->update(['verification_code_status' => 'active']);
            /* Authenticate user */
            Auth::login($user->first());
            return $this->respondAccepted();
        }
        return $this->respondInvalid();
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
        if(! $user){
            return $this->respondNotFound('Record does not exist');

        }
        return Response::json([
            'data' => $this->transform($user->toArray())
        ],200);
    }
    public function login(Request $request)
    {
        $user = User::where('email', $request['email']);

        // if($user->account_status != 'active')
        // {
        //     return $this->respondUnauthorized('Account not active');
        // }
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if(!Auth::attempt($data)){
            return $this->respondUnauthorized('Invalid Login Credentials');
        }
        // Creating a token without scopes...
        $accesstoken = Auth::user()->createToken('authToken')->accessToken;

        return response(['user' => Auth::user(),'access_token' => $accesstoken]);
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
        if(! $user){
            return $this->respondNotFound();
        }
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

        return $this->respondSuccess();
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
        if(! $user){
            return $this->respondNotFound();
        } 
        $user->delete();
        return $this->respondSuccess();
    }
    private function transformCollection($users)
    {
        return array_map([$this, 'transform'], $users->toArray());
    }

    private function transform($user)
    {
        return [
            'membership_number' => $user['membership_number'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'gender' => $user['gender'],
            'mobile_number' => $user['mobile_number'],
            'landline_number' => $user['landline_number'],
            'birthdate' => $user['birthdate'],
            'marital_status' => $user['marital_status'],
            'account_status' => $user['account_status'],
            'created_by' => $user['created_by'],
            'updated_by' => $user['updated_by'],
            'deleted_by' => $user['deleted_by'],
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at']
        ];

    }
}
