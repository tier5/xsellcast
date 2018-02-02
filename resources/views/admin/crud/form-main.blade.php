@if($crud_form->getMethod() == 'post')
    {!! Html::box_form_open_post($crud_form->getRoute(), $crud_form->getBoxLabel(), ['data-valdiation-url' => $crud_form->getValidationUrl()], ['views' => $crud_form->getHeaderViews(), 'show_title' => $crud_form->isShowDefaultHead()]) !!}
@elseif($crud_form->getMethod() == 'put')
    {!! Html::box_form_open_put($crud_form->getRoute(), $crud_form->getModel(), $crud_form->getModelId(), $crud_form->getBoxLabel(), ['views' => $crud_form->getHeaderViews(), 'show_title' => $crud_form->isShowDefaultHead()]) !!}
@else
    {!! Html::box_form_open_get($crud_form->getRoute(), $crud_form->getBoxLabel(), ['views' => $crud_form->getHeaderViews(), 'show_title' => $crud_form->isShowDefaultHead()]) !!}
@endif
            <!-- Form fields goes here -->
            <div class="row">
                @foreach($crud_form->getFields() as $field)
                    <div class="{!! $field->getOption('col-class') !!}">
                        {!! $field->render() !!}
                    </div>

                    @if($field->getOption('clear_all'))
                     {!! HTML::clear_all() !!}
                    @endif
                @endforeach
            </div>
            <!-- /End form fields -->
            <div class="box-footer">

                @foreach($crud_form->getSubmitBtns() as $n => $val)
                	@if($n != 'default' || ($n == 'default' && $crud_form->isShowDefaultSubmit()))
                        @if(!isset($val['is_link']) || !$val['is_link'])
                    	   <button type="submit" class="btn @if(isset($val['class'])){!! $val['class'] !!}@endif" value="{!! $n !!}" name="{!! $n !!}">@if(isset($val['icon_class'])){!! HTML::fa_icon($val['icon_class']) !!}@endif {!! $val['label'] !!}</button>
                        @else
                            <a href="{!! $val['url'] !!}" class="btn @if(isset($val['class'])){!! $val['class'] !!}@endif">@if(isset($val['icon_class'])){!! HTML::fa_icon($val['icon_class']) !!}@endif {!! $val['label'] !!}</a>
                        @endif
                    @endif
                @endforeach
            </div>

       {!! Html::box_body_close() !!}
    {!! Form::close() !!}
{!! Html::box_close() !!}