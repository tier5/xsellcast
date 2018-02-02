<?php

Html::macro('adminProspectActRow', function($content_left, $content_right)
{
    $class = (isset($attr['class'])? $attr['class'] : '' );

    return <<<HTML
            <div class="faq-item">
                <div class="row">
                    <div class="col-md-7">
                        $content_left
                    </div>
                    <div class="col-md-5">
                        $content_right
                    </div>
                </div>
            </div>
HTML;
});

?>            