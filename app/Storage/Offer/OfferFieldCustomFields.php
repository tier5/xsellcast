<?php namespace App\Storage\Offer;

use \Form;
use \Html;

class OfferFieldCustomFields
{

	public function mediaUpload($crud_field)
	{
		$label = $crud_field->getOption('label');
		$options = $crud_field->getOption('field-attr');
		$name = $crud_field->getOption('name');
		$value = $crud_field->getOption('value');
		$accepts = ($crud_field->getOption('accepts') ? $crud_field->getOption('accepts') : 'image/*');
		$footModalRth = ($crud_field->getOption('modfootrth') ? $crud_field->getOption('modfootrth') : '');
	    
	    if(!isset($options['id'])){
	        $options['id'] = $name;
	    }

	    if(!isset($options['class'])){
	        $options['class'] = '';
	    }

	    $options['class'] .= ' form-control';
	    $val = Form::getValueAttribute($name, $value);
	    $value = ($val ? implode(',', $val) : null);

	    $btn = <<<HTML
	    <div class="form-group">
			<button type="button" class="btn btn-primary btn-block media-upload" data-label="Add Media">Add Media</button> 
		</div>	
HTML;
	    $btnRow = HTML::bs_row(HTML::bs_col($btn, ['xs' => 6, 'sm' => 6, 'md' => 3], ['xs' => 3, 'sm' => 3, 'md' => 0]));
		
	    $media = <<<HTML
	    <ul class="media-unorderlist"></ul>
HTML;

		$mediaRow = HTML::bs_row(HTML::bs_col($media));

		return <<<HTML
		<div class="media-field" id="media-field-$name" data-field-name="$name" data-field-accept="$accepts" data-field-value="$value" data-modal-foot-right="$footModalRth">
			$mediaRow
			$btnRow
		</div>
HTML;
	}

	public function offerSelect($crud_field)
	{
		$btn = view('admin.offer.fields.offer_select_btn', compact('crud_field'));
		return view('admin.offer.fields.offer_select', compact('btn', 'crud_field'));
	}

	public function editSelect($crud_field)
	{

		return view('admin.offer.fields.edit_select', compact('crud_field'));
	}

	public function yesNoLinkModal($crud_field)
	{

		return view('admin.offer.fields.yesno_link_modal', compact('crud_field'));
	}
}