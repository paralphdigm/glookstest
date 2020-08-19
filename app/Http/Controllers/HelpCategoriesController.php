<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\HelpCategory;
use DB;
use App\Http\Requests;

class HelpCategoriesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = Input::get('limit') ?: 3;
        $helpcategories = HelpCategory::paginate($limit);
  
        if( ! $helpcategories->count() > 0)
        {
            return $this->respondNoRecord();
        }

        return $this->respondWithPaginator($helpcategories,[
            'data' => $this->transformWithPaginate($helpcategories)
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
        if(! $request['name'] or ! $request['description'])
        {
            return $this->respondInvalid();
        }
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        HelpCategory::create([
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
        $helpcategory = HelpCategory::find($id);
        if(! $helpcategory){
            return $this->respondNotFound('Record does not exist');

        }
        return Response::json([
            'data' => $this->transform($helpcategory->toArray())
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
        $helpcategory = HelpCategory::find($id); 
        if(! $helpcategory){
            return $this->respondNotFound();
        }
        $input = $request->all();
        $helpcategory->fill($input)->save();
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
        $helpcategory = HelpCategory::find($id);
        if(! $helpcategory){
            return $this->respondNotFound();
        } 
        $helpcategory->delete();
        return $this->respondSuccess();
    }

    public function transform($helpcategory)
    {
        return [
            'name' => $helpcategory['name'],
            'description' => $helpcategory['description']
        ];

    }
    private function transformWithPaginate($helpcategories)
    {
        
        $itemsTransformed = $helpcategories
            ->getCollection()
            ->map(function($helpcategory) {
                return [
                    'name' => $helpcategory['name'],
            'description' => $helpcategory['description']
                ];
        })->toArray();

        return $itemsTransformed;
    }
}
