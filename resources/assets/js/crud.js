var loadTblWayStr = function(){

	return 'loading-tbl-waypoint';
};

var isLoadingTblWaypoint = function(){

	return $('body').hasClass(loadTblWayStr());
}

var rmLoadingTbl = function(){

	$('body').removeClass(loadTblWayStr());
}

var tblAjaxLoad = function(elem, success_callback, no_url_callback)
{
	var $this = $(elem);
	var url = $this.attr('data-url');
	var dataFields = [];
	var $tbody = $('table tbody', $this);
	var $loadBtn = $('.btn-loadmore', $this);
	var td_callback = $this.attr('data-callback-func');
	var after_append_callback = $this.attr('data-after-append');

	$loadBtn.text('LOADING...');
	$loadBtn.attr('disabled', 'disabled');

	if(!url || $this.hasClass('all-loaded')){
		if (typeof no_url_callback === "function") {
			no_url_callback();
		}

		return false;
	}

	$('table thead tr th', $this).each(function(){
		dataFields.push($(this).attr('data-field'));
	});

	if(isLoadingTblWaypoint()){

		return false;
	}

	$('body').addClass(loadTblWayStr());

	$.ajax({
		url: url,
		dataType: 'json',
		type: 'get',
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
	   		var html = '';

	   		$.each(data.data, function(data_k, row){
	   			html = html + "<tr>";
		   		$.each(dataFields, function(k, v){

					if (typeof window[td_callback] === "function") {
						var td = window[td_callback](v, row);
						var attr = '';
						if(td.attr){
							$.each(td.attr, function(attr_k, attr_v){
								attr = attr + ' ' + attr_k + '="' + attr_v + '"';
							});
						}

						html = html + "<td " + attr + ">" + td.html + "</td>";
					}else{
						console.log('Failed to execute callback.');
					}
		   		});
		   		html = html + "</tr>";
	   		});

	   		$tbody.append(html);
	   		if (typeof window[after_append_callback] === "function") {
	   			window[after_append_callback]();
	   		}

	   		if(data.meta.pagination.total_pages > data.meta.pagination.current_page){

		   		$this.attr('data-url', data.meta.pagination.links.next);
	   		}else{
	   			$this.attr('data-url', null);
	   			$loadBtn.hide();
	   			$this.addClass('all-loaded');
	   		}

	   		if (typeof success_callback === "function") {
				success_callback();
	   		}
	   	}
	}).complete(function(){
		rmLoadingTbl();
		Waypoint.refreshAll();
		$loadBtn.text('LOAD MORE');
		$loadBtn.removeAttr('disabled');
	});
};

var waypointInit = function() {

	var waypoints = $('.tbl-waypoint').waypoint(function(direction) {

		tblAjaxLoad(this.element);

	}, { offset: 'bottom-in-view' });

	$('.tbl-waypoint').each(function(){
		tblAjaxLoad($(this));
	});


};

var tblLoadMore = function() {
	$('.tbl-waypoint .btn-loadmore').on('click', function(e){

		var elem = $(this).closest('.tbl-waypoint');
		tblAjaxLoad(elem);
		e.preventDefault();
	});
};

var ajaxTblLetterBtn = function($btn) {
	var letter = $btn.attr('data-letter');
	var $cont = $btn.closest('.tbl-waypoint');
	var $td = $('table tr td[data-letter="' + letter +'"]', $cont);
	var $loadBtn = $('.btn-loadmore', $cont);

	console.log('Analyzing...');
	if($td.length > 0)
	{
		/**
		 * Letter has been found
		 */
	    $('body').animate({
	        scrollTop: $td.offset().top
	    }, 500, function(){
	    	$td.addClass('blink-warning');

	    	setTimeout(function(){
	    		$td.removeClass('blink-warning');
	    	}, 2000);
	    });
	}else{

		if($cont.hasClass('all-loaded')){

			return false;
		}

		console.log('Scrolling...');
		/**
		 * Letter not found so scroll to last tr and generate more data.
		 */

		$('html').animate({
		    scrollTop: $('table tbody tr:last-child td', $cont).offset().top,
		}, 500, 'swing',
		function(){
			console.log('Reloading..');
			tblAjaxLoad($cont, function(){

				/**
				* Reload again till get the letter of choice.
				*/
				ajaxTblLetterBtn($btn);
			}, function(){

				ajaxTblLetterBtn($btn);
			});
		});

	}
};

var ajaxTblLetterPaginate = function() {

	$('.btn-letter-paginate .btn').on('click', function(e){
		e.preventDefault();
		console.log('Letter clicked..');
		ajaxTblLetterBtn($(this));
	});

}

var salesRepProspectTd = function(k, row) {

	if(k == 'name'){
		var url = laroute.route('admin.prospects.show', { customer_id : row.id });
		var html = '<a href="' + url + '">' + row.name + '</a>';
		var attr = {'data-letter': row.lastname.charAt(0)};

		return {html: html, attr: attr};
	}

	return '';
};

var messageTblTd = function(k, row) {
	var html = '';
	var attr = {};
	var showUrl = laroute.route('admin.messages.show', {thread_id: row.id, message_id: row.last_message.id});

	if(row.thread_status == 'draft')
	{
		showUrl = laroute.route('admin.messages.draft.continue', {thread_id: row.id, message_id: row.last_message.id});
	}

	if(k === 'checkbox'){
		html = '<input type="checkbox" class="i-checks" name="msg-id[]" value="' + row.id + '" />';
		attr = {class: 'check-mail'};

	}else if(k === 'content'){
		attr = {class: 'mail-subject'};
	 	html = '<a href="' + showUrl + '">' + row.last_message.body_excerpt + '</a>';

	}else if(k === 'time'){
		html = row.last_message.created_at_human;
		attr = {class: 'text-right mail-date'};

	}else if(k === 'sender'){
		attr = {class: 'mail-ontact'};
		html = '<a href="' + showUrl + '">' + row.last_message.sender_name + '</a><span class="label label-' + row.type.badge + ' pull-right">' + row.type.name + '</span>';
	}

	if(row.is_unread){
		attr.class = attr.class + ' unread';
	}

	return {html: html, attr: attr};

};

var messageAfterAppend = function()
{
	iCheckfield();

	$('body.adminmessages table .i-checks, body.adminmessagesdraft table .i-checks, body.adminmessagessent table .i-checks').on('ifChanged', function(event){
		messageTblListDeleteIds(this);
	});

};

var fieldAutoComplete = function()
{
	$('input[type="text"].field-autocomplete').each(function(i, elem){
		var $this = $(this);
		var url = $this.attr('data-url');
		$this.autocomplete({
		  	source: function( request, response ) {
			    $.ajax( {
						url: url,
						dataType: "jsonp",
						data: {
						term: request.term
					},
					success: function( data ) {
						response( data );
					}
			    });
		  	},
			messages: {
				noResults: '',
				results: function() {}
			},
		  	minLength: 2,
			select: function( event, ui ) {
				console.log("Selected: " + ui.item.value + " aka " + ui.item.id );
			}
		} );
	});
};

var dealerModalFindSelectPopulateCat = function(){
	var $modal = $('.modal.modal-appstoragecrudcustomfieldsatselectdealermodal');
	var $select = $('select', $modal);

	var $option = $("<option value=''>Select category...</option>");
	$select.append($option);

	$.ajax({
		url: laroute.route('admin.api.dealers.categories'),
		dataType: 'json',
		type: 'get',
	   	success: function(data) {
	   		var html = '';
	   		$.each(data.data, function(i, row){
	   			var $option = $("<option></option>");
	   			$option.text(row.name);
	   			$option.attr('value', row.id);
	   			$select.append($option);
	   		});

	   	}
	});
};

var dealerModalFindSelect = function(field_name, dealer_id, dealer_name){
	var $field = $('input[name="' + field_name + '"]');
	var $cont = $field.closest('.form-group');

	$('.modal.modal-appstoragecrudcustomfieldsatselectdealermodal').modal('hide');
	$field.val(dealer_id);
	$('.dealer-name', $cont).text(dealer_name);
};

var dealerModalShowSubmit = function()
{
	var url = laroute.route('api.v1.dealers', {limit: -1});
	var $submit = $('.modal.modal-appstoragecrudcustomfieldsatselectdealermodal .btn-submit');
	$submit.on('click', function(){
		var $modal = $(this).closest('.modal');
		var accessToken = $modal.attr('data-access-token');
		var data = {access_token: accessToken};
		var fieldZip = $('input.field-zip', $modal).val();
		var $resultTxtCount = $('.result-text .count', $modal);
		var $dealersUl = $('ul.dealers', $modal);
		var fieldName = $modal.attr('data-field-name');
		var category = $('select.field-category', $modal).val();

		$modal.removeClass('has-result');

		$('li', $dealersUl).remove();

		if(fieldZip && fieldZip != ''){
			data.zip = fieldZip;
		}

		if(category && category != ''){
			data.category = category;
		}

		if(fieldZip == '')
		{
			$.gritter.add({
				title: 'Form error!',
				text: 'Zip field is required.',
				time: 8000,
				class_name: 'gritter-danger'
			});

			return false;
		}

		if(category == '')
		{
			$.gritter.add({
				title: 'Form error!',
				text: 'Category field is required.',
				time: 8000,
				class_name: 'gritter-danger'
			});

			return false;
		}

		$.ajax({
			url: url,
			dataType: 'json',
			type: 'get',
			data: data,
			error: function(r) {
				var html = '';
				if(r.status == 422){

					html = jsonErrorToHtml(r.responseJSON);
				}else{

					html = 'Uknown error found.';
				}

				$.gritter.add({
					title: 'Form error!',
					text: html,
					time: 8000,
					class_name: 'gritter-danger'
				});
		   	},
		   	success: function(data) {
		   		var html = '';
		   		$resultTxtCount.text(data.length);
		   		$.each(data.data, function(i, row){
		   			var $li = $("<li></li>");
		   			var $span = $("<span></span>");
		   			var $btn = $('<button class="btn btn-primary btn-sm"></button>');
		   			$btn.attr("onclick", 'dealerModalFindSelect("' + fieldName + '", ' + row.id + ', "' + row.name + '")');
		   			$btn.text('Select');
		   			$span.text(row.name);
		   			$li.append($span);
		   			$li.append($btn);
		   			$dealersUl.append($li);
		   			$modal.addClass('has-result');
		   		});

		   		if(data.data.length == 0)
		   		{
					$.gritter.add({
						title: 'Form error!',
						text: 'Sorry, there are no dealers near you for your chosen category.',
						time: 8000,
						class_name: 'gritter-danger'
					});
		   		}

		   	}
		}).complete(function(){

		});

	});
};

var officeHoursFieldSetCheck = function(elem)
{
	var $cb = $(elem);
	var $tr = $cb.closest('tr');
	var $hours = $('.hours', $tr);
	if($cb.is(':checked')){
		$('select', $hours).prop('disabled', true);
	}else{
		$('select', $hours).prop('disabled', false);
	}
}

var officeHoursField = function()
{
	$('.field-hours-of-operations input[type="checkbox"]').each(function(){
		officeHoursFieldSetCheck(this);
	});

	$('.field-hours-of-operations input[type="checkbox"]').on('ifChecked', function(){
		officeHoursFieldSetCheck(this);
	});

	$('.field-hours-of-operations input[type="checkbox"]').on('ifUnchecked', function(){
		officeHoursFieldSetCheck(this);
	});
};

var ctaTblTd = function(k, row) {
// console.log(row);
	var html = '';
	var attr = {};
	var showUrl = laroute.route('admin.messages.cta.show', {thread_id: row.id, message_id: row.last_message.id});

	// if(row.thread_status == 'draft')
	// {
	// 	showUrl = laroute.route('admin.messages.draft.continue', {thread_id: row.id, message_id: row.last_message.id});
	// }

	if(k === 'checkbox'){
		html = '<input type="checkbox" class="i-checks" name="msg-id[]" value="' + row.id + '" />';
		attr = {class: 'check-mail'};

	}else if(k === 'content'){
		attr = {class: 'mail-subject'};
	 	html = '<a href="' + showUrl + '">' + row.last_message.body_excerpt + '</a>';

	}else if(k === 'time'){
		html = row.last_message.created_at_human;
		attr = {class: 'text-right mail-date'};

	}else if(k === 'sender'){
		attr = {class: 'mail-ontact'};
		html = '<a href="' + showUrl + '">' + row.last_message.sender_name + '<span class="label label-' + row.type.badge + ' pull-right">' + row.type.name + '</span></a>';
	}

	if(row.is_unread){
		attr.class = attr.class + ' unread';
	}

	return {html: html, attr: attr};

};


$(document).ready(function(){
	waypointInit();
	tblLoadMore();
	ajaxTblLetterPaginate();
	fieldAutoComplete();
	dealerModalFindSelectPopulateCat();
	dealerModalShowSubmit();
	officeHoursField();
});