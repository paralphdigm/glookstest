<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
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
        $limit = Input::get('limit') ?: 3;
        $articletypes = ArticleType::paginate($limit);

        if(! $articletypes->count() > 0)
        {
            return $this->respondNoRecord();
        }
        return $this->respondWithPaginator($articletypes,[
            'data' => $this->transformWithPaginate($articletypes)
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

    public function transform($articletype)
    {
        return [
            'name' => $articletype['name'],
            'description' => $articletype['description']
        ];

    }
    private function transformWithPaginate($articletypes)
    {
        
        $itemsTransformed = $articletypes
            ->getCollection()
            ->map(function($articletype) {
                return [
                    'name' => $articletype['name'],
                    'description' => $articletype['description']
                ];
        })->toArray();

        return $itemsTransformed;
    }
}
