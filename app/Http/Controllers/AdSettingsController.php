<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\AdSetting;
use DB;
use App\Http\Requests;

class AdSettingsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = Input::get('limit') ?: 3;
        $adsettings = AdSetting::paginate($limit);
        if(! $adsettings->count() > 0)
        {
            return $this->respondNoRecord();
        }

        return $this->respondWithPaginator($adsettings,[
            'data' => $this->transformWithPaginate($adsettings)
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
        if(! $request['impression_per_dollar'] or ! $request['reach_per_dollar'])
        {
            return $this->respondInvalid();
        }
        $data = $request->all();
        AdSetting::create([
                'impression_per_dollar' => $data['impression_per_dollar'],
                'reach_per_dollar' => $data['reach_per_dollar'],
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
        $adsetting = AdSetting::find($id);
        if(! $adsetting){
            return $this->respondNotFound();
        }
        return Response::json([
            'data' => $this->transform($adsetting->toArray())
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
        $adsetting = AdSetting::find($id);
        if(! $adsetting){
            return $this->respondNotFound();
        } 
        $input = $request->all();
        $adsetting->fill($input)->save();

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
        $adsetting = AdSetting::find($id); 
        if(! $adsetting){
            return $this->respondNotFound();
        } 
        $adsetting->destroy();
        return $this->respondSuccess();
    }

    public function transform($adsetting)
    {
        return [
            'impression_per_dollar' => $adsetting['impression_per_dollar'],
            'reach_per_dollar' => $adsetting['reach_per_dollar']
        ];

    }
    private function transformWithPaginate($adsettings)
    {
        
        $itemsTransformed = $adsettings
            ->getCollection()
            ->map(function($adsetting) {
                return [
                    'impression_per_dollar' => $adsetting['impression_per_dollar'],
                    'reach_per_dollar' => $adsetting['reach_per_dollar']
                ];
        })->toArray();

        return $itemsTransformed;
    }
}
