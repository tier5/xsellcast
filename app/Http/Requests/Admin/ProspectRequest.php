<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

/**
 * This request is for App\Http\Controllers\Admin\ProspectsController
 */
class ProspectRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $saleRep = $user->salesRep;
        $routeName = $this->route()->getName();
        
        $this->attributes->add(['user' => $user, 'salesrep' => $saleRep]);

        if($user->hasRole('csr'))
        {
            return true;
        }

        /**
         * Only sales rep have access.
         */
        if(!$saleRep){ 
            return false;
        }

        if($routeName == 'admin.prospects.show'){ // Single 

            return $saleRep->hasCustomer($this->route('customer_id'))->first();
        }elseif($routeName == 'admin.prospects.post.note'){//Send note

            return $saleRep->hasCustomer($this->route('customer_id'))->first();
        }elseif($routeName == 'admin.prospects'){ // Listing

            return $saleRep;
        }else{

            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }    
}
