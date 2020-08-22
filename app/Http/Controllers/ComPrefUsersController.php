<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\CommunicationPreferenceUser;
use App\CommunicationPreference;
use App\PreferenceCategory;
use App\User;
use DB;
use App\Http\Requests;

class ComPrefUsersController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = Input::get('limit') ?: 20;
        $communicationPreferenceusers = ComPrefUser::paginate($limit);

        if( ! $communicationPreferenceusers->count() > 0)
        {
            return $this->respondNoRecord();
        }
        return $this->respondWithPaginator($communicationPreferenceusers,[
            'data' => $this->transformWithPaginate($communicationPreferenceusers)
        ],200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->communication_preference_users as $key => $value) {

            $preference = $value['communication_preference_id'];
            $user = $value['user_id'];
            
            $checker = ComPrefUser::where([
                ['user_id', $user],
                ['communication_preference_id', $preference],
            ])->get();

            if(! $checker->count() > 0){

                ComPrefUser::create([
                    'user_id' => $value['user_id'],
                    'communication_preference_id' => $value['communication_preference_id'],
                ])->save();
                return $this->respondAccepted();
            }
            return $this->respondInvalid('Duplicate Entry');
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
        $limit = Input::get('limit') ?: 20;
        $communicationPreferenceusers = $id ? User::find($id)->communication_preference_users : PreferenceCategory::paginate($limit);

        // return response()->json($sizetypecategoryusers);
        return response()->json([
            'data' => $this->transformCollection($communicationPreferenceusers),
        ]);
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
        DB::table("communication_preference_users")->where("communication_preference_users.user_id",$id)
            ->delete();
        
        foreach ($request->communication_preference_users as $key => $value) {
            ComPrefUser::create([
                'user_id' => $value['user_id'],
                'communication_preference_id' => $value['communication_preference_id'],
            ])->save();
        }
        return $this->respondSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function transform($communicationPreferenceusers)
    {
        $communicationpreferenceid = $communicationPreferenceusers['communication_preference_id'];
        $communicationpreference = CommunicationPreference::find($communicationpreferenceid);
 
        $preferencecategoryid = $communicationpreference['preference_category_id'];
        $preferencecategory = PreferenceCategory::find($preferencecategoryid);
        
        $userid = $communicationPreferenceusers['user_id'];
        $user = User::find($userid);

        return [
            'user' => $user['membership_number'],
            'name' => $communicationpreference['name'],
            'category' => $preferencecategory['name'],
        ];

    }
    private function transformWithPaginate($communicationPreferenceusers)
    {
        
        $itemsTransformed = $communicationPreferenceusers
            ->getCollection()
            ->map(function($communicationPreferenceuser) {

                $communicationpreferenceid = $communicationPreferenceuser['communication_preference_id'];
                $communicationpreference = CommunicationPreference::find($communicationpreferenceid);
        
                $preferencecategoryid = $communicationpreference['preference_category_id'];
                $preferencecategory = PreferenceCategory::find($preferencecategoryid);
                
                $userid = $communicationPreferenceuser['user_id'];
                $user = User::find($userid);

                return [
                    'user' => $user['membership_number'],
                    'name' => $communicationpreference['name'],
                    'category' => $preferencecategory['name'],
                ];
        })->toArray();

        return $itemsTransformed;
    }
}
