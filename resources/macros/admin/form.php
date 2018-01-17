<?php

Html::macro('admin_form_row', function($input, $label__args = null, $help = null)
{
    $label = ($label__args ? Form::label($label__args['name'], $label__args['label'], ['class'=>'control-label', 'style' => 'display: block']) : '' );

    return view('admin.crud.form.parts.row', compact('label', 'input', 'help'));
});

Form::macro('admin_textarea', function($name, $label, $value = null, $options = array())
{
    if(!isset($options['id'])){
        $options['id'] = $name;
    }

    if(!isset($options['class'])){
        $options['class'] = '';
    }
    $options['class'] .= ' form-control';

    $value = (string) Form::getValueAttribute($name, $value);
    $input = Form::textarea($name , $value, $options);
    $html = HTML::admin_form_row($input, array('name' => $options['id'], 'label' => $label));

    return $html;
});

Form::macro('admin_tinymce', function($name, $label, $value = null, $options = array())
{
    if(!isset($options['class'])){
        $options['class'] = '';
    }    
    $options['class'] .= ' tinymce-field';

    return Form::admin_textarea($name, $label, $value, $options);
});

Form::macro('admin_text', function($name, $label, $value = null, $options = array())
{
    if(!isset($options['id'])){
        $options['id'] = $name;
    }

    if(!isset($options['class'])){
        $options['class'] = '';
    }

    $options['class'] .= ' form-control';
    $help = (isset($options['help']) ? $options['help'] : null);
    $value = (string) Form::getValueAttribute($name, $value);
    $input = Form::text($name , $value, $options);
    $html = HTML::admin_form_row($input, array('name' => $options['id'], 'label' => $label), $help);

    return $html;
});

Form::macro('admin_email', function($name, $label, $value = null, $options = array())
{
    if(!isset($options['id'])){
        $options['id'] = $name;
    }

    if(!isset($options['class'])){
        $options['class'] = '';
    }

    $options['class'] .= ' form-control';
    $help = (isset($options['help']) ? $options['help'] : null);
    $value = (string) Form::getValueAttribute($name, $value);
    $input = Form::email($name , $value, $options);
    $html = HTML::admin_form_row($input, array('name' => $options['id'], 'label' => $label), $help);

    return $html;
});

Form::macro('admin_password', function($name, $label, $options = array())
{
    if(!isset($options['id'])){
        $options['id'] = $name;
    }

    if(!isset($options['class'])){
        $options['class'] = '';
    }

    $options['class'] .= ' form-control';
    $help = (isset($options['help']) ? $options['help'] : null);
  //  $value = (string) Form::getValueAttribute($name, $value);
    $input = Form::password($name, $options);
    $html = HTML::admin_form_row($input, array('name' => $options['id'], 'label' => $label), $help);

    return $html;
});

Form::macro('admin_daterange', function($name, $label, $value = null, $options = array())
{
    if(!isset($options['id'])){
        $options['id'] = $name;
    }

    if(!isset($options['class'])){
        $options['class'] = '';
    }
    $options['class'] .= ' form-control input-daterange';
    $help = (isset($options['help']) ? $options['help'] : null );

    $value = (string) Form::getValueAttribute($name, $value);
    $input = Form::text($name , $value, $options);
    $html = <<<HTML
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            $input
        </div>
HTML;

    $html = HTML::admin_form_row($html, array('name' => $options['id'], 'label' => $label), $help);

    return $html;
});

Form::macro('admin_date', function($name, $label, $value = null, $options = array())
{
    if(!isset($options['id'])){
        $options['id'] = $name;
    }

    if(!isset($options['class'])){
        $options['class'] = '';
    }
    $options['class'] .= ' form-control input-date';
    $help = (isset($options['help']) ? $options['help'] : null );

    $value = (string) Form::getValueAttribute($name, $value);
    $input = Form::text($name , $value, $options);
    $html = <<<HTML
        <div class="input-group date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            $input
        </div>
HTML;

    $html = HTML::admin_form_row($html, array('name' => $options['id'], 'label' => $label), $help);

    return $html;
});

Form::macro('admin_number', function($name, $label, $value = null, $options = array())
{
    if(!isset($options['id'])){
        $options['id'] = $name;
    }

    if(!isset($options['class'])){
        $options['class'] = '';
    }
    $options['class'] .= ' form-control';

    $value = (string) Form::getValueAttribute($name, $value);
    $input = Form::number($name , $value, $options);
    $html = HTML::admin_form_row($input, array('name' => $options['id'], 'label' => $label));

    return $html;
});

Form::macro('admin_select', function($name, $label, $list, $selected = null, $options = array())
{
    if(!isset($options['id'])){
        $options['id'] = $name;
    }

    if(!isset($options['class'])){
        $options['class'] = '';
    }
    
    $options['class'] .= ' form-control select2';

    $noLabel = (isset($options['nolabel']) ? $options['nolabel'] : false );
    $labelArgs = array('name' => $options['id'], 'label' => $label);

    if($noLabel)
    {
        $labelArgs = null;
    }

    $input = Form::select($name , $list, $selected, $options);
    $html = HTML::admin_form_row($input, $labelArgs);

    return $html;
});

Form::macro('admin_checkbox', function($name, $label, $value = null, $options = array())
{
    if(!isset($options['id'])){
        $options['id'] = $name;
    }

    if(!isset($options['class'])){
        $options['class'] = '';
    }
    $options['class'] .= ' form-control';

    $value = (string) Form::getValueAttribute($name, $value);
    $input = '<div class="checkbox checkbox-styled"><label>' . Form::checkbox($name, $value) . '<span>'.$label.'</span></label></div>';
    $html = HTML::admin_form_row($input, array('name' => $options['id'], 'label' => ' '));

    return $html;
});

Form::macro('admin_radio', function($name, $label, $list, $value = null, $options = array())
{
    if(!isset($options['id'])){
        $options['id'] = $name;
    }

    if(!isset($options['class'])){
        $options['class'] = '';
    }
    $options['class'] .= ' form-control';

    $value = (string) Form::getValueAttribute($name, $value);
    $input = view('admin.crud.form.custom-fields.radio-list', compact('list', 'value', 'name')); 
    //'<div class="checkbox checkbox-styled"><label>' . Form::checkbox($name, $value) . '<span>'.$label.'</span></label></div>';
    $html = HTML::admin_form_row($input, array('name' => $options['id'], 'label' => $label));

    return $html;
});

Form::macro('admin_file', function($name, $label, $value = null, $options = array())
{
    if(!isset($options['id'])){
        $options['id'] = $name;
    }

    if(!isset($options['class'])){
        $options['class'] = '';
    }
    $options['class'] .= ' ';

    $value = (string) Form::getValueAttribute($name, $value);
    $input = Form::file($name, $options);
    $html  = HTML::admin_form_row($input, array('name' => $options['id'], 'label' => $label));

    return $html;
});

Form::macro('admin_link', function($label, $url, $options = array())
{   
    $link = HTML::button_link($options['label'], $options['url'], $options);
    $html = HTML::admin_form_row($link, array('name' => '--', 'label' => '&nbsp;'));

    return $html;
});

Form::macro('plain_hours', function($name, $selected = null, $options = []){

    $list               = [];
    $options['nolabel'] = true;
    
    for($i = 0; $i < 24; $i++){
        $list[$i . '00'] = ($i % 12 ? $i % 12 : 12) . ':00' . ($i >= 12 ? 'pm' : 'am');
        $list[$i . '30'] = ($i % 12 ? $i % 12 : 12) . ':30' . ($i >= 12 ? 'pm' : 'am');
    }    

    return Form::admin_select($name, ' ', $list, $selected, $options);
});

Form::macro('states', function($name, $label, $selected = null, $options = []){

    $list = ['' => 'Select state...'] + states();   

    return Form::admin_select($name, $label, $list, $selected, $options);
});