<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\ArticleType;
use DB;
use App\Http\Requests;

class ArticleTypesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articletypes = ArticleType::all();

        if(! $articletypes->count() > 0)
        {
            return $this->respondNoRecord();
        }
        return Response::json([

            'data' => $this->transformCollection($articletypes)

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
        ArticleType::create([
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
        $articletype = ArticleType::find($id);
        if(! $articletype){
            return $this->respondNotFound('Record does not exist');
        }
        return Response::json([
            'data' => $this->transform($articletype->toArray())
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
        $articletype = ArticleType::find($id); 
        if(! $articletype){
            return $this->respondNotFound();
        }
        $input = $request->all();
        $articletype->fill($input)->save();
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
        $articletype = ArticleType::find($id);
        if(! $articletype){
            return $this->respondNotFound();
        } 
        $articletype->delete();
        return $this->respondSuccess();
    }
    private function transformCollection($articletypes)
    {
        return array_map([$this, 'transform'], $articletypes->toArray());
    }

    private function transform($articletype)
    {
        return [
            'name' => $articletype['name'],
            'description' => $articletype['description']
        ];

    }
}
