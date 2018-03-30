<?php

namespace App\Storage\NotificationSetting\NotificationNews;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\NotificationSetting\NotificationNews\NotificationNewsRepository;
use App\Storage\NotificationSetting\NotificationNews\NotificationNews;
use App\Storage\NotificationSetting\NotificationNews\NotificationNewsValidator;
use App\Storage\NotificationSetting\NotificationNews\NotificationNewsPresenter;


/**
 * Class BrandRepositoryEloquent
 * @package namespace App\Storage\NotificationSetting;
 */
class NotificationNewsRepositoryEloquent extends BaseRepository implements NotificationNewsRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return NotificationNews::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return NotificationNewsValidator::class;
    }

    public function presenter()
    {

        return NotificationNewsPresenter::class;
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
    public function createNews($data){

        // $notification_type      = (isset($data['notification_type']) ? $data['notification_type'] : '1' );
        $customer_id            = (isset($data['customer_id']) ? $data['customer_id'] : 0);
        $status                 = (isset($data['status']) ? $data['status'] : 1);
        $insert                 =[
            // 'notification_type'=>$notification_type,
            'customer_id' =>$customer_id,
            'status'      => $status
        ];
// dd($insert);
        $notification=$this->model->create($insert);
        return $notification;
    }

 public function updateNews($notification, $data)
    {
        $notification_type      = (isset($data['notification_type']) ? $data['notification_type'] : '1' );
        $customer_id            = (isset($data['customer_id']) ? $data['customer_id'] : 0);
        $status                 = (isset($data['status']) ? $data['status'] : 1);
        // $notification           = $this->model->find([
        //     // 'notification_type' =>$notification_type,
        //     'customer_id'       =>$customer_id,
        //     'status'            => $status
        // ], $notification->id);
        $notification->status=$status;
        $notification->save();
        return $notification;
    }

    public function isNews($data,$type){
        $customer_id            = (isset($data['customer_id']) ? $data['customer_id'] : 0);
        $status                 = (isset($data['status']) ? $data['status'] : 1);
        $notification           = $this->model->where('customer_id','=',$customer_id)->first();
       return $notification;
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