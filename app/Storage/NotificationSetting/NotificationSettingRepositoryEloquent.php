<?php

namespace App\Storage\NotificationSetting;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\NotificationSetting\BrandRepository;
use App\Storage\NotificationSetting\NotificationSetting;
use App\Storage\NotificationSetting\NotificationSettingValidator;
use App\Storage\NotificationSetting\NotificationSettingPresenter;


/**
 * Class BrandRepositoryEloquent
 * @package namespace App\Storage\NotificationSetting;
 */
class NotificationSettingRepositoryEloquent extends BaseRepository implements NotificationSettingRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return NotificationSetting::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return NotificationSettingValidator::class;
    }

    public function presenter()
    {

        return NotificationSettingPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Gets the by customer_id.
     *
     * @param      integer  $customer_id  The customer identifier
     *
     * @return     $this  The by customer.
     */
    public function getByCustomer($customer_id)
    {

        $model = $this->model
            ->whereHas('customer', function($query) use($customer_id){
                $query->where('customer_id', $customer_id);
            });

        $this->model = $model;

        return $this;
    }
    public function createNews($data){
        $notification_type   = (isset($data['notification_type']) ? $data['notification_type'] : '1' );
        $customer_id   = (isset($data['customer_id']) ? $data['customer_id'] : 0);
        $status   = (isset($data['status']) ? $data['status'] : 1);
        $insert=['notification_type'=>$notification_type,'customer_id'=>$customer_id,'status'=> $status];

        $this->skipPresenter()->create($insert);
        return $this;
    }



    public function customerBrands($customer_id)
    {
        // $this->model = $this->model->whereHas('dealers', function($query) use($customer_id){
        //     $query->whereHas('salesReps', function($query) use($customer_id){
        //         $query->whereHas('customersPivot', function($query) use($customer_id){
        //             $query->where('customer_id', $customer_id);
        //         });
        //     });
        // });

        // return $this;
    }


}