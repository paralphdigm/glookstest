<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Connection;
use DB;
use App\Http\Requests;

class ConnectionsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $connections = Connection::all();
        if(! $connections->count() > 0)
        {
            return $this->respondNoRecord();
        }

        return Response::json([

            'data' => $this->transformCollection($connections)

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
        if(! $request['name'] or ! $request['description'])
        {
            return $this->respondInvalid();
        }
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string'
        ]);
        
        Connection::create([
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
        $connection = Connection::find($id);
        if(! $connection){
            return $this->respondNotFound('Record does not exist');

        }
        return Response::json([
            'data' => $this->transform($connection->toArray())
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
        $connection = Connection::find($id); 
        if(! $connection){
            return $this->respondNotFound();
        }
        $input = $request->all();
        $connection->fill($input)->save();
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
        $connection = Connection::find($id);
        if(! $connection){
            return $this->respondNotFound();
        } 
        $connection->delete();
        return $this->respondSuccess();
    }
    private function transformCollection($connections)
    {
        return array_map([$this, 'transform'], $connections->toArray());
    }

    private function transform($connection)
    {
        return [
            'name' => $connection['name'],
            'description' => $connection['description']
        ];

    }
}
