<?php

namespace App\Storage\DealersCategory;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\DealersCategory\DealersCategoryRepository;
use App\Storage\DealersCategory\DealersCategory;
use App\Storage\DealersCategory\DealersCategoryValidator;
use App\Storage\DealersCategory\DealersCategoryPresenter;

/**
 * Class DealersCategoryRepositoryEloquent
 * @package namespace App\Storage\DealersCategory;
 */
class DealersCategoryRepositoryEloquent extends BaseRepository implements DealersCategoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DealersCategory::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return DealersCategoryValidator::class;
    }


    public function presenter()
    {
        return DealersCategoryPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function lookBySlug($slug)
    {
        $this->model = $this->model->where('slug', $slug);
    }
}
