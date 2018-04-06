<?php

namespace App\Storage\Appointment;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\Appointment\AppointmentRepository;
use App\Storage\Appointment\Appointment;
use App\Storage\Appointment\AppointmentValidator;
use App\Storage\Appointment\AppointmentPresenter;
use App\Storage\Category\Category;

/**
 * Class AppointmentRepositoryEloquent
 * @package namespace App\Storage\Appointment;
 */
class AppointmentRepositoryEloquent extends BaseRepository implements AppointmentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Appointment::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return AppointmentValidator::class;
    }

    public function presenter()
    {

        return AppointmentPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Gets the by dealer.
     *
     * @param      integer  $dealer_id  The dealer identifier
     *
     * @return     $this  The by dealer.
     */
    public function getByDealer($dealer_id)
    {

        $model = $this->model
            ->whereHas('dealers', function($query) use($dealer_id){
                $query->where('dealer_id', $dealer_id);
            });

        $this->model = $model;

        return $this;
    }

    public function createOne($data)
    {
        $name       = (isset($data['name']) ? $data['name'] : '' );
        $parentId   = (isset($data['parent_id']) ? $data['parent_id'] : 0);
        $logoId     = (isset($data['media_logo_id']) ? $data['media_logo_id'] : null);
        $desc       = (isset($data['description']) ? $data['description'] : '');
        $catalogUrl = (isset($data['catalog_url']) ? $data['catalog_url'] : '');
        $mediaIds   = (isset($data['media_ids']) ? $data['media_ids'] : '');
        $categoryId   = (isset($data['category']) ? $data['category'] : null);
        $opId = (isset($data['opid']) ? $data['opid'] : null);
        $wp_Appointment_id = (isset($data['wp_Appointment_id']) ? $data['wp_Appointment_id'] : null);
        $slug = (isset($data['slug']) ? $data['slug'] : null);
        $image_url = (isset($data['image_url']) ? $data['image_url'] : null);
        $image_link = (isset($data['image_link']) ? $data['image_link'] : null);
        $image_text = (isset($data['image_text']) ? $data['image_text'] : null);




        if(is_array($mediaIds))
        {
            $mediaIds = implode(',', $mediaIds);
        }elseif(!$mediaIds)
        {
            $mediaIds = '';
        }

        $insert = [
                    'name' => $name,
                    'parent_id' => $parentId,
                    'media_logo_id' => $logoId,
                    'description' => $desc,
                    'catalog_url' => $catalogUrl,
                    'media_ids' => $mediaIds,
                    'opid' => $opId,
                    'wp_Appointment_id' => $wp_Appointment_id,
                    'slug'          => $slug,
                    'image_url'     => $image_url,
                    'image_link'    => $image_link,
                    'image_text'    => $image_text,

                ];

        $Appointment = $this->skipPresenter()->create($insert);
        $category = Category::find($categoryId);

        $Appointment->categories()->save($category);

        return $Appointment;
    }

    public function customerAppointments($customer_id)
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

    public function orderByCategoryName($order = 'desc')
    {

        $this->model = $this->model
            ->join('Appointment_categories', 'Appointment_categories.Appointment_id', '=', 'Appointments.id')
            ->join('categories', 'categories.id', '=', 'Appointment_categories.category_id')
            ->orderBy('categories.name', $order)->select('Appointments.*');

        return $this;
    }

    public function getId($wp_Appointment_id){
        return $wp_Appointment_id;
        // return $this->model->where('wp_Appointment_id',$wp_Appointment_id)->first()->id;

    }

    public function updateOne($data,$Appointment_id){

        $Appointment=$this->model->find($Appointment_id);
        $AppointmentFields   = \Schema::getColumnListing($Appointment->getTable());

        foreach($AppointmentFields as $field)
        {
            if($field == 'id' || $field == 'wp_Appointment_id')
            {
                continue;
            }

            if(isset($data[$field]))
            {
                $Appointment->{$field} = $data[$field];
            }
        }

        $Appointment->save();

        return $Appointment;
    }
}