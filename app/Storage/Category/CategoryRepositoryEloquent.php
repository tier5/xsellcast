<?php

namespace App\Storage\Category;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\Category\CategoryRepository;
use App\Storage\Category\Category;
use App\Storage\Category\CategoryValidator;

/**
 * Class CategoryRepositoryEloquent
 * @package namespace App\Storage\Category;
 */
class CategoryRepositoryEloquent extends BaseRepository implements CategoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return CategoryValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function withDealers()
    {
        $this->model = $this->model->withDealers();

        return $this;
    }
}
