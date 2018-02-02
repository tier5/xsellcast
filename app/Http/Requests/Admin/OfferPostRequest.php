<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Storage\Offer\Offer;

/**
 * This request is for App\Http\Controllers\Admin\OfferController
 */
class OfferPostRequest extends Request
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
        $offer = Offer::find($this->route('offer_id'));
        
        if(!$this->route('offer_id'))
        {
            return true;
        }
        //$this->attributes->add(['salesrep' => $saleRep]);

        if($offer->author_type != 'custom' && $user->hasRole('csr'))
        {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'         => 'required',
            'contents'      => 'required',
            'media'         => 'required|is_media_image',
            'tags'          => 'comma_max:2',
            'brand'         => 'required|not_in:0'
        ];
    }

    public function messages()
    {
        return [
            'title.required'        => 'Oops, an offer title is required.',
            'tags.comma_max'        => 'Oops, 2 is the maximum hashtag.',
            'contents.required'     => 'Oops, body text is required.',
            'media.is_media_image'  => 'Oops, you must associate an image with this offer',
            'media.required'        => 'Oops, an offer media is required.',
            'brand.required'        => 'Oops, an brand is required.',
            'brand.not_in'        => 'Oops, an brand is required.',
        ];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }    
}
