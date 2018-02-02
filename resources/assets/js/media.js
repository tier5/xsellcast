var mediaGuid = function(){
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
      .toString(16)
      .substring(1);
  }
  return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
    s4() + '-' + s4() + s4() + s4();
}

var mediaFormUpload = function(){
	var html = "<form method='POST' id='lbt-file-upload-frm'><input type='file' name='file[]' multiple accept='' /></form>";

	if($('#lbt-file-upload-frm').length < 1){
		$('body').append(html);
	}
}; 

var mediaField = function(){

	$('.media-field').each(function(){
		
		var $container = $(this);
		var $btn = $('.media-upload', $container);
		var $list = $('.media-unorderlist', $container);
		var field_name = $container.attr('data-field-name');
		var field_accepts = $container.attr('data-field-accept');

		loadMedia($container, field_name);

		$btn.on('click', function(){

			if($('.media-field').length > 0 && $('#lbt-file-upload-frm').length < 1){
				mediaFormUpload();
			}
			
			var $file = $('#lbt-file-upload-frm input[type="file"]');			
			$file.attr('accept', field_accepts);
			$file.trigger('click');

			$file.on('change', function(){
				
				var formData = new FormData(); 
			    $.each($(this)[0].files,function(j, file){
			        formData.append('files['+j+']', file);
			    });
				mediaUpload(formData, $list, field_name);
			});			
		});
	});
};

var mediaVideoPlayer = function(url, ext){

	return '' +
	'<video class="video-js" width="320" height="240" controls>' + 
		'<source src="' + url + '" type="video/' + ext + '">' +
	'</video>';
};

var mediaImg = function(url)
{
	return '<img src="' + url + '" class="img-responsive" />';
};
//onClick="mediaDelete(' + liGuidStr + ')"
var mediaModalDelete = function(guid, li_guid){

	var liGuid = "'" + li_guid + "'";
	return '<div class="modal inmodal" id="media_modal_delete_' + guid + '" tabindex="-1" role="dialog" aria-hidden="true">' +
			    '<div class="modal-dialog modal-sm">' +
			        '<div class="modal-content animated bounceInRight">' + 
			            '<div class="modal-header">' +
			                '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' +
			                '<h4 class="modal-title">Question</h4>' +
			            '</div>' +
			            '<div class="modal-body">' + 
			                '<p>Are you sure you want to delete this file?</p>' + 
			            '</div>' +
			            '<div class="modal-footer">' +
			                '<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>' +
			                '<a href="#" data-dismiss="modal" class="btn btn-danger" onclick="mediaDelete(' + liGuid + ')">Yes</a>' +
			            '</div>' +
			        '</div>' +
			    '</div>' +
			'</div>';
};

var mediaAddMediaToList = function($list, data, field_name){
	if(!field_name){
		field_name = 'media';
	}

	var $cont = $list.closest('.media-field');
	var footCallback = $cont.attr('data-modal-foot-right');
	var isSingle = $cont.attr('data-is-single');

	if(!isSingle){
		field_name = field_name + '[]';
	}

	$.each(data, function(k, row){
		var thumb          = '<img src="' + row.thumbnail + '" />';
		var guid           = mediaGuid();
		var input          = "<input value='" + row.id + "' type='hidden' name='" + field_name + "'/>";
		var a              = "<a href='#' data-toggle='modal' data-target='#" + guid + "'>" + thumb + "</a>";
		var footerHtml     = '';
		var liGuid         = 'li_' + guid;
		var liGuidStr      = "'" + liGuid + "'";
		var liDelHtmlModal = mediaModalDelete(guid, liGuid);

		if (typeof window[footCallback] === "function") {

			footerHtml = window[footCallback](row, liGuid);
		}

		if(row.type === 'image'){
			body = mediaImg(row.url);
		}else if(row.type === 'video'){
			body = mediaVideoPlayer(row.url, row.extension);
		}

		var modal = 
		'<div class="modal inmodal" id="' + guid + '" tabindex="-1" role="dialog" aria-hidden="true">' + 
		    '<div class="modal-dialog">' +
			    '<div class="modal-content animated bounceInRight">' +
			        '<div class="modal-header">' +
			            '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' +
			            '<h4 class="modal-title">Attachment Details</h4>' +
			        '</div>' +
			        '<div class="modal-body text-center">' +
			           body +
			        '</div>' +
			        '<div class="modal-footer">' +
			        	'<div class="row">' +
			        		'<div class="col-sm-6 text-left">' +
			        			'<p><strong>' + row.slug + '</strong><br/>' +
			        			'<strong>Added on ' + row.created_at_standard + '</strong></p>' +
			        		'</div>' +
			     			'<div class="col-sm-6">' +
					            '<button type="button" class="btn btn-white" data-target="#media_modal_delete_' + guid + '" data-toggle="modal" data-dismiss="modal">Delete</button>' +
					            footerHtml + 
			     			'</div>' +
			        '</div>' +
		        '</div>' +
		    '</div>' +
		'</div>';

		if(isSingle){
			$('li', $list).remove();
		}

		$list.append("<li id='" + liGuid + "' data-value='" + row.id + "'>" + input + a + "</li>");
		$('body').append(modal);
		$('body').append(liDelHtmlModal);
	});

	$(window).trigger( "mediaAdded" );
};

var mediaUpload = function(form_data, $list, field_name){
	var url      = laroute.route('admin.upload.submit');
	var $cont    = $list.closest('.media-field');
	var $btn     = $('.media-upload', $cont);
	var btnLbl   = $btn.attr('data-label');

	$btn.text('Loading...');
	$btn.attr('disabled', 'disabled');

	$.ajax({
		url: url,
		processData: false,
		contentType: false,			
		dataType: 'json',
		data: form_data,
		type: 'POST',
		error: function(r) {

			if(r.status == 422){
				var err =  r.responseJSON;

				$.each( r.responseJSON, function( key, value ) {
					if(typeof value.body !== undefined){
						$.gritter.add({
							title: value.title,
							text: value.body,
							time: 8000,
							class_name: 'gritter-danger'
						});
					}else{
						$.gritter.add({
							title: 'Error',
							text: jsonErrorToHtml(r.responseJSON),
							time: 8000,
							class_name: 'gritter-danger'
						});
					}
				});

			}else{

				$.gritter.add({
					title: 'Error!',
					text: 'Uknown error found.',
					time: 8000,
					class_name: 'gritter-danger'
				});				
			}
	   	},
	   	success: function(data) {

	   		mediaAddMediaToList($list, data.data, field_name)
			$.gritter.add({
				text: 'Media uploaded!',
				time: 3000
			});
	   	}
	}).complete(function(){
		$btn.text(btnLbl);
		$btn.attr('disabled', false);
		$('#lbt-file-upload-frm').remove();
	});

};

var loadMedia = function($container, field_name){
	var $list = $('.media-unorderlist', $container);
	var ids = $container.attr('data-field-value');
	
	if(ids == ""){
		return false;
	}

	var url = laroute.route('admin.media.show', {media_id: ids});

	$.ajax({
		url: url,
		processData: false,
		contentType: false,			
		dataType: 'json',
		type: 'GET',
		error: function(r) {
			var html = '';
			if(r.status == 422){
				
				html = jsonErrorToHtml(r.responseJSON);
			}else{

				html = 'Uknown error found.';
			}

			$.gritter.add({
				title: 'Loading record error!',
				text: html,
				time: 8000,
				class_name: 'gritter-danger'
			});
	   	},
	   	success: function(data) {
	   		mediaAddMediaToList($list, data.data, field_name);
	   	}
	}).complete(function(){

	});		
};

var openField = function(elem) {
    if (document.createEvent) {
        var e = document.createEvent("MouseEvents");
        e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        elem[0].dispatchEvent(e);
    } else if (element.fireEvent) {
        elem[0].fireEvent("onmousedown");
    }
}	

var editSelectField = function(){

	$('.form-group.edit_select').each(function(){

		var $this = $(this);
		var $lblSelect = $('label.selected', $this);
		var $selectBox = $('select', $lblSelect);
		var $spanTxt = $('span.txt', $lblSelect);

		$selectBox.on('change', function(){
			var txt = $('option:selected', $(this)).text();
			$spanTxt.text(txt);
		});
	});
};

var mediaDelete = function(li_id){
	$('#' + li_id).remove();
};

var offerModalFooter = function(d, li_id){
	if(d.type === 'image'){
		return '<button type="button" class="btn btn-primary" data-dismiss="modal" onClick="offerSetThumb(' + d.id + ')">Set as Featured Image</button>';		
	}

	return '';
}		

var offerSetThumb = function(media_id){
	$('#thumbnail_id').val(media_id);
	offerSetThumbHighLight(media_id);
};

var offerSetThumbHighLight = function(media_id){
	var $ul = $('#media-field-media ul.media-unorderlist');
	var $li = $('li[data-value="' + media_id + '"]', $ul);
	$('li', $ul).removeClass('active');
	$li.addClass('active');
};

var mediaAddedTrigger = function(){
	$(window).on('mediaAdded', function(){
		var media_id = $('#thumbnail_id').val();
		offerSetThumbHighLight(media_id);
	});
};

$(document).ready(function(){
	
	mediaField();
	editSelectField();
	mediaAddedTrigger();

});