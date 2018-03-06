<?php

namespace App\Storage\NotificationSetting\NotificationBrandAssociates;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\NotificationSetting\NotificationBrandAssociates\NotificationBrandAssociatesRepository;
use App\Storage\NotificationSetting\NotificationBrandAssociates\NotificationBrandAssociates;
use App\Storage\NotificationSetting\NotificationBrandAssociates\NotificationBrandAssociatesValidator;
use App\Storage\NotificationSetting\NotificationBrandAssociates\NotificationBrandAssociatesPresenter;


/**
 * Class BrandRepositoryEloquent
 * @package namespace App\Storage\NotificationSetting;
 */
class NotificationBrandAssociatesRepositoryEloquent extends BaseRepository implements NotificationBrandAssociatesRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return NotificationBrandAssociates::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return NotificationBrandAssociatesValidator::class;
    }

    public function presenter()
    {

        return NotificationBrandAssociatesPresenter::class;
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

        $this->modelNews = $model;

        return $this;
    }
    public function createBrand($data){


        $customer_id            = (isset($data['customer_id']) ? $data['customer_id'] : 0);
        $brand_ids              = (isset($data['brand_ids']) ? $data['brand_ids'] : []);
        $status                 = (isset($data['status']) ? $data['status'] : 1);
        $notification=[];
        foreach ($brand_ids as $brand_id) {
            // $model=$this->model->where('customer_id','=',$customer_id)->where('brand_id','=',$brand_id)->updateOrCreate();
            $formdata                 =[
                    'customer_id'=>$customer_id,
                    'brand_id'=>$brand_id,
                    'status'=> $status];
             $brandobj= $this->isBrand($formdata);
            if(empty($brandobj)){
            $notification[]=$this->skipPresenter()->create($formdata);
            }else{
            $notification[]=$this->skipPresenter()->update($formdata,$brandobj->id);
            }
            // ->updateOrCreate($insert);
        }

        return $notification;
    }

    // public function update($notification, $data)
    // {
    //     $notification_type      = (isset($data['notification_type']) ? $data['notification_type'] : '1' );
    //     $customer_id            = (isset($data['customer_id']) ? $data['customer_id'] : 0);
    //     $status                 = (isset($data['status']) ? $data['status'] : 1);
    //     $notification                 = $this->skipPresenter()->update([
    //         // 'notification_type' =>$notification_type,
    //         'customer_id'       =>$customer_id,
    //         'status'            => $status], $notification->id);

    //     return $notification;
    // }

    public function isBrand($data){
        $customer_id            = (isset($data['customer_id']) ? $data['customer_id'] : 0);
        $brand_id            = (isset($data['brand_id']) ? $data['brand_id'] : 0);
        $notification           = $this->model->where('customer_id','=',$customer_id)->where('brand_id','=',$brand_id)->first();
       return $notification;
    }
    public function customerBrands($customer_id)
    {
        $this->model = $this->model->whereHas('dealers', function($query) use($customer_id){
            $query->whereHas('salesReps', function($query) use($customer_id){
                $query->whereHas('customersPivot', function($query) use($customer_id){
                    $query->where('customer_id', $customer_id);
                });
            });
        });

        return $this;
    }


}