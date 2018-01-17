<?php 

Html::macro('modalYesNo', function($question, $id, $options = [])
{
	$options = $options + ['no_label' => 'No', 'yes_label' => 'Yes'] ;

    return view('admin.partials.modal.default', compact('question', 'options', 'id'));
});