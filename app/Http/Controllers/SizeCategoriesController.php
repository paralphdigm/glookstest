<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\SizeCategory;
use App\SizeTypeCategory;
use DB;
use App\Http\Requests;

class SizeCategoriesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = Input::get('limit') ?: 10;
        $sizecategories = SizeCategory::paginate($limit);
        if(! $sizecategories->count() > 0)
        {
            return $this->respondNoRecord();
        }

        return $this->respondWithPaginator($sizecategories,[
            'data' => $this->transformWithPaginate($sizecategories)
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

        SizeCategory::create([
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
        $sizecategory = SizeCategory::find($id);
        if(! $sizecategory){
            return $this->respondNotFound('Record does not exist');

        }
        return Response::json([
            'data' => $this->transform($sizecategory->toArray())
        ],200);

    }
    public function showCategorySizeTypes($id = null)
    {
        $limit = Input::get('limit') ?: 20;
        $sizetypecategories = $id ? SizeCategory::find($id)->size_type_categories : SizeCategory::paginate($limit);
        
        return response()->json([
            'data' => $this->transformCollectionAlt($sizetypecategories),
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
        $sizecategory = SizeCategory::find($id); 
        if(! $sizecategory){
            return $this->respondNotFound();
        }
        $input = $request->all();
        $sizecategory->fill($input)->save();
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
        $sizecategory = SizeCategory::find($id);
        if(! $sizecategory){
            return $this->respondNotFound();
        } 
        $sizecategory->delete();
        return $this->respondSuccess();
    }
    public function transform($sizecategory)
    {
        return [
            'name' => $sizecategory['name'],
            'description' => $sizecategory['description']
        ];

    }
    public function transformAlt($sizecategory)
    {
        $categoryid = $sizecategory['size_category_id'];
        $category = SizeCategory::find($categoryid);
        
        return [
            'name' => $category['name'],
            'size' => $sizecategory['size']
        ];

    }
   
    private function transformWithPaginate($sizecategories)
    {
        
        $itemsTransformed = $sizecategories
            ->getCollection()
            ->map(function($sizecategory) {
                return [
                    'name' => $sizecategory['name'],
                    'description' => $sizecategory['description']
                ];
        })->toArray();

        return $itemsTransformed;
    }
}
