$(document).ready(function(){
	if( top != self ){
        	postSize($(document).height());
	}
	getDocumentReadyForUse();
});

function postSize(height){
	var target = parent.postMessage ? parent : (parent.document.postMessage ? parent.document : undefined);

    	if(typeof target != "undefined" && document.body.scrollHeight){
        	target.postMessage(height, "*");
        }
}

function tabs(){
	//return;
	$("#foki  a").each(function(){
		$(this).attr("href", "#"+$(this).attr("aria-controls"));
		$(this).attr("role","tab");
		$(this).attr("data-toggle","tab");
	});
	$("#foki a").off();
	$("#foki a").on("show.bs.tab", function(e){
		var fokus = $(this).attr("aria-controls");
		var link = $("#"+fokus+"TabSelector a").attr("data-href");
		if($("#"+fokus+"TabSelector").attr("data-loaded") != "1"){
			$.get(link, function(data){
				$("#"+fokus+"TabSelector").attr("data-loaded", "1");
				$("#"+fokus).html(data);
				$("input[name=focus]").val($("#foki li.active a").attr("aria-controls"));
				getDocumentReadyForUse();
			});
		}
		getDocumentReadyForUse();
	});
}

function getDocumentReadyForUse(){
	clickLog();
	popovers();
	imageLoader();
	//pagination();
	tabs();
	theme();
	fokiChanger();
}

function theme(){
	if(localStorage){
		var theme = localStorage.getItem("theme");
		if(theme != null){
			if((theme.match(/,/g) || []).length != 3){
				localStorage.removeItem("theme");
			}else{
				theme = theme.split(",");
				$($("head link")[2]).attr("href", "/css/theme.css.php?r=" + theme[0] + "&g=" + theme[1] + "&b=" + theme[2] + "&a=" + theme[3]);
			}
		}
	}
}

function clickLog(){
	$(".result a.title, .result div.link-link a").off();
	$(".result a.title, .result div.link-link a").click(function(){
		$.get("/clickstats", {i:$("meta[name=p]").attr("content"), s:$(this).attr("data-hoster"), q:$("meta[name=q]").attr("content"), p:$(this).attr("data-count"), url:$(this).attr("href")});
	});
}

function popovers(){
	$("[data-toggle=popover]").each(function(e){
			$(this).popover("destroy");
			$(this).popover({
				//html			:	true,
				//title			:	"<span class='glyphicon glyphicon-cog'></span> Optionen",
				content			:	$(this).parent().find(".content").html()
			});
	});
}

function pagination(){
	$(".pagination li:not(.active) > a").attr("href", "#");
	$(".pagination li.disabled > a").removeAttr("href");
	$(".pagination li:not(.active) > a").off();
	$(".pagination li:not(.active) > a").click(paginationHandler);
}
function paginationHandler(){
	var link = $(this).attr("data-href");
	if(link.length == 0){return;}
	var tabPane = $(".tab-pane.active");
	$(tabPane).html("<div class=\"loader\"><img src=\"/img/ajax-loader.gif\" alt=\"\" /></div>");
	$.get(link, function(data){
		$(tabPane).html(data);
		$(".pagination li:not(.active) > a").attr("href", "#");
		$(".pagination li.disabled > a").removeAttr("href");
		$(".pagination li:not(.active) > a").off();
		$(".pagination li:not(.active) > a").click(paginationHandler);
		getDocumentReadyForUse();
	});
}

function imageLoader(){
	if(typeof $("#container").masonry == "undefined"){
		return;
	}

	var $grid = $("#container").masonry(
			{
				columnWidth: 150,
				itemSelector: '.item',
				gutter: 10,
				isFitWidth: true
			}
		);
	$grid.imagesLoaded().progress(function(instance,image){$grid.masonry('layout');});
}

function eliminateHost(host){
	$(".result:not(.ad)").each(function(e){
		var host2 = $(this).find(".link-link > a").attr("data-host");
		if(host2.indexOf(host) === 0){
			$(this).css("display", "none");
		}
	});
}

function fokiChanger(){
	$("#fokiChanger ul > li").click(function(){
		document.location.href=$(this).attr("data-href");
	});
}
// Polyfill for form attribute
(function($) {
	  /**
	   * polyfill for html5 form attr
	   */
	  // detect if browser supports this
	  var sampleElement = $('[form]').get(0);
	  var isIE11 = !(window.ActiveXObject) && "ActiveXObject" in window;
	  if (sampleElement && window.HTMLFormElement && sampleElement.form instanceof HTMLFormElement && !isIE11) {
	    // browser supports it, no need to fix
	    return;
	  }
	  /**
	   * Append a field to a form
	   *
	   */
	  $.fn.appendField = function(data) {
	    // for form only
	    if (!this.is('form')) return;

	    // wrap data
	    if (!$.isArray(data) && data.name && data.value) {
	      data = [data];
	    }

	    var $form = this;

	    // attach new params
	    $.each(data, function(i, item) {
	      $('<input/>')
	        .attr('type', 'hidden')
	        .attr('name', item.name)
	        .val(item.value).appendTo($form);
	    });

	    return $form;
	  };

	  /**
	   * Find all input fields with form attribute point to jQuery object
	   * 
	   */
	  $('form[id]').submit(function(e) {
	    var $form = $(this);
	    // serialize data
	    var data = $('[form='+ $form.attr('id') + ']').serializeArray();
	    // append data to form
	    $form.appendField(data);
	  }).each(function() {
	    var form = this,
	      $form = $(form),
	      $fields = $('[form=' + $form.attr('id') + ']');

	    $fields.filter('button, input').filter('[type=reset],[type=submit]').click(function() {
	      var type = this.type.toLowerCase();
	      if (type === 'reset') {
	        // reset form
	        form.reset();
	        // for elements outside form
	        $fields.each(function() {
	          this.value = this.defaultValue;
	          this.checked = this.defaultChecked;
	        }).filter('select').each(function() {
	          $(this).find('option').each(function() {
	            this.selected = this.defaultSelected;
	          });
	        });
	      } else if (type.match(/^submit|image$/i)) {
	        $(form).appendField({name: this.name, value: this.value}).submit();
	      }
	    });
	  });


	})(jQuery);
