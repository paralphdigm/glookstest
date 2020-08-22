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
        $limit = Input::get('limit') ?: 20;
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
        
        foreach ($request->security_questions as $key => $value) {

            $securityquestionid = $value['security_question_id'];
            $user = $value['user_id'];
            $answer = $value['answer'];
            
            $checker = SecurityQuestionUser::where([
                ['user_id', $user],
                ['security_question_id', $securityquestionid],
                ['answer', $answer],
            ])->get();

            if(! $checker->count() > 0){
                SecurityQuestionUser::create([
                    'user_id' => $value['user_id'],
                    'security_question_id' => $value['security_question_id'],
                    'answer' => $value['answer'],
                ])->save();
                
                return $this->respondAccepted();
            }
            return $this->respondInvalid('Duplicate Entry');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showQuestionAnswerUsers($id = null)
    {
        $limit = Input::get('limit') ?: 20;
        $usersecurityquestions = $id ? User::find($id)->security_question_users : SecurityQuestion::paginate($limit);
 
        return response()->json([
            'data' => $this->transformCollection($usersecurityquestions),
        ]);
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

        DB::table("security_question_users")->where("security_question_users.user_id",$id)
            ->delete();
        
        foreach ($request->security_questions as $key => $value) {
            SecurityQuestionUser::create([
                'user_id' => $value['user_id'],
                'security_question_id' => $value['security_question_id'],
                'answer' => $value['answer'],
            ])->save();
        }
        return $this->respondSuccess();
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
