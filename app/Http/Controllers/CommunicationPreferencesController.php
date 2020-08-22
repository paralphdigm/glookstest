<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use App\PreferenceCategory;
use App\CommunicationPreference;
use DB;
use App\Http\Requests;

class CommunicationPreferencesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = Input::get('limit') ?: 20;
        $communicationcategories = CommunicationPreference::paginate($limit);

        if( ! $communicationcategories->count() > 0)
        {
            return $this->respondNoRecord();
        }
        return $this->respondWithPaginator($communicationcategories,[
            'data' => $this->transformWithPaginate($communicationcategories)
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
        if(! $request['preference_category_id'] or ! $request['name'])
        {
            return $this->respondInvalid();
        }
        $data = $request->all();
        $preferencecategory = PreferenceCategory::where('id',$data['preference_category_id'])->get();
      
        if(! $preferencecategory->count() > 0){
            return $this->respondInvalid('Preference Category ID does not exist');
        }
        CommunicationPreference::create([
            'preference_category_id' => $data['preference_category_id'],
            'name' => $data['name'],
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
        $communicationcategory = CommunicationPreference::find($id);
        if(! $communicationcategory){
            return $this->respondNotFound('Record does not exist');

        }
        return Response::json([
            'data' => $this->transform($communicationcategory->toArray())
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
        $communicationcategory = CommunicationPreference::find($id); 
        if(! $communicationcategory){
            return $this->respondNotFound();
        }
        $input = $request->all();
        $preferencecategory = PreferenceCategory::where('id',$input['preference_category_id'])->get();

        if(! $preferencecategory->count() > 0){
            return $this->respondInvalid('Preference Category is does not exist');
        }
        $communicationcategory->fill($input)->save();
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
        $communicationcategory = CommunicationPreference::find($id);
        if(! $communicationcategory){
            return $this->respondNotFound();
        } 
        $communicationcategory->delete();
        return $this->respondSuccess();
    }
    public function transform($communicationcategory)
    {
        $preferencecategoryid = $communicationcategory['preference_category_id'];
        $preferencecategory = PreferenceCategory::find($preferencecategoryid);

        return [
            'category' => $preferencecategory['name'],
            'name' => $communicationcategory['name'],
        ];

    }
    private function transformWithPaginate($communicationcategories)
    {
        
        $itemsTransformed = $communicationcategories
            ->getCollection()
            ->map(function($communicationcategory) {

                $preferencecategoryid = $communicationcategory['preference_category_id'];
                $preferencecategory = PreferenceCategory::find($preferencecategoryid);
                return [
                    'category' => $preferencecategory['name'],
                    'name' => $communicationcategory['name'],
                ];
        })->toArray();

        return $itemsTransformed;
    }
}
