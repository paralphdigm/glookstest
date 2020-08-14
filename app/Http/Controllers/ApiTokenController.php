<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\ApiToken;
use App\Http\Requests;

class ApiTokenController extends Controller
{
    /**
     * Update the authenticated user's API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(Request $request){

        $data = $request->all();
        $token = Str::random(60);
        $secret = 'SC' . Str::random(60);
        ApiToken::create([
                'app_name' => $data['name'],
                'app_id' => 'APP'. Str::random(60),
                'api_token' => hash('sha256', $token),
                'api_secret' => hash('sha256', $secret),
        ])->save();

        return 200;
    }
    public function update(Request $request, $id)
    {
        $app = ApiToken::find($id); 
        $token = Str::random(60);

        $request->user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return 200;
    }
}
