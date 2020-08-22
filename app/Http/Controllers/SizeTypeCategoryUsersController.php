<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\SizeTypeCategoryUser;
use App\SizeTypeCategory;
use App\SizeCategory;
use App\User;
use DB;
use App\Http\Requests;

class SizeTypeCategoryUsersController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = Input::get('limit') ?: 20;
        $sizetypecategoryusers = SizeTypeCategoryUser::paginate($limit);

        if( ! $sizetypecategoryusers->count() > 0)
        {
            return $this->respondNoRecord();
        }
        return $this->respondWithPaginator($sizetypecategoryusers,[
            'data' => $this->transformWithPaginate($sizetypecategoryusers)
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
        foreach ($request->size_type_category_users as $key => $value) {

            $size = $value['size_type_category_id'];
            $user = $value['user_id'];
            
            $checker = SizeTypeCategoryUser::where([
                ['user_id', $user],
                ['size_type_category_id', $size],
            ])->get();

            if(! $checker->count() > 0){

                SizeTypeCategoryUser::create([
                    'user_id' => $value['user_id'],
                    'size_type_category_id' => $value['size_type_category_id'],
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
    public function showUserSizes($id = null)
    {
        $limit = Input::get('limit') ?: 20;
        $sizetypecategoryusers = $id ? User::find($id)->size_type_category_users : SizeCategory::paginate($limit);

        // return response()->json($sizetypecategoryusers);
        return response()->json([
            'data' => $this->transformCollection($sizetypecategoryusers),
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
        DB::table("size_type_category_users")->where("size_type_category_users.user_id",$id)
            ->delete();
        
        foreach ($request->size_type_category_users as $key => $value) {
            SizeTypeCategoryUser::create([
                'user_id' => $value['user_id'],
                'size_type_category_id' => $value['size_type_category_id'],
            ])->save();
        }
        return $this->respondSuccess();
    }

    public function transform($sizetypecategoryusers)
    {
        $sizetypecategoryid = $sizetypecategoryusers['size_type_category_id'];
        $sizetypecategory = SizeTypeCategory::find($sizetypecategoryid);
 
        $sizecategoryid = $sizetypecategory['size_category_id'];
        $sizecategory = SizeCategory::find($sizecategoryid);
        
        $userid = $sizetypecategoryusers['user_id'];
        $user = User::find($userid);

            return [
                'user' => $user['membership_number'],
                'size' => $sizetypecategory['size'],
                'category' => $sizecategory['name'],
            ];

    }
    private function transformWithPaginate($sizetypecategoryusers)
    {
        
        $itemsTransformed = $sizetypecategoryusers
            ->getCollection()
            ->map(function($sizetypecategoryuser) {

                $sizetypecategoryid = $sizetypecategoryuser['size_type_category_id'];
                $sizetypecategory = SizeTypeCategory::find($sizetypecategoryid);

                $sizecategoryid = $sizetypecategory['size_category_id'];
                $sizecategory = SizeCategory::find($sizecategoryid);

                $userid = $sizetypecategoryuser['user_id'];
                $user = User::find($user);

                return [
                    'user' => $user['membership_number'],
                    'size' => $sizetypecategory['name'],
                    'category' => $sizecategory['name'],
                ];
        })->toArray();

        return $itemsTransformed;
    }
}
