<?php
namespace App\Storage\LbtWp;

class WpConvetor {

    /**
     * Single
     *
     * Get a brand by ID.
     *
     * @param      string   module type
     * @param      Integer  $wp_id  The brand identifier
     *
     * @return     Response model id
     */
    public function getId($type, $wp_id) {
        $id = null;
        switch ($type) {
        case 'brand':
            $brand = \App\Storage\Brand\Brand::where('wp_brand_id', $wp_id)->first();
            if (!empty($brand)) {
                $id = $brand->id;
            }

            break;
        case 'category':
            $category = \App\Storage\Category\Category::where('wp_category_id', $wp_id)->first();
            if (!empty($category)) {
                $id = $category->id;
            }
            break;
        case 'customer':
            $customer = \App\Storage\Customer\Customer::where('wp_userid', $wp_id)->first();
            if (!empty($customer)) {
                $id = $customer->id;
            }
            break;
        case 'dealer':
            $dealer = \App\Storage\Dealer\Dealer::where('wpid', $wp_id)->first();
            if (!empty($dealer)) {
                $id = $dealer->id;
            }

            break;
        case 'offer':
            $offer = \App\Storage\Offer\Offer::where('wpid', $wp_id)->first();
            if (!empty($offer)) {
                $id = $offer->id;
            }
            break;

        default:
            // code...
            break;
        }
        return $id;
    }

}

?>