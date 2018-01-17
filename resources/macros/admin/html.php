<?php

Html::macro('edit_link', function($label,$url, $attr = null)
{
    return <<<HTML
        <a href="$url" class="btn btn-sm btn-white"><i class="fa fa-pencil"></i> $label</a>
HTML;
});

Html::macro('destroy_link', function($label, $url, $attr = null)
{
    $class = (isset($attr['class'])? $attr['class'] : '' );

    return <<<HTML
        <a href="$url" class="btn btn-sm btn-white $class"><i class="fa fa-trash"></i> $label</a>
HTML;
});

html::macro('clear_all', function(){

    return <<<HTML
        <div class='col-lg-12 col-md-12 col-xs-12 col-sm-12'></div>
HTML;
});

Html::macro('icon_link', function($label, $url, $icon_class, $attr = null)
{
    $class = (isset($attr['class'])? $attr['class'] : '' );

    if(isset($attr['class'])){
        unset($attr['class']);
    }

    $attrStr = '';
    if($attr){
        foreach($attr as $key => $val){

            $attrStr .= ' ' . $key . '="'. $val .'"';
        }
    }

    return <<<HTML
        <a href="$url" class="btn $class" $attrStr><i class="$icon_class"></i> $label</a>
HTML;
});

Html::macro('button_link', function($label, $url, $attr = null)
{
    $class = (isset($attr['class'])? $attr['class'] : 'btn-default' );

    return <<<HTML
        <a href="$url" class="btn $class">$label</a>
HTML;
});

Html::macro('form_group_paragaph', function($label, $value, $attr = null)
{
    $class = (isset($attr['class'])? $attr['class'] : 'btn-default' );

    return <<<HTML
        <div class="col-md-12 $class">
            <div class="form-group">
                <label>$label</label>
                <p>$value</p>
            </div>
        </div>
HTML;
});

Html::macro('bs_row', function($html){

    return <<<HTML
    <div class="row">$html</div>
HTML;
});

Html::macro('bs_col', function($html, $col_sizes = 12, $col_offsets = 0){

    $classArr = [];

    if(is_array($col_sizes)){
        foreach($col_sizes as $point => $size)
        {
            $classArr[] = 'col-' . $point . '-' . $size;
        }
    }else{
        $classArr[] = 'col-md-' . $col_sizes;
    }

    if(is_array($col_offsets)){
        foreach($col_offsets as $point => $size)
        {
            $classArr[] = 'col-' . $point . '-offset-' . $size;
        }
    }else{
        $classArr[] = 'col-md-' . $col_offsets;
    }

    $class = implode(' ', $classArr);

    return <<<HTML
        <div class="$class">$html</div>
HTML;
});

Html::macro('fa_icon', function($class){

    return <<<HTML
        <i class="fa $class"></i>
HTML;
});

Html::macro('progressBar', function($progress){

    $class = '';

    if($progress <= 33.3)
    {
        $class .= " progress-bar-danger";
    }elseif($progress <= 66.6)
    {
        $class .= " progress-bar-warning";
    }elseif($progress >= 66.7)
    {
        $class .= " progress-bar-default";
    }

    return <<<HTML
    <div class="row">
        <div class="col-md-9">
            <div class="progress progress-small">
                <div style="width: $progress%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="$progress" role="progressbar" class="progress-bar $class">
                    <span class="sr-only">$progress% Complete (success)</span>
                </div>
            </div>            
        </div>
        <div class="col-md-2">
        $progress%
        </div>
    </div>
HTML;
});