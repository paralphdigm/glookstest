<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\PreferenceCategory;
use DB;
use App\Http\Requests;

class PreferenceCategoriesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $preferencecategories = PreferenceCategory::all();

        if( ! $preferencecategories->count() > 0)
        {
            return $this->respondNoRecord();
        }

        return Response::json([

            'data' => $this->transformCollection($preferencecategories)

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
        if(! $request['name'] or ! $request['description'])
        {
            return $this->respondInvalid();
        }
        $data = $request->all();
        PreferenceCategory::create([
                'name' => $data['name'],
                'description' => $data['description'],
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
        $preferencecategory = PreferenceCategory::find($id);
        if(! $preferencecategory){
            return $this->respondNotFound('Record does not exist');

        }
        return Response::json([
            'data' => $this->transform($preferencecategory->toArray())
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
        $preferencecategory = PreferenceCategory::find($id); 
        if(! $preferencecategory){
            return $this->respondNotFound();
        }
        $input = $request->all();
        $preferencecategory->fill($input)->save();
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
        $preferencecategory = PreferenceCategory::find($id);
        if(! $preferencecategory){
            return $this->respondNotFound();
        }
        $preferencecategory->delete();
        return $this->respondSuccess();
    }
    private function transformCollection($preferencecategories)
    {
        return array_map([$this, 'transform'], $preferencecategories->toArray());
    }

    private function transform($preferencecategory)
    {
        return [
            'name' => $preferencecategory['name'],
            'description' => $preferencecategory['description']
        ];

    }
}
