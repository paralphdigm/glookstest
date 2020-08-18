<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\User;
use App\SecurityQuestion;
use App\UserSecurityQuestion;
use DB;
use App\Http\Requests;

class UserSecurityQuestionsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usersecurityquestions = UserSecurityQuestion::all();

        if( ! $usersecurityquestions->count() > 0)
        {
            return $this->respondNoRecord();
        }

        return Response::json([

            'data' => $this->transformCollection($usersecurityquestions)

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
        if(! $request['question_id'] or ! $request['user_id'] or ! $request['answer'])
        {
            return $this->respondInvalid();
        }
        $usersecurityquestion = new UserSecurityQuestion();
        $usersecurityquestion->question_id = $request->input('question_id');
        $usersecurityquestion->user_id = $request->input('user_id');
        $usersecurityquestion->answer = $request->input('answer');
        $usersecurityquestion->save();

        
        // foreach ($request->input('permission') as $key => $value) {
        //     $role->attachPermission($value);
        // }
        
        return $this->respondAccepted();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showUserQuestionAnswers($id)
    {
        $usersecurityquestions = UserSecurityQuestion::where('user_id', $id)->get();

        return response()->json($usersecurityquestions);

        return 201;
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
        $usersecurityquestion = UserSecurityQuestion::where('user_id', $id)->get();
        $input = $request->all();
        $usersecurityquestion->fill($input)->save();
        return 200;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
