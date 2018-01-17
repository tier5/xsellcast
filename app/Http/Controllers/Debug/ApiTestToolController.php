<?php

namespace App\Http\Controllers\Debug;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\Crud\CrudForm;
use App\Storage\Crud\Box;
use App\Storage\LocalApiRequest\LocalApiRequest;

class ApiTestToolController extends Controller
{
	public function __construct()
	{
		$this->crud = new Crud();
	}

	public function index(Request $request)
	{
		$layoutColumns       = $this->crud->layoutColumn();	
		$params              = $request->get('params');
		$route               = $request->get('route');
		$pArr                = explode("\r\n", $params);
		$p                   = implode('&', $pArr);
		$html                = '';
		$htmlRoutes          = '';
		$selectedRouteMethod = null;
		$localApi            = new LocalApiRequest();

		parse_str($p, $query);
//echo '<pre>';
		foreach ($this->groupListOfRoutes('api/v1') as $r) 
		{
			$htmlRoutes .= '<pre><label class="label">' . $r->getMethods()[0] . '</label> ' . $r->getAction()['as'] . ' : ' . $r->getUri() . '</pre>';

			if($r->getAction()['as'] == $route)
			{
				$selectedRouteMethod = $r->getMethods()[0];
			}

	//		print_r($r->getAction());
		}

//exit();
		$formParams = $query;

		if($route)
		{
			$url      = ($route ? $localApi->url($route, ($selectedRouteMethod == 'POST' ? [] : $query)) : null);
			$client   = new \GuzzleHttp\Client();
			$response = $client->request($selectedRouteMethod, $url, $formParams);
			$json     = (string)$response->getBody();
			$html     .= '<pre>URL: ' . $url . '</pre>';
			$html     .= '<pre>Token: ' . $localApi->getToken() . '</pre>';
			$html     .= '<pre>' . print_r(json_decode($json), true) . '</pre>';  
		}

		$layoutColumns->addItemForm($this->form($request));
        $layoutColumns->addItem($htmlRoutes, ['show_box' => true, 'column_size' => 8]);
        $layoutColumns->addItem($html, ['show_box' => true, 'column_size' => 12]);

		return $this->crud->pageView($layoutColumns);	
	}

	protected function groupListOfRoutes($prefix)
	{
		$name = $prefix;
		$routeCollection = \Route::getRoutes(); // RouteCollection object
		$routes = $routeCollection->getRoutes(); // array of route objects
		$grouped_routes = array_filter($routes, function($route) use ($name) {
		    $action = $route->getAction();
	
		    return (isset($action['prefix']) && (strpos($action['prefix'], 'api/v1') !== false));
		});

		return $grouped_routes;	
	}

    protected function form($request)
    {
		$fields = $this->crud->crudForm('get');
		$fields->setRoute('debug.admin.api.tool');
		$fields->addField(array(
			'name'      => 'route',
			'label'     => 'Route',
			'type'      => 'text',
			'value'     => $request->get('route'),
			'col-class' => 'col-md-12'));

		$fields->addField(array(
			'name'      => 'params',
			'label'     => 'Parameters',
			'type'      => 'textarea',
			'value'     => $request->get('params'),
			'col-class' => 'col-md-12'));

		$fields->setSubmitText('Submit');
		$fields->noRedirectField();

		$info = array(
			'box_title'    => 'API', 
			'column_size'  => 4,
			'column_class' => 'col-sm-12 col-xs-12');
		$box = $this->crud->box($info);

		$box->setForm($fields);

		return $box;
    }	

}