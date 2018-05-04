<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
use App\Storage\Offer\Offer;
use App\Storage\LbtWp\WpConvetor;


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
        // $wpIdRules = 'required_without:offer_id';
        // $wpId = $this->get('wpid');
        // $offerId = $this->get('offer_id');
        // $offer = null;

        // if($wpId && !$offerId)
        // {
        //     /**
        //      * Look for offer by wpid.
        //      *
        //      * @var        Offer
        //      */
        //     $offer = Offer::where('wpid', $wpId)->first();
        //     $wpIdRules .= ($offer  ? '|unique:offers,id,' . $offer->id : '|exists:offers,wpid' );

        // }elseif($offerId && !$wpId)
        // {
        //     *
        //      * When offer_id is set look for offer using offer ID.
        //      *
        //      * @var        <type>

        //     $offer = Offer::find($offerId);
        //   //  $wpIdRules .= ($offer  ? '|unique:offers,id,' . $offer->id : '' );
        // }elseif($offerId && $wpId)
        // {

        //     $offer = Offer::find($offerId);

        //     if($offer && $offer->wpid)
        //     {
        //         $wpIdRules .= '|unique:offers,wpid,' . $offer->wpid;
        //     }else{

        //         $wpIdRules .= '|unique:offers,wpid';
        //     }

        // }

        // $this->attributes->add(['offer' => $offer]);

        $wp_offer_id=$this->wp_offer_id;
        $wp=new WpConvetor();

        $offer_id=$wp->getId('offer',$wp_offer_id);
        // $customerId = $this->get('customer_id');

        $offer   = Offer::find($offer_id);
        $wpIdRules  = '|unique:offers,wpid';
        // $emailRules = 'unique:users,email';

        if($offer)
        {
            $wpIdRules .= ',' . $offer->id;

        }else{
            return [
            'access_token'   => 'required',
            'wp_offer_id'    => 'bail|required|integer|exists:offers,wpid'
            ];
        }


        return [
            'access_token'      => 'required',
            'wp_offer_id'       => 'required|integer'.$wpIdRules,//'required|integer|unique:offers,wpid',
            'title'             => isset($this->title)?'required':'',
            'wp_brand_id'       => isset($this->wp_brand_id)?'required|integer|exists:brands,wp_brand_id':'',
            'contents'          => isset($this->contents)?'required':'',
            //'thumbnail'     => 'image',
            'media'             => 'url',
            'wp_offer_link'     => 'url',
            'status'            => isset($this->contents) ? 'required|in:publish,draft,pending':'',
        ];
    }

    /**
     * Response error message as json
     *
     * @param array $errors
     * @return mixed
     */
    public function response(array $errors){

        return response()->json([
                    'status'=>false,
                    'code'=>config('responses.bad_request.status_code'),
                    'data'=>null,
                    'errors'=>$errors,
                    'message'=>config('responses.bad_request.status_message'),
                ],
                config('responses.bad_request.status_code')
            );
        // return Response::json($errors, config('responses.bad_request.status_code'));
    }

}