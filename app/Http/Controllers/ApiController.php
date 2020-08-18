<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{
    protected $statusCode;

    public function getStatusCode()
    {
        return $this->statusCode;
    }
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }
    // helpers
    public function respondNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }
    public function respondNoRecord($message = 'No record')
    {
        return $this->setStatusCode(404)->respondWhenSuccess($message);
    }
    public function respondMethodNotAllowed($message = 'Method not allowed')
    {
        return $this->setStatusCode(405)->respondWithError($message);
    }
    public function respondSuccess($message = 'Record has been updated')
    {
        return $this->setStatusCode(200)->respondWhenSuccess($message);
    }
    public function respondAccepted($message = 'Record has been created')
    {
        return $this->setStatusCode(201)->respondWhenSuccess($message);
    }
    public function respondNoContent($message = 'No content')
    {
        return $this->setStatusCode(204)->respondWhenSuccess($message);
    }
    public function respondUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }
    public function respondInvalid($message = 'Invalid Parameters')
    {
        return $this->setStatusCode(422)->respondWithError($message);
    }



    public function respond($data, $headers = [])
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }
    public function respondWithError($message){

        return $this->respond([

            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }
    public function respondWhenSuccess($message){

        return $this->respond([

            'status' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }
}
