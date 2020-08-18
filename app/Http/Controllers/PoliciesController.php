<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
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
        $policies = Policy::all();
        if( ! $policies->count() > 0)
        {
            return $this->respondNoRecord();
        }
        return Response::json([

            'data' => $this->transformCollection($policies)

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
    private function transformCollection($requirements)
    {
        return array_map([$this, 'transform'], $requirements->toArray());
    }

    private function transform($requirement)
    {
        return [
            'title' => $requirement['title'],
            'content' => $requirement['content']
        ];

    }
}
