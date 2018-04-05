<?php namespace App\Storage\Crud;

use \Form;
use \Html;

class CustomFields
{

	public function textAutoComplete($crud_field)
	{

		return view('admin.crud.form.custom-fields.autocomplete', compact('crud_field'));
	}

	public function h2Field($crud_field)
	{
		return "<h2>" . $crud_field->getOption('label') . "</h2>";
	}

	public function selectDealerModal($crud_field)
	{
		return view('admin.crud.form.custom-fields.select-dealer-modal', compact('crud_field'));
	}

	public function termsButton($crud_field)
	{
		return view('admin.crud.form.custom-fields.terms-buttons', compact('crud_field'));
	}

	public function salesrepAgreement($crud_field)
	{
		return view('admin.crud.form.custom-fields.salesrep-agreement', compact('crud_field'));
	}

	public function hoursOperation($crud_field)
	{
		$oldValue = \Request::old($crud_field->getOption('name'));

		if($oldValue)
		{
			$value = $oldValue;
		}elseif($crud_field->getOption('value') && is_string($crud_field->getOption('value')))
		{
			$value = unserialize($crud_field->getOption('value'));
		}

		return view('admin.crud.form.custom-fields.hours-operation', compact('crud_field', 'value'));
	}

	public function brandCategoryList($crud_field)
	{
		$caregories = \App\Storage\Category\Category::all()->lists('name', 'id')->toArray();
		// $caregories = ['' => 'Select category...'] + $caregories;

		return Form::admin_select($crud_field->getOption('name'), $crud_field->getOption('label'), $caregories, $crud_field->getOption('selected'));
	}

	public function brandsList($crud_field)
	{
		$brands = \App\Storage\Brand\Brand::all()->lists('name', 'id')->toArray();
		$brands = ['' => 'Select brand...'] + $brands;

		return Form::admin_select($crud_field->getOption('name'), $crud_field->getOption('label'), $brands, $crud_field->getOption('selected'));
	}

	public function statesList($crud_field)
	{
		$states = ['' => 'Select state...'] + states();
		return Form::admin_select($crud_field->getOption('name'), $crud_field->getOption('label'), $states, $crud_field->getOption('selected'), $crud_field->getOption('options'));
	}

	public function paragraph($crud_field)
	{
		return '<div>' . $crud_field->getOption('label') . '</div>';
	}

	/**
	 * Ontraport tags select field
	 *
	 * @param      CrudField  $crud_field  The crud field
	 *
	 * @return     View
	 */
	public function opTagSelect($crud_field)
	{
		$t     = new \App\Storage\Ontraport\TagObj();
		$tags  = $t->objects()->lists('tag_name', 'tag_id')->toArray();
		$lists = ['' => 'Select tag...'] + $tags;
		$f     =  Form::admin_select($crud_field->getOption('name'), $crud_field->getOption('label'), $lists, $crud_field->getOption('selected'), $crud_field->getOption('options'));

		return $f;
	}
}