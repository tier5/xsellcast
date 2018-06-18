<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;

class CTARequestPostRequest extends Request {
    protected $redirectRoute = 'api.errors';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $type       = $this->type;
        $brand_rule = '';
        $offer_rule = '';
        if (in_array($type, [1, 2, 3, 4])) {
            $offer_rule = 'required_without:wp_brand_id|integer|exists:offers,wpid';
            $brand_rule = 'required_without:wp_offer_id|integer|exists:brands,wp_brand_id';
        }
        if (in_array($type, [5, 6, 7])) {
            $offer_rule = 'required|integer|exists:offers,wpid';
        }

        return [
            'access_token'   => 'required',
            'type'           => 'required|integer|in:1,2,3,4,5,6,7',
            'wp_customer_id' => 'required|integer|exists:user_customer,wp_userid',
            'wp_offer_id'    => $offer_rule,
            'wp_brand_id'    => $brand_rule,
            'body'           => 'required_if:type,==,5',
            'phone_number'   => 'required_if:type,==,4|numeric',
        ];
    }

    /**
     * Response error message as json
     *
     * @param array $errors
     * @return mixed
     */
    public function response(array $errors) {

        return response()->json([
            'status'  => false,
            'code'    => config('responses.bad_request.status_code'),
            'data'    => null,
            'errors'  => $errors,
            'message' => config('responses.bad_request.status_message'),
        ],
            config('responses.bad_request.status_code')
        );
    }
}