<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\MethodOfContract;
use DB;
use App\Http\Requests;

class MethodOfContractsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $limit = Input::get('limit') ?: 3;
        $methodofcontracts = MethodOfContract::paginate($limit);
        if( ! $methodofcontracts->count() > 0)
        {
            return $this->respondNoRecord();
        }

        return $this->respondWithPaginator($methodofcontracts,[
            'data' => $this->transformWithPaginate($methodofcontracts)
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
        $data = $request->all();
        MethodOfContract::create([
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
        $methodofcontract = MethodOfContract::find($id);
        if(! $methodofcontract){
            return $this->respondNotFound('Record does not exist');

        }
        return Response::json([
            'data' => $this->transform($methodofcontract->toArray())
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
        $methodofcontract = MethodOfContract::find($id); 
        if(! $methodofcontract){
            return $this->respondNotFound();
        }
        $input = $request->all();
        $methodofcontract->fill($input)->save();
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
        $methodofcontract = MethodOfContract::find($id);
        if(! $methodofcontract){
            return $this->respondNotFound();
        } 
        $methodofcontract->delete();
        return $this->respondSuccess();
    }

    public function transform($methodofcontract)
    {
        return [
            'name' => $methodofcontract['name'],
            'description' => $methodofcontract['description']
        ];

    }
    private function transformWithPaginate($methodofcontracts)
    {
        
        $itemsTransformed = $methodofcontracts
            ->getCollection()
            ->map(function($methodofcontract) {
                return [
                    'name' => $methodofcontract['name'],
            'description' => $methodofcontract['description']
                ];
        })->toArray();

        return $itemsTransformed;
    }
}
