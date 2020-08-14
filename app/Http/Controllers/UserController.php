<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\Http\Resources\Users as UserResource;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;

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
        
        return null;
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
            return 200;
        }
        return 404;
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
