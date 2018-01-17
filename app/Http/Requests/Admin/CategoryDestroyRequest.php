<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Storage\Category\Category;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
/**
 * This request is for App\Http\Controllers\Admin\CategoriesController
 */
class CategoryDestroyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	$category = Category::find($this->route('category_id'));
    	
    	if(!$category)
    	{
    		return false;
    	}

    	if($category->brands->count() > 0)
    	{

    		return false;
    	}

    	$this->attributes->add(compact('category'));

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }

    public function forbiddenResponse()
    {
    	$error = 'Oops, this category cannot be deleted because one or more brands are associated with it. Please assign those brands to other categories before attempting to delete this category.';

        return new JsonResponse(['error' => $error], 422);
    }  
}
