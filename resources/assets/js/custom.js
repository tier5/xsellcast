var iCheckfield = function()
{
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });	

};

var radioIcheckHtml = function(value, name, label)
{
	return '<div class="checkbox i-checks"><label> <input type="radio" name="'+name+'" value="'+value+'"> <i></i> '+label+' </label></div>';
};

/**
 * Concert json response to listed HTML.
 *
 * @param Object array
 */
var jsonErrorToHtml = function(jsonResp)
{
	var html = '<ul>';
	$.each( jsonResp, function( key, value ) {
		html = html + '<li>' + value + '</li>';
	});

	return html + '</ul>';
};

/**
 * Request ajax and show success and errors.
 *
 * @param      String  url       The url
 * @param      Array   frm_data  The form data
 */
var simpleAjaxRq = function(url, frm_data, is_valid, success_callback, callback_param, method, param)
{
	
	if(is_valid === null || is_valid === undefined){
		is_valid = true;
	}

	if(method === null || method === undefined){
		method = 'POST';
	}

	var successMsg = null;
	if(param.success_msg !== null && param.success_msg !== undefined){
		successMsg = param.success_msg;
	}

	if(is_valid){
		addBodyProcessing();

		$.ajax({
			url: url,
			data: frm_data,
			dataType: 'json',
			type: method,
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

		   		if(successMsg){
					$.gritter.add({
						text: successMsg,
						time: 3000
					});
				}
				
				if (typeof success_callback === "function") {
					success_callback(callback_param, data);
				}

		   	}
		}).complete(function(){
			rmBodyProcessing();
		});		
	}

}

var addBodyProcessing = function()
{
	$('body').append('<div id="body-processing"><span><i class="fa fa-refresh"></i> Processing...</span></div>')
};

var rmBodyProcessing = function()
{
	$('#body-processing').remove();
}

var registerFieldWizard = function()
{
	$(document).ready(function () {
	  	var navListItems = $('div.setup-panel div a'),
	          allWells = $('.setup-content'),
	          allNextBtn = $('.nextBtn');

	  	allWells.hide();

		navListItems.click(function (e) {
			e.preventDefault();
			var $target = $($(this).attr('href')),
				$item = $(this),
				$container = $item.closest('.stepwizard-step');

			$('.stepwizard-step').removeClass('active');

			if (!$item.hasClass('disabled') && !$item.hasClass('open-done')) {
				navListItems.removeClass('btn-primary').addClass('btn-default');
				$item.addClass('btn-primary');
				allWells.hide();
				$target.show();
				$target.find('input:eq(0)').focus();
				$container.addClass('active');
			}
		});

		/**
		 * @param      {Object}   inputs     The inputs
		 * @param      {Object}   next_step  The next step
		 * @param      {boolean}  is_valid   Indicates if valid
		 * @return     {boolean}  { description_of_the_return_value }
		 */
		var triggerNextStep = function(inputs, next_step, is_valid)
		{
	      	$(".form-group").removeClass("has-error");
	      	for(var i=0; i<inputs.length; i++){
	          	if (!inputs[i].validity.valid){
	            	is_valid = false;
	              	$(inputs[i]).closest(".form-group").addClass("has-error");
	          	}
	      	}

	        return is_valid;
		};

		/**
		 * Callback - After success go to next step.
		 *
		 * @param void next_step The next step
		 */
		var successNextStep = function(next_step)
		{
			next_step.removeAttr('disabled').trigger('click');
		};

		var successDoneStep = function(next_step)
		{
			successNextStep(next_step);

			$('.register-page.setup-content').hide();
			$('#step-success').show();
		}

		allNextBtn.click(function(){
	      	var curStep = $(this).closest(".setup-content"),
	          	curStepBtn = curStep.attr("id"),
	          	nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
	          	curInputs = curStep.find("input[type='text'],input[type='url']"),
	          	isValid = true,
	          	$form = $(this).closest('form'),
	          	formData = $form.serialize(),
	          	url = $form.attr('action'),
	          	successMsg = $(this).attr('data-success-msg');

	        isValid = triggerNextStep(curInputs, nextStepWizard, isValid);
	        isDone = $(this).hasClass('doneBtn');

	        if($(this).hasClass('next-submit')){
	        	 
	        	if(isDone){
	        		simpleAjaxRq(url, formData, isValid, successDoneStep, nextStepWizard, null, {success_msg: successMsg});
	        	}else{
	        		simpleAjaxRq(url, formData, isValid, successNextStep, nextStepWizard, null, {success_msg: successMsg});	        		
	        	}
	        }
	  	});

		var totalSteps = $('div.setup-panel div a').length;
	  	var countDisabled = $('div.setup-panel div a.open-done').length; 

	  	$('.register-page.setup-content').eq(countDisabled - 1).show();
	  	$('.stepwizard-step').eq(countDisabled - 1).addClass('active');

	  	//trigger('click');
		//console.log(countDisabled);
	});
};

var registerSearchDealer = function()
{
	
	/**
	 * Add dealers listing
	 *
	 * @param      null  param   The parameter
	 * @param      Object  data    The data
	 */
	var populateDealers = function(param, data)
	{
		if(data.data.length > 0){
			$.gritter.add({
				text: 'Dealer found!',
				time: 3000
			});	

			var html = '<ul>';
			$.each(data['data'], function(k, v){
				html = html + '<li>' + radioIcheckHtml(v.id, 'dealer_id', v.name) + '</li>';
			});
			html = html + '</ul>';
			$('#register-dealer-list').empty();
			$('#register-dealer-list').append(html);

			if(data['data'].length > 0 ){
				$('#dealers-container').removeClass('hidden');
			}else{
				$('#dealers-container').addClass('hidden');
			}
	
			iCheckfield();

		}else{
			$.gritter.add({
				title: 'Error!',
				text: 'No dealer found!',
				time: 8000,
				class_name: 'gritter-danger'
			});			

			$('#dealers-container').addClass('hidden');
		}
	};

	$('.setup-content form.search-dealer').on('submit', function(e){
		e.preventDefault();
		var formData = $(this).serialize();
		var zipVal = $('#company_zip').val();
		var url = $(this).attr('action');

		if(zipVal != '')
		{
			simpleAjaxRq(url, formData, true, populateDealers, null, 'GET', {});			
		}else{
			$.gritter.add({
				title: 'Error!',
				text: 'Company zip code field is required.',
				time: 8000,
				class_name: 'gritter-danger'
			});
		}

	});

};

var scrollTo = function()
{
	$('.scrollto').on('click', function(){
		var $target = $($(this).attr('data-target'));

		if(!$target.hasClass('in')){
			setTimeout(function(){
			    $('html, body').animate({
			        scrollTop: $target.offset().top
			    }, 500);
			}, 1000);	
		}	
	});
}

var initTinyMce = function()
{
	$('textarea.tinymce-field').each(function(){
		var id = $(this).attr('id');
		var attr = {
			selector: 'textarea.tinymce-field',
			height: 200,
			menubar: false,
			toolbar: 'bold italic'
		};

		if($(this).is(':disabled')){
			attr.readonly = 1;
		}

		tinymce.init(attr);
	});
};

var offerSelectFieldClick = function(elem)
{
	var $btn = $(elem);
	var $modal = $btn.closest('#addLBTOfferModal');
	var url = $modal.attr('data-url');

	if(url != 0)
	{
		offerSelectFieldPopulate(url, $modal);
	}else{

		$('.load-more', $modal).addClass('hidden');
	}
}

var offerSelectField = function()
{
	$('#addLBTOfferModal').on('shown.bs.modal', function () {
		var url = laroute.route('admin.api.offers') + '?status=publish';
		var $modal = $(this);
		offerSelectFieldPopulate(url, $modal);
	});

	$('#addLBTOfferModal').on('hidden.bs.modal', function () {
		var $modal = $(this);
		$('ul.offer li', $modal).remove();
	});	
};

var offerSelectFieldPopulate = function(url, $modal)
{
	offerLoadAll(url, function(data){
		var html = '';
		var selectEvent = $modal.attr('data-select-event');
		$.each(data.data, function(k, row){

	        var template = document.getElementById('offer-select-field-item').innerHTML;
	        Mustache.parse(template);

	        //Render the data into the template
	        html = html + Mustache.render(template, {row:row, selectEvent: selectEvent});
		});
		$('.offer_table tbody', $modal).append(html);

		if(data.meta.pagination.links.next)
		{
			$modal.attr('data-url', data.meta.pagination.links.next);	
		}else{

			$modal.attr('data-url', 0);
		}
		

	})
};

var offerLoadAll = function(url, success_callback)
{
	$.ajax({
		url: url,
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
				title: 'Error loading data!',
				text: html,
				time: 8000,
				class_name: 'gritter-danger'
			});
	   	},
	   	success: function(data) {

			if (typeof success_callback === "function") {
				success_callback(data);
			}
	   	}
	}).complete(function(){

	});
};

var offerSelectItem = function(elem)
{
	var $li = $(elem);
	var $modal = $li.closest('.modal');
	var fieldName = $modal.attr('data-name');
	$modal.modal('hide');

	$('#' + fieldName).val($li.attr('data-id'));
	$('.offer_select span.selected_title').text($li.attr('data-name'));
	$('.offer_select span.selected_title').addClass('active');
	$('.offer_select .btn').removeClass('hidden');
};

var offerSelectItemRemoves = function(elem)
{
	var $cont = $(elem).closest('.offer_select');
	var $hidden = $('input[type="hidden"]', $cont);
	$(elem).addClass('hidden');
	$('.selected_title.active', $cont).removeClass('active');
	$hidden.val('');
};

var setGlobalTz = function()
{
	$.ajax({
		url: laroute.route('admin.api.option.set.tz'),
		dataType: 'json',
		type: 'post',
		data: {'tz': moment.tz.guess()},
		error: function(r) {
			var html = '';
			if(r.status == 422){
				
				html = jsonErrorToHtml(r.responseJSON);
			}else{

				html = 'Uknown error found.';
			}

			console.log('Setting timezone error: ' + html);
	   	},
	   	success: function(data) {

	   	}
	});	
};

var salesRepAgreementModal = function()
{
	$('#salesrepagreement.in').modal('show');
};

var setSidebarSetCount = function($li, url)
{

	$.ajax({
		url: url,
		dataType: 'json',
		type: 'GET',
	   	success: function(data) {
	   		if(data.count != 0)
	   		{
	   			var $lbl = $('> a span.label', $li);
	   			$lbl.text(data.count);
	   			$lbl.removeClass('hidden');
	   		}
	   	}
	});

};

var sidebarSetCounts = function()
{
	$('ul#side-menu li').each(function(){
		var $li = $(this);
		var $liChild = $('ul.nav-second-level', $li);

		if($li.attr('data-count-url'))
		{
			setSidebarSetCount($li, $li.attr('data-count-url'));
		}

		if($liChild.length > 0)
		{
			$liChild.each(function(){
				if($(this).attr('data-count-url')){
					setSidebarSetCount($(this), $(this).attr('data-count-url'));
				}
			});
		}
	});
}

var btnCtrlCollaps = function()
{
	$('.btn-control-collapsed').each(function(){
		var collapsedTxt   = $(this).attr('data-collapsed-text');
		var normalTxt      = $(this).text();
		var collapsedClass = $(this).attr('data-collapsed-class') + ' btn-control-collapsed';
		var normalClass    = $(this).attr('class');
		var href 		   = $(this).attr('href');	
		var $this 		   = $(this);

		$(this).attr('data-normal-class', normalClass);
		$(this).attr('data-normal-text', normalTxt);

		$(href).on('show.bs.collapse', function() {
		    $this.attr('class', collapsedClass);
		    $this.text(collapsedTxt);
		}).on('hide.bs.collapse', function() {
		    $this.attr('class', normalClass);
		    $this.text(normalTxt);
		});

	});
};

var beforeBrandAssignment = function(elem)
{
	var $item    = $(elem).closest('.ba-assignment-item');
	var k        = $item.attr('data-key');
	var val      = $("option:selected", elem).val();

	$(elem).attr('data-old-value', val);	
};

var brandAssignment = function(elem)
{
	var $item    = $(elem).closest('.ba-assignment-item');
	var k        = $item.attr('data-key');
	var txt      = $("option:selected", elem).attr('data-fullname');
	var val      =  $("option:selected", elem).val();
	var location = $("option:selected", elem).attr('data-location');
	var $btnYes  = $('.btn-yes', $item);

	if(val === ''){

		return false;
	}

	var $modal = $('#brandassignment_' + k).modal();

	$modal.modal('show');

	$('.btn-yes', $modal).attr('data-salesrep-id', val);
	$('.ba-name', $modal).text(txt);
	//$('.ba-name', $modal).text( '"' + txt + '"');
}

var changeProspectBa = function(elem, customer_id)
{
	var $btn = $(elem);
	var $modal = $btn.closest('.modal');
	var $m = $($btn.attr('data-target'));
	var salesrepId = $btn.attr('data-salesrep-id');

	$modal.addClass('disabled');

	$.ajax({
		url: laroute.route('admin.api.prospect.change_ba'),
		dataType: 'json',
		type: 'post',
		data: {salesrep_id: salesrepId, customer_id: customer_id},
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
	   		location.reload();
	   	}
	}).complete(function(){
		$modal.removeClass('disabled');
		//$m.modal('hide');
	});	
};

var prospectShowProfileSetSign = function()
{
	if($('.ba-assignment-item.has-assign-now-false-false').length > 0)
	{
		$('body').addClass('prospect-has-assign-now');
	}
}

var acceptLead = function(elem)
{
	var $this = $(elem);
	//var offerId = $this.attr('data-offer-id');
	var customerUserId = $this.attr('data-customer-user-id');
	var salesrepUserId = $this.attr('data-salesrep-user-id');

	$.ajax({
		url: laroute.route('admin.api.accept.lead'),
		dataType: 'json',
		type: 'post',
		data: {customer_user_id: customerUserId, salesrep_user_id: salesrepUserId},
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
	   		location.reload();	   		
	   	}
	}).complete(function(){

	});
};

var rejectLead = function(elem)
{
	var $this = $(elem);
	//var offerId = $this.attr('data-offer-id');
	var customerUserId = $this.attr('data-customer-user-id');
	var salesrepUserId = $this.attr('data-salesrep-user-id');
	var threadId = $this.attr('data-thread-id');

	$.ajax({
		url: laroute.route('admin.api.reject.lead'),
		dataType: 'json',
		type: 'post',
		data: {customer_user_id: customerUserId, salesrep_user_id: salesrepUserId, thread_id: threadId},
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
	   		window.location.href = laroute.route('admin.prospects.leads');		
	   	}
	}).complete(function(){

	});
};

var lbtClosePrint = function() {
	document.body.removeChild(this.__container__);
}

var lbtSetPrint = function() {
	this.contentWindow.__container__ = this;
	this.contentWindow.onbeforeunload = lbtClosePrint;
	this.contentWindow.onafterprint = lbtClosePrint;
	this.contentWindow.focus(); // Required for IE
	this.contentDocument.execCommand('print', false, null);
};

var printPage = function(sURL) {
	var oHiddFrame = document.createElement("iframe");
	oHiddFrame.onload = lbtSetPrint;
	oHiddFrame.style.visibility = "hidden";
	oHiddFrame.style.position = "fixed";
	oHiddFrame.style.right = "0";
	oHiddFrame.style.bottom = "0";
	oHiddFrame.src = sURL;
	document.body.appendChild(oHiddFrame);
};

var sideMenuDropTrigger = function()
{
	$('ul#side-menu > li.dropdown').on('touchstart', function(){
		var $li = $(this);
		$li.trigger('hover');
	});
};

var categoryDestroyConfirm = function(elem)
{
	var categoryId = $(elem).attr('data-id');
	var url = laroute.route('admin.categories.destroy.confirm', {category_id: categoryId});

	$('#category_delete_modal').remove();

	$.ajax({
		url: url,
		dataType: 'json',
		type: 'GET',
		error: function(r) {
			var html = '';
			messages = [];

			if(r.status == 422){
				
				messages = [r.responseJSON.error];
			}else{

				messages = ['Uknown error found.'];
			}

	        var template = document.getElementById('modal-okay').innerHTML;
	        Mustache.parse(template);

	        modal = Mustache.render(template, {messages: messages});

	        $('body').append(modal);
	        $('#category_delete_modal').modal();
	        $('#category_delete_modal').modal('show');
	   	},
	   	success: function(data) {
	        var template = document.getElementById('category-delete-modal').innerHTML;
	        Mustache.parse(template);
	        url = laroute.route('admin.categories.destroy', {category_id: data.data.id})
	        modal = Mustache.render(template, {url: url});

	        $('body').append(modal);
	        $('#category_delete_modal').modal();
	        $('#category_delete_modal').modal('show');
	   	}
	}).complete(function(){
	
	});	
};

var categoryUpdate = function(elem)
{
	var categoryId = $(elem).attr('data-id');
	var url = laroute.route('admin.categories.show', {category_id: categoryId});

	$('#category_edit_form_modal').remove();

	$.ajax({
		url: url,
		dataType: 'json',
		type: 'GET',
		error: function(r) {
			var html = '';
			messages = [];

			if(r.status == 422){
				
				messages = [r.responseJSON.error];
			}else{

				messages = ['Uknown error found.'];
			}

	        var template = document.getElementById('modal-okay').innerHTML;
	        Mustache.parse(template);

	        modal = Mustache.render(template, {messages: messages});

	        $('body').append(modal);
	        $('#category_edit_form_modal').modal();
	        $('#category_edit_form_modal').modal('show');
	   	},
	   	success: function(data) {
	   		var form = $('form', $($.parseHTML(data.data.form)[0]));
	        var template = document.getElementById('category_edit_form_mustache').innerHTML;
	        Mustache.parse(template);
	        modal = Mustache.render(template);

	        $('body').append(modal);
	        $('#category_edit_form_modal .modal-body').append(form);
	        $('#category_edit_form_modal').modal();
	        $('#category_edit_form_modal').modal('show');
	   	}
	}).complete(function(){
	
	});	
};

var messageTblListDeleteIds = function(elem)
{	
	var name = $(elem).attr('name');
	var ids = [];
	$.each($('input[name="' + name + '"]:checked'), function(k, dom){
		var v = $(dom).attr('value');
		ids.push(v);
	});

	$('body').attr('data-msg-delete-ids', ids);
};

var triggerMessageListDelete = function(elem)
{
	var strIds = $('body').attr('data-msg-delete-ids');
	var redirect = $(elem).attr('data-redirect');

	if(!strIds)
	{
		return false;
	}

	var ids = strIds.split(',');
	var url = laroute.route('admin.messages.delete.multi', {thread_id: ids, redirect_to: redirect});

	$.ajax({
		url: url,
		dataType: 'json',
		type: 'GET',
		error: function(r) {
			var html = '';
			messages = [];

			if(r.status == 422){
				
				messages = [r.responseJSON.error];
			}else{

				messages = ['Uknown error found.'];
			}
	   	},
	   	success: function(data) {

	   		window.location.href = data.data.url;
	   	}
	}).complete(function(){
	
	});	
};

var messageDeleteListModal = function()
{
	var strIds = $('body').attr('data-msg-delete-ids');

	if(!strIds)
	{
		return false;
	}	

	$('#deleteMessageListModal').modal();
	$('#deleteMessageListModal').modal('show');
};

var formValidationSubmit = function()
{
	var $forms = $('form[data-valdiation-url]');

	$forms.on('submit', function(e){

		tinyMCE.triggerSave();

		var url = $(this).attr('data-valdiation-url');
		var $form = $(this);
		var data = {};

		$('[name]', $form).each(function(){
			var name = $(this).attr('name');
			var val = $(this).val(); 
			data[name] = val;

			if($(this).is('button'))
			{
				$('<input />').attr('type', 'hidden').attr('name', name).attr('value', val).appendTo($form);
			}
		});

		if($form.hasClass('validated'))
		{
			return true;
		}

		e.preventDefault();		

		simpleAjaxRq(url, $.param(data), true, function(){
			$form.addClass('validated');
			$form.trigger('submit');
		}, {form: $form}, 'POST', {});


	});
};

registerFieldWizard();
setGlobalTz();
$(document).ready(function () {
	iCheckfield();
	initTinyMce();
	scrollTo();
	offerSelectField();
	salesRepAgreementModal();
	sidebarSetCounts();	
	registerSearchDealer();
	btnCtrlCollaps();
	sideMenuDropTrigger();
	formValidationSubmit();
	//brandAssignment();
		
});