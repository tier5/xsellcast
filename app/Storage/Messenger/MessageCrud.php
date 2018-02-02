<?php namespace App\Storage\Messenger;

use HTML;
use App\Storage\Crud\TableCollection;
use App\Storage\Crud\CrudForm;
use App\Storage\Crud\Box;
use Illuminate\Http\Request;

class MessageCrud
{

	public static function ajaxTable($url)
	{
		$table  = new TableCollection();
	    $info   = array(  
	      'box_title'     => 'All Prospects', 
	      'column_size'   => 8, 
	      'column_class'  => 'col-sm-12 col-xs-12',
	      'box_float'     => 'left');

		$table = $table->make([])
	      ->columns(array(
	        'checkbox' 	=> 'checkbox',
	        'sender'	=> 'sender',
	        'content' 	=> 'content',
	        'time'		=> 'time'
	      ))
	      ->toActionShow(false)
	      ->sortable(array('name'))
	      ->setView('admin.crud.table.ajax-table')
	      ->addExtra('url', $url)
	      ->showHeader(false)
	      ->useDefaultTblClass(false)
	      ->addAttribute('class', 'table-hover table-mail table')
	      ->addAttribute('id', 'messages-ajax-tbl')
	      ->addExtra('js-callback', 'messageTblTd')
	      ->addExtra('after-append', 'messageAfterAppend');

	    $box = new Box($info);
	    $box->setTable($table);    
	    
	    return $table;
	}

}

