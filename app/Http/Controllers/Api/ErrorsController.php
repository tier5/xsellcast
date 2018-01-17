<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Response;

/**
 * @resource Error
 *
 * General error return of the API.
 */
class ErrorsController extends Controller
{

    public function index(Request $request)
    {
        $errors = Session::get('errors');
        $errorArr = [];

        if($errors){
            foreach($errors->getBags() as $bag)
            {
                foreach($bag->getMessages() as $field => $msgs)
                {
                    foreach($msgs as $msg)
                    {
                        $errorArr[] = $msg;
                    }
                }
            }            
        }

        if(empty($errorArr))
        {
            $errorArr[] = 'Missing error!';
        }

        return Response::json(['data' => ['errors' => $errorArr]], 422);
    }

}