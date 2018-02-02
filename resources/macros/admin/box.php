<?php

Html::macro('box_open', function($label, $options = [])
{
    $isShowTitle = (isset($options['show_title']) ? $options['show_title'] : true);
    $html = '';
    if($isShowTitle){
        $html .=     
        '<div class="ibox-title">
            <h5 class="box-title">' . $label . '</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </div>                  
        </div>';
    }

    return <<<HTML
        <div class="ibox">
            $html
HTML;
});

Html::macro('box_body_open', function($class = '')
{
    return <<<HTML
        <div class="ibox-content $class">
            <div>
HTML;
});

Html::macro('box_body_close', function()
{
    return <<<HTML
            </div>
        </div>
HTML;
});

Html::macro('box_footer_btn', function($label, $attr = array()){

    $class = (isset($attr['class']) ? $attr['class'] : '' );
    return <<<HTML
        <div class="box-footer">
            <button class="btn $class">$label</button>
        </div>
HTML;

});

Html::macro('box_footer', function($html){

    return <<<HTML
        <div class="box-footer">
            $html
        </div>
HTML;

});

Html::macro('box_footer_btn_primary', function($label)
{
    return Html::box_footer_btn($label, array('class' => 'btn-primary'));
});

Html::macro('box_footer_btn_danger', function($label)
{
    return Html::box_footer_btn($label, array('class' => 'btn-danger'));
});

Html::macro('box_close', function()
{
    return <<<HTML
        </div>
HTML;
});

HTML::macro('box_form_open_get', function($route, $header_label = "", $opt = [])
{
    $head = '';
    if(isset($opt['views'])){
        foreach ($opt['views'] as $view) {
            $head .= $view->render();
        }
    }

    return
        Html::box_open($header_label, $opt).
            Form::open(['route' => $route, 'method' => 'GET']) .
                $head .
                Html::box_body_open();
});

HTML::macro('box_form_open_post', function($route, $header_label = "", $attr = [], $opt = [])
{
    $head = '';

    if(isset($opt['views'])){
        foreach ($opt['views'] as $view) {
            $head .= $view->render();
        }
    }

    $param = array_merge($attr, ['route' => $route, 'method' => 'POST']);
    return 
        Html::box_open($header_label, $opt). 
            Form::open($param) .
                $head .
                Html::box_body_open();
});

HTML::macro('box_form_open_put', function($route, $model, $model_id, $header_label = "", $opt = [])
{
    $head = '';
    if(isset($opt['views'])){
        foreach ($opt['views'] as $view) {
            $head .= $view->render();
        }
    }

    return 
        Html::box_open($header_label, $opt). 
            Form::model($model,['route' => [$route, $model_id], 'method' => 'PUT']) .
                $head .
                Html::box_body_open();
});

HTML::macro('box_form_close', function($button_label = 'Save', $type = 'primary')
{
   // $html = new Html();
    $button = call_user_func(array('Html', 'box_footer_btn_' . $type), $button_label);
    return      $button .
                Html::box_body_close().
            Form::close().
        Html::box_close();
});