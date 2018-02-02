<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
use App\Storage\Offer\Offer;

//use Request;

class OffersPutRequest extends Request
{

    protected $redirectRoute = 'api.errors';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $wpIdRules = 'required_without:offer_id';
        $wpId = $this->get('wpid');
        $offerId = $this->get('offer_id');
        $offer = null;

        if($wpId && !$offerId)
        {
            /**
             * Look for offer by wpid.
             *
             * @var        Offer
             */
            $offer = Offer::where('wpid', $wpId)->first();
            $wpIdRules .= ($offer  ? '|unique:offers,id,' . $offer->id : '|exists:offers,wpid' );

        }elseif($offerId && !$wpId)
        {
            /**
             * When offer_id is set look for offer using offer ID.
             *
             * @var        <type>
             */
            $offer = Offer::find($offerId);
          //  $wpIdRules .= ($offer  ? '|unique:offers,id,' . $offer->id : '' );
        }elseif($offerId && $wpId)
        {

            $offer = Offer::find($offerId);

            if($offer && $offer->wpid)
            {
                $wpIdRules .= '|unique:offers,wpid,' . $offer->wpid;   
            }else{

                $wpIdRules .= '|unique:offers,wpid';
            }

        }

        $this->attributes->add(['offer' => $offer]);

        return [
            'access_token'  => 'required',
            'contents'      => '',
            'thumbnail_url' => 'url',
            'media'         => '',
            'status'        => 'in:publish,draft,pending',
            'title'         => '',
            'wpid'          => $wpIdRules,
            'brand_id'      => 'exists:brands,id',
            'offer_id'      => 'exists:offers,id'
        ];
    }
    
}