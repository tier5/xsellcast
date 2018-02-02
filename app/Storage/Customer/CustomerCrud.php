<?php namespace App\Storage\Customer;

use App\Storage\Crud\TableCollection;
use App\Storage\Crud\CrudForm;
use App\Storage\Crud\Box;
use HTML;
use Illuminate\Http\Request;

class CustomerCrud
{ 

    public static function tableUnmatched($model, $opt)
    {
        $table  = new TableCollection();
        $all    = ($model ? $model->all() : [] );
        $info   = array(  
          'box_title'     => 'Prospects',
          'box_body_class' => 'no-padding',
          'column_size'   => 12);

        $table = $table->make($all)
            ->columns(array(
                'name'     => 'Name',
                'status'  => 'Status'
            ))
            ->modify('name', function($row){

              if(!$row->user)
              {
                return 'n/a';
              }else{
                
                return Html::link(route('admin.prospects.show', ['customer_id' => $row->id]), $row->user->firstname . ' ' . $row->user->lastname);                
              }

            })
            ->modify('status', function($row){

              return ($row->has_pending ? "Pending BA's Approval" : 'New' );
            })
            ->sortable(['name', 'status'])
            ->toActionShow(false);

        $box = new Box($info);
        $box->setTable($table);    

        return $box;          
    }

  	public static function ajaxUserTable($model = null, $url)
  	{
  		$table  = new TableCollection();
  		$all    = ($model ? $model->all() : [] );
      $info   = array(  
        'box_title'     => 'All Prospects', 
        'column_size'   => 8, 
        'column_class'  => 'col-sm-12 col-xs-12',
        'box_float'     => 'left');

  		$table = $table->make($all)
        ->columns(array(
          'name' => 'Name'
        ))
        ->toActionShow(false)
        ->sortable(array('name'))
        ->setView('admin.crud.table.ajax-table')
        ->addExtra('view_before', 'admin.crud.table.letter-paginate')
        ->addExtra('url', $url)
        ->addExtra('js-callback', 'salesRepProspectTd');

      $box = new Box($info);
      $box->setTable($table);    
      
      return $box;  
  	}

    /**
      * Search field
      *
      * @return CrudForm 
      */
    public static function searchField($request)
    {
      $search = $request->get('s');
      $fields = new CrudForm('get');
      $fields->setRoute('admin.prospects');
      $fields->addField(array(
        'name'      => 's',
        'label'     => 'Search',
        'type'      => 'text',
        'col-class' => 'col-md-12',
        'value'     => $search));

      $fields->setSubmitText('Search');
      $fields->noRedirectField();

      $info = array(
        'box_title'     => 'Keyword search', 
        'column_size'   => 4,
        'column_class'  => 'col-sm-12 col-xs-12',
        'box_float'     => 'right');

      $box = new Box($info);
      $box->setForm($fields);

      return $box;
    }   	

    public static function lookbookTable($model, $opt)
    {
      $table  = new TableCollection();
      $all    = ($model ? $model->all() : [] );
      $info   = array(  
        'box_title'     => (isset($opt['box_title']) ? $opt['box_title'] : 'My Offers' ),
        'box_body_class' => 'no-padding',
        'column_size'   => 12);

      $table = $table->make($all)
        ->columns(array(
            'thumbnail' => 'Thumbnail',
            'info'    => '',
            'added' => 'Added to Lookbook'
          ))
          ->modify('thumbnail', function($pivot){
            $offer = $pivot->offer;
            $medias = $offer->medias();
            $thumbId = $offer->getMeta('thumbnail_id');

            if(!$medias){

              return '';
            }

            if($thumbId){
              $media = $medias->find($thumbId);
            }else{
              $media = $medias->first();
            }

            if(!$media){

              return '';
            }

            $url = $media->getSize(150, 100);

            $img = ($url ? HTML::image($url) : '' );

            return HTML::link(route('admin.offers.edit', [$offer->id]), $img, [], null, false);
          })
          ->modify('info', function($pivot){
            $o = $pivot->offer;
            return view('admin.customer.table.lookbook-col-info', ['offer' => $o, 'pivot' => $pivot]);
          })
          ->modify('added', function($row){

            return $row->created_at->format('l \a\t h:i A');
          })
          ->sortable(['added'])
          ->addAttribute('id', 'offer-list-tble')   
          ->toActionShow(false);

      $box = new Box($info);
      $box->setTable($table);    

      return $box;          
    }
}

?>