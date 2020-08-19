<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Requirement;
use DB;
use App\Http\Requests;

class RequirementsController extends ApiController
{
    function __construct()
    {
        // $this->middleware('auth')->only('store');
        // $this->middleware('auth')->only('update');
    }
    public function index()
    {   
        $limit = Input::get('limit') ?: 3;
        $requirements = Requirement::paginate($limit);

        if( ! $requirements->count() > 0)
        {
            return $this->respondNoRecord();
        }
        return $this->respondWithPaginator($requirements,[
            'data' => $this->transformWithPaginate($requirements)
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
        
        Requirement::create([
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
        $requirement = Requirement::find($id);
        if(! $requirement){
            return $this->respondNotFound('Record does not exist');

        }
        return Response::json([
            'data' => $this->transform($requirement->toArray())
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
        $requirement = Requirement::find($id); 

        if(! $requirement){
            return $this->respondNotFound();
        }
        $input = $request->all();
        $requirement->fill($input)->save();

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

        $requirement = Requirement::find($id);
        if(! $requirement){
            return $this->respondNotFound();
        } 
        $requirement->delete();
        return $this->respondSuccess();
    }

    private function transform($requirement)
    {
        return [
            'name' => $requirement['name'],
            'description' => $requirement['description']
        ];

    }
    private function transformWithPaginate($requirements)
    {
        
        $itemsTransformed = $requirements
            ->getCollection()
            ->map(function($requirement) {

                return [
                    'name' => $requirement['name'],
                    'description' => $requirement['description']
                ];
        })->toArray();

        return $itemsTransformed;
    }
}
