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

        if(is_array($mediaIds))
        {
            $mediaIds = implode(',', $mediaIds);
        }elseif(!$mediaIds)
        {
            $mediaIds = '';
        }

        $insert = ['name' => $name, 'parent_id' => $parentId, 'media_logo_id' => $logoId, 'description' => $desc, 'catalog_url' => $catalogUrl, 'media_ids' => $mediaIds, 'opid' => $opId];

        $brand = $this->skipPresenter()->create($insert);
        $category = Category::find($categoryId);

        $brand->categories()->save($category);

        return $brand;
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

    public function orderByCategoryName($order = 'desc')
    {

        $this->model = $this->model
            ->join('brand_categories', 'brand_categories.brand_id', '=', 'brands.id')
            ->join('categories', 'categories.id', '=', 'brand_categories.category_id')
            ->orderBy('categories.name', $order)->select('brands.*');

        return $this;
    }
}