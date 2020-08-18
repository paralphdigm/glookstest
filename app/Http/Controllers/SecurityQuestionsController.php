<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
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
        $securityquestions = SecurityQuestion::all();

        if( ! $securityquestions->count() > 0)
        {
            return $this->respondNoRecord();
        }

        return Response::json([

            'data' => $this->transformCollection($securityquestions)

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
    private function transformCollection($securityquestions)
    {
        return array_map([$this, 'transform'], $securityquestions->toArray());
    }

    private function transform($securityquestion)
    {
        return [
            'question' => $securityquestion['question'],
            'description' => $securityquestion['description']
        ];

    }
}
