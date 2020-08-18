<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
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
        
        $adsettings = AdSetting::all();
        if(! $adsettings->count() > 0)
        {
            return $this->respondNoRecord();
        }

        return Response::json([

            'data' => $this->transformCollection($adsettings)

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
            'data' => $this->transform($requirement->toArray())
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
    private function transformCollection($adsettings)
    {
        return array_map([$this, 'transform'], $adsettings->toArray());
    }

    private function transform($adsetting)
    {
        return [
            'impression_per_dollar' => $adsetting['name'],
            'reach_per_dollar' => $adsetting['description']
        ];

    }
}
