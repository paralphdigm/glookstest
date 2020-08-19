<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\User;
use App\SecurityQuestion;
use App\SecurityQuestionUser;
use DB;
use App\Http\Requests;

class SecurityQuestionUsersController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = Input::get('limit') ?: 3;
        $usersecurityquestions = SecurityQuestionUser::paginate($limit);

        if( ! $usersecurityquestions->count() > 0)
        {
            return $this->respondNoRecord();
        }
        return $this->respondWithPaginator($usersecurityquestions,[
            'data' => $this->transformWithPaginate($usersecurityquestions)
        ]);
    
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        if(! $request['security_question_id'] or ! $request['user_id'] or ! $request['answer'])
        {
            return $this->respondInvalid();
        }
        $usersecurityquestion = new SecurityQuestionUser();
        $usersecurityquestion->user_id = $request->input('user_id');
        $usersecurityquestion->security_question_id = $request->input('security_question_id');
        $usersecurityquestion->answer = $request->input('answer');
        $usersecurityquestion->save();

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
        //
    }
    public function showQuestionAnswerUsers($id = null)
    {
        $usersecurityquestions = $id ? User::find($id)->security_question_users : SecurityQuestion::all();
        
        return $this->respondWithPaginator($usersecurityquestions,[
            'data' => $this->transformWithPaginate($usersecurityquestions)
        ]);
        // return response()->json([
        //     'data' => $this->transformCollection($usersecurityquestions),
        // ]);
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
        $policy = Policy::find($id);

        if(! $policy){
            return $this->respondNotFound();
        } 
        $input = $request->all();
        $policy->fill($input)->save();

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
        //
    }
    public function transform($usersecurityquestion)
    {
        $securityquestionid = $usersecurityquestion['security_question_id'];
        $securityquestion = SecurityQuestion::find($securityquestionid);
            return [
                
                'question' => $securityquestion['question'],
                'answer' => $usersecurityquestion['answer'],
            ];

    }
    public function transformCollection($data)
    {
        return array_map([$this, 'transform'], $data->toArray());
    }
    private function transformWithPaginate($usersecurityquestions)
    {
        
        $itemsTransformed = $usersecurityquestions
            ->getCollection()
            ->map(function($usersecurityquestion) {
                $question = $usersecurityquestion['security_question_id'];
                $questionid = SecurityQuestion::find($question);

                $user = $usersecurityquestion['user_id'];
                $userid = User::find($user);
                return [
                    'user' => $userid['membership_number'],
                    'question' => $usersecurityquestion['answer'],
                    'answer' => $usersecurityquestion['answer']
                ];
        })->toArray();

        return $itemsTransformed;
    }
}
