<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\UserAddress;
use App\User;
use DB;
use App\Http\Requests;
class UserAddressesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = Input::get('limit') ?: 20;
        $useraddresses = SecurityQuestionUser::paginate($limit);

        if( ! $useraddresses->count() > 0)
        {
            return $this->respondNoRecord();
        }
        return $this->respondWithPaginator($useraddresses,[
            'data' => $this->transformWithPaginate($useraddresses)
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
        if(! $request['user_id'] or ! $request['address_line_1'] or ! $request['city'] or ! $request['post_code']
        or ! $request['country'])
        {
            return $this->respondInvalid();
        }
        $data = $request->all();
        $user = User::where('id',$data['user_id'])->get();
      
        if(! $user->count() > 0){
            return $this->respondInvalid('User ID is does not exist');
        }
        UserAddress::create([
            'user_id' => $data['user_id'],
            'house_number' => $data['house_number'],
            'address_line_1' => $data['address_line_1'],
            'address_line_2' => $data['address_line_2'],
            'city' => $data['city'],
            'post_code' => $data['post_code'],
            'country' => $data['country'],
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
        $useraddress = UserAddress::find($id);
        if(! $useraddress){
            return $this->respondNotFound('Record does not exist');

        }
        return Response::json([
            'data' => $this->transform($useraddress->toArray())
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showUserAddresses($id)
    {
        
        $user = User::where('id',$id)->get();
        if(! $user->count() > 0){
            
            return $this->respondInvalid('User ID is does not exist');
           
        }
        
        $useraddresses = UserAddress::where('user_id',$id)->get();
        foreach($useraddresses as $useraddress){
            return Response::json([
                'data' => $this->transform($useraddress->toArray())
            ],200);
        }
    
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
        
        $useraddress = UserAddress::find($id); 
        if(! $useraddress){
            return $this->respondNotFound();
        }
        $input = $request->all();
        $user = User::where('id',$input['user_id'])->get();

        if(! $user->count() > 0){
            return $this->respondInvalid('User is does not exist');
        }
        $useraddress->fill($input)->save();
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
        $useraddress = UserAddress::find($id);
        if(! $useraddress){
            return $this->respondNotFound();
        } 
        $useraddress->delete();
        return $this->respondSuccess();
    }

    public function transform($useraddress)
    {
        return [
            'house_number' => $useraddress['house_number'],
            'address_line_1' => $useraddress['address_line_1'],
            'address_line_2' => $useraddress['address_line_2'],
            'city' => $useraddress['city'],
            'post_code' => $useraddress['post_code'],
            'country' => $useraddress['country'],
        ];

    }
    private function transformWithPaginate($useraddresses)
    {
        
        $itemsTransformed = $useraddresses
            ->getCollection()
            ->map(function($useraddress) {
                return [
                    'house_number' => $useraddress['house_number'],
                    'address_line_1' => $useraddress['address_line_1'],
                    'address_line_2' => $useraddress['address_line_2'],
                    'city' => $useraddress['city'],
                    'post_code' => $useraddress['post_code'],
                    'country' => $useraddress['country'],
                ];
        })->toArray();

        return $itemsTransformed;
    }
}
