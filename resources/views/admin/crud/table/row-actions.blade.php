@foreach($links as $key => $link)

	@if($link)
	    @if($key == 'edit')
	        {!! Html::edit_link($link['label'], $link['url']) !!}
	    @elseif($key == 'destroy')
	    	<?php $u = uniqid(); ?>
	        <?php # Html::destroy_link($link['label'], $link['url'], ['class' => 'ajax-destroy']) !!} 
	              ?>
	        <button type="button" class="btn btn-sm btn-white" data-toggle="modal" data-target="#{!! $u !!}"><i class="fa fa-trash"></i> {!! $link['label'] !!}</button>
	        {!! Html::modalYesNo($link['delete_msg'],  $u, [ 'yes_url' => $link['url'] ]) !!}
	    @else
	        {!! Html::icon_link($link['label'], $link['url'], '', array('class' => 'btn-sm ' . $link['class'])) !!}
	    @endif
	@endif
@endforeach