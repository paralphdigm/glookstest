<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\SizeCategory;
use App\SizeTypeCategory;
use DB;
use App\Http\Requests;

class SizeTypeCategoriesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = Input::get('limit') ?: 20;
        $sizetypecategories = SizeTypeCategory::paginate($limit);

        if( ! $sizetypecategories->count() > 0)
        {
            return $this->respondNoRecord();
        }
        return $this->respondWithPaginator($sizetypecategories,[
            'data' => $this->transformWithPaginate($sizetypecategories)
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
        if(! $request['size_category_id'] or ! $request['size'])
        {
            return $this->respondInvalid();
        }
        $data = $request->all();
        $sizecategory = SizeCategory::where('id',$data['size_category_id'])->get();
      
        if(! $sizecategory->count() > 0){
            return $this->respondInvalid('Size Category ID does not exist');
        }
        SizeTypeCategory::create([
            'size_category_id' => $data['size_category_id'],
            'size' => $data['size'],
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
        $sizetypecategory = SizeTypeCategory::find($id);
        if(! $sizetypecategory){
            return $this->respondNotFound('Record does not exist');

        }
        return Response::json([
            'data' => $this->transform($sizetypecategory->toArray())
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
        $sizetypecategory = SizeTypeCategory::find($id); 
        if(! $sizetypecategory){
            return $this->respondNotFound();
        }
        $input = $request->all();
        $sizecategory = SizeCategory::where('id',$input['size_category_id'])->get();

        if(! $sizecategory->count() > 0){
            return $this->respondInvalid('Size Category is does not exist');
        }
        $sizetypecategory->fill($input)->save();
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
        $sizetypecategory = SizeTypeCategory::find($id);
        if(! $sizetypecategory){
            return $this->respondNotFound();
        } 
        $sizetypecategory->delete();
        return $this->respondSuccess();
    }
    public function transform($sizetypecategory)
    {
        return [
            'size' => $sizetypecategory['size'],
        ];

    }
    private function transformWithPaginate($sizetypecategories)
    {
        
        $itemsTransformed = $sizetypecategories
            ->getCollection()
            ->map(function($sizetypecategory) {
                $sizetypecategoryid = $sizetypecategory['size_category_id'];
                $sizecategory = SizeCategory::find($sizetypecategoryid);
                return [
                    'category' => $sizecategory['name'],
                    'size' => $sizetypecategory['size'],
                ];
        })->toArray();

        return $itemsTransformed;
    }
}
