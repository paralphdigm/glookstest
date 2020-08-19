<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Policy;
use DB;
use App\Http\Requests;

class PoliciesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = Input::get('limit') ?: 3;
        $policies = Policy::paginate($limit);
        if( ! $policies->count() > 0)
        {
            return $this->respondNoRecord();
        }
        return $this->respondWithPaginator($policies,[
            'data' => $this->transformWithPaginate($policies)
        ]);
    
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(! $request['title'] or ! $request['content'])
        {
            return $this->respondInvalid();
        }
        $data = $request->all();
        Policy::create([
                'title' => $data['title'],
                'content' => $data['content'],
        ])->save();

        return $this->respondAccepted();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $policy = Policy::find($id);
        if(! $policy){
            return $this->respondNotFound('Record does not exist');

        }
        return Response::json([
            'data' => $this->transform($policy->toArray())
        ],200);
    
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
        $policy = Policy::find($id);

        if(! $policy){
            return $this->respondNotFound();
        } 
        $input = $request->all();
        $policy->fill($input)->save();

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
        $policy = Policy::find($id);
        if(! $policy){
            return $this->respondNotFound();
        } 
        $policy->delete();
        return $this->respondSuccess();
    }

    public function transform($policy)
    {
        return [
            'title' => $policy['title'],
            'content' => $policy['content']
        ];

    }
    private function transformWithPaginate($policies)
    {
        
        $itemsTransformed = $policies
            ->getCollection()
            ->map(function($policy) {
                return [
                    'title' => $policy['title'],
                    'content' => $policy['content']
                ];
        })->toArray();

        return $itemsTransformed;
    }
}
