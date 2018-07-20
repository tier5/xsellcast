<?php

namespace App\Storage\Dealer;

use App\Storage\Brand\Brand;
use App\Storage\CityState\CityState;
use App\Storage\Customer\CustomerRepositoryEloquent;
use App\Storage\Dealer\Dealer;
use App\Storage\Dealer\DealerPresenter;
use App\Storage\Dealer\DealerRepository;
use App\Storage\Dealer\DealerValidator;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class DealerRepositoryEloquent
 * @package namespace App\Storage\Dealer;
 */
class DealerRepositoryEloquent extends BaseRepository implements DealerRepository {
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model() {
        return Dealer::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator() {

        return DealerValidator::class;
    }

    public function presenter() {

        return DealerPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot() {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function filter($where) {

        $this->model = $this->model->where($where);

        return $this;
    }

    public function withCategoryId($category_id) {
        $this->model = $this->model->with(['brands.categories' => function ($q) use ($category_id) {
            $q->where('categories.id', $category_id);
        }]);

        $this->model->whereHas('brands', function ($q) use ($category_id) {
            $q->whereHas('categories', function ($q) use ($category_id) {
                $q->where('categories.id', $category_id);
            });
        });

        return $this;
    }

    public function createOne($data) {

        $hoursOfOperation = ($data['hours_of_operation'] && is_array($data['hours_of_operation']) ? serialize($data['hours_of_operation']) : '');
        $brand            = ($data['brand'] ? Brand::find($data['brand']) : null);
        $dealerData       = [
            'wpid'               => $data['wpid'],
            'name'               => $data['name'],
            'address1'           => $data['address1'],
            'address2'           => $data['address2'],
            'city'               => $data['city'],
            'county'             => $data['county'],
            'state'              => $data['state'],
            'country'            => $data['country'],
            'phone'              => $data['phone'],
            'fax'                => $data['fax'],
            'website'            => $data['website'],
            'zip'                => $data['zip'],
            'email'              => $data['email'],
            'description'        => $data['description'],
            'hours_of_operation' => $hoursOfOperation,
            'logo_media_id'      => $data['logo_media_id'],
            'outlet'             => $data['outlet'],
            'distributor_name'   => $data['distributor_name'],
            'rep_name'           => $data['rep_name'],
            'rep_email'          => $data['rep_email'],

        ];
        $cityState = CityState::zipLook($data['zip'])->first();
        // dd($cityState);
        if ($cityState) {
            $dealerData['geo_lat']  = $cityState->geo_lat;
            $dealerData['geo_long'] = $cityState->geo_long;
            $dealerData['state']    = $cityState->state;
            $dealerData['city']     = $cityState->city;
        }

        $dealer = $this->skipPresenter()->create($dealerData);

        if ($brand) {
            $dealer->brands()->save($brand);
        }

        return $dealer;
    }

    public function updateOne($dealer, $data) {
        $hoursOfOperation = ($data['hours_of_operation'] && is_array($data['hours_of_operation']) ? serialize($data['hours_of_operation']) : '');
        $brand            = ($data['brand'] ? Brand::find($data['brand']) : null);

        $dealerData = [
            'wpid'               => $data['wpid'],
            'name'               => $data['name'],
            'address1'           => $data['address1'],
            'address2'           => $data['address2'],
            'city'               => $data['city'],
            'county'             => $data['county'],
            'state'              => $data['state'],
            'country'            => $data['country'],
            'phone'              => $data['phone'],
            'fax'                => $data['fax'],
            'website'            => $data['website'],
            'zip'                => $data['zip'],
            'email'              => $data['email'],
            'description'        => $data['description'],
            'hours_of_operation' => $hoursOfOperation,
            'logo_media_id'      => $data['logo_media_id'],
            'outlet'             => $data['outlet'],
            'distributor_name'   => $data['distributor_name'],
            'rep_name'           => $data['rep_name'],
            'rep_email'          => $data['rep_email'],

        ];

        $cityState = CityState::zipLook($data['zip'])->first();
        // dd($cityState);
        if ($cityState) {
            $dealerData['geo_lat']  = $cityState->geo_lat;
            $dealerData['geo_long'] = $cityState->geo_long;
            $dealerData['state']    = $cityState->state;
            $dealerData['city']     = $cityState->city;
        }

        $dealer = $this->skipPresenter()->update($dealerData, $dealer->id);
        $dealer->brands()->detach();
        $dealer->save();

        if ($brand) {
            $dealer->brands()->save($brand);
        }

        return $dealer;
    }

    public function whereInZips($zips) {
        $this->model = $this->model->whereIn('zip', $zips);

        return $this;
    }
    public function whereBrandInZips($zips, $brand_id) {

        $this->model = $this->model->leftJoin('dealer_brands', 'dealers.id', '=', 'dealer_brands.dealer_id')
            ->whereIn('zip', $zips)
            ->where('dealer_brands.brand_id', $brand_id)
        ;

        return $this;
    }

    public function orderByName($order = 'desc') {
        $this->model = $this->model->orderBy('name', $order);

        return $this;
    }

    public function orderByBrand($order = 'desc') {
        $this->model = $this->model->leftJoin('dealer_brands', 'dealers.id', '=', 'dealer_brands.dealer_id')
            ->leftJoin('brands', 'dealer_brands.brand_id', '=', 'brands.id')
            ->orderBy('brands.name', $order)
            ->select('dealers.*');

        return $this;
    }

    public function orderByCity($order = 'desc') {
        $this->model = $this->model->orderBy('city', $order);

        return $this;
    }

    public function orderByZip($order = 'desc') {
        $this->model = $this->model->orderBy('zip', $order);

        return $this;
    }

    public function orderByState($order = 'desc') {
        $this->model = $this->model->orderBy('state', $order);

        return $this;
    }

    public function scopeInBrand($brand) {
        return $this->scopeQuery(function ($q) use ($brand) {

            return $q->whereHas('brands', function ($q) use ($brand) {

                $q->where('brands.id', $brand->id);
            });
        });
    }

    public function nearestInBrandCustomer($brand, $customer) {
        $customerRepo  = new CustomerRepositoryEloquent(app());
        $dealers       = $this->skipPresenter()->scopeInBrand($brand)->all();
        $nearestDealer = null;

        foreach ($dealers as $dealer) {
            /**
             * Calculate distance
             *
             * @var float
             */

            $distance = $customerRepo->distance($customer->geo_lat, $customer->geo_long, $dealer->geo_lat, $dealer->geo_long);

            if ($nearestDealer) {

                $nearestSoFar = $customerRepo->distance($customer->geo_lat, $customer->geo_long, $nearestDealer->geo_lat, $nearestDealer->geo_long);

                if ($nearestSoFar >= $distance) {
                    $nearestDealer = $dealer;
                }
            } else {

                $nearestDealer = $dealer;
            }
        }

        return collect([$nearestDealer]);
    }

}
