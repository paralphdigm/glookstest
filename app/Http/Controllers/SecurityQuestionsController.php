<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\User;
use App\SecurityQuestion;
use DB;
use App\Http\Requests;

class SecurityQuestionsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = Input::get('limit') ?: 3;
        $securityquestions = SecurityQuestion::paginate($limit);

        if( ! $securityquestions->count() > 0)
        {
            return $this->respondNoRecord();
        }

        return $this->respondWithPaginator($securityquestions,[
            'data' => $this->transformWithPaginate($securityquestions)
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
        if(! $request['question'] or ! $request['description'])
        {
            return $this->respondInvalid();
        }
        $data = $request->all();
        SecurityQuestion::create([
                'question' => $data['question'],
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
        $securityquestion = SecurityQuestion::find($id);
        if(! $securityquestion){
            return $this->respondNotFound('Record does not exist');

        }
        return Response::json([
            'data' => $this->transform($securityquestion->toArray())
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
        $securityquestion = SecurityQuestion::find($id); 
        if(! $securityquestion){
            return $this->respondNotFound();
        }
        $input = $request->all();
        $securityquestion->fill($input)->save();
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
        $securityquestion = SecurityQuestion::find($id); 
        if(! $securityquestion){
            return $this->respondNotFound();
        }
        $securityquestion->delete();
        return $this->respondSuccess();
    }

    public function transform($securityquestion)
    {
        return [
            'question' => $securityquestion['question'],
            'description' => $securityquestion['description']
        ];

    }
    private function transformWithPaginate($securityquestions)
    {
        
        $itemsTransformed = $securityquestions
            ->getCollection()
            ->map(function($securityquestion) {
                return [
                    'question' => $securityquestion['question'],
                    'description' => $securityquestion['description']
                ];
        })->toArray();

        return $itemsTransformed;
    }
}
