$(document).ready(function(){
	// checkPlugin();
	if(location.href.indexOf("#plugin-modal") > -1)
		$("#plugin-modal").modal("show");
	$("button").popover();
	if(localStorage){
		var theme = localStorage.getItem("theme");
		if(theme != null){
			if((theme.match(/,/g) || []).length != 3){
				localStorage.removeItem("theme");
			}else{
				theme = theme.split(",");
				$("#theme").attr("href", "/css/theme.css.php?r=" + theme[0] + "&g=" + theme[1] + "&b=" + theme[2] + "&a=" + theme[3]);
			}
		}
		
		if(localStorage.getItem("pers")){
			setSettings();
		}
	}
	$("button").on("shown.bs.popover", function(){
		$("#color-chooser a").click(function(){
			var theme = $(this).attr("data-rgba");
			if(localStorage){
				localStorage.setItem("theme", theme);
				location.href = "/";
			}
		});
	});
	
	$("#mobileFoki").change(function(){
		var fokus = $("#mobileFoki > option:selected").val();
		if(fokus == "angepasst")
			window.location = "./settings/";
		else
			window.location = "./?focus=" + fokus;//$("#mobileFoki > option:selected").val());
	});
	if($("fieldset#foki.mobile").length){
		$("fieldset#foki.mobile label#anpassen-label").click(function(){
			window.location = "./settings/";
		});
	}
	$("#anpassen-label").click(function(){
		window.location = "./settings/";
	});

	$(".cookie-box button").click(function(){
		if(localStorage)
		{
			localStorage.setItem("cookie", "no");
		}
	});
	if( localStorage && localStorage.getItem("cookie") == "no")
	{
		$(".cookie-box").addClass("hidden");
	}
});

/*
function checkPlugin(){
	if(isFirefox){
		$("#plugin-modal .modal-header h4").html("MetaGer zum Firefox hinzufügen");
		var content = "" +
				"<ol>" +
				"<li>Klicken Sie <a href=\"javascript:window.external.AddSearchProvider($('link[rel=search]').attr('href'))\">hier</a> um MetaGer als Suchmaschine hinzuzufügen.</li>" +
				"<li>Setzen Sie einen Haken bei \"Diese als aktuelle Suchmaschine setzen\"</li>" +
				"<li>Klicken Sie auf \"Hinzufügen\"</li>" +
				"</ol>";
		$("#plugin-modal .modal-body").html(content);
	}else if(isChrome){
		$("#plugin-modal .modal-header h4").html("MetaGer zum Chrome hinzufügen");
		var content = "" +
				"<ol>" +
				"<li>Klicken Sie <a href=\"javascript:window.external.AddSearchProvider($('link[rel=search]').attr('href'))\" onclick=\"$('#more').removeClass('hidden');\">hier</a>, um MetaGer als Suchmaschine hinzuzufügen" +
				"<ul id=\"more\" class=\"hidden list-unstyled\">" +
				"<li>Klicken Sie im Popup auf OK</li>" +
				"<li><small>(Sollte der OK-Knopf deaktiviert sein, ist MetaGer bereits in Ihren Suchmaschinen. Klicken Sie dann auf Abbrechen und fahren mit Schritt 2 fort.)</small></li>" +
				"</ul></li>" +
				"<li>Klicken in Ihrem Browser oben rechts auf <span class=\"glyphicon glyphicon-menu-hamburger\"></span></li>" +
				"<li>Wählen Sie \"Einstellungen\"</li>" +
				"<li>Im Bereich Suchen, klicken Sie auf \"Suchmaschinen verwalten\"</li>" +
				"<li>In der nun angezeigten Liste, finden Sie MetaGer. Fahren Sie mit der Maus über den Eintrag und klicken auf \"Als Standard festlegen\"</li>" +
				"</ol>";
		$("#plugin-modal .modal-body").html(content);
	}else if(isOpera){
		$("#plugin-modal .modal-header h4").html("MetaGer zum Opera hinzufügen");
		var content = "" +
		"<ol>" +
		"<li>Klicken Sie <a href=\"/\" target=\"_blank\">hier</a> um MetaGer in einem neuen Tab zu öffnen.</li>" +
		"<li>Im neuen Tab klicken Sie mit der rechten Maustaste auf das Sucheingabefeld in der Mitte der Seite.</li>" +
		"<li>Wählen Sie im Kontextmenü \"Suchmaschine erstellen...\"</li>" +
		"<li>Klicken Sie im Popup auf \"erstellen\"</li>" +
		"<li><small>(Leider ist es Suchmaschinen nicht mehr möglich im Opera als Standard eingestellt zu werden, aber <a href=\"https://www.mozilla.org/de/firefox/new/\" target=\"_blank\">hier</a> können Sie mit Firefox einen Open-Source Browser installieren, der dies unterstützt.)</small>" +
		"</ol>";
		$("#plugin-modal .modal-body").html(content);
	}else if(isIE && !isEdge){
		$("#plugin-modal .modal-header h4").html("MetaGer zum Internet Explorer hinzufügen");
		var content = "" +
			"<ol>" +
			"<li>Klicken Sie <a href=\"javascript:window.external.addSearchProvider($('link[rel=search]').attr('href'));\">hier</a>, um MetaGer als Suchmaschine hinzuzufügen" +
			"<li>Klicken Sie in Ihrem Browser oben rechts auf Extras (<span class=\"glyphicon glyphicon-cog\"></span>)</li>" +
			"<li>Wählen Sie den Menüpunkt \"Add-Ons verwalten\"</li>" +
			"<li>Klicken Sie im Bereich Add-On-Typen auf \"Suchanbieter\" und danach im rechten Bereich auf \"MetaGer\"</li>" +
			"<li>Wählen Sie unten den Knopf \"Als Standard\"</li>" +
			"</ol>";
		$("#plugin-modal .modal-body").html(content);
	}else if(isEdge){
		$("#plugin-modal .modal-header h4").html("MetaGer zum Microsoft Edge hinzufügen");
		var content = "" +
		"<ol>" +
		"<li>Klicken Sie in Ihrem Browser oben rechts auf Extras (<span class=\"glyphicon glyphicon-option-horizontal\"></span>) und wählen sie \"Einstellungen\"</li>" +
		"<li>Scrollen Sie nach unten und klicken auf \"Erweiterte Einstellungen anzeigen\"</li>" +
		"<li>Scrollen Sie erneut nach unten bis zu Punk \"In Adressleiste suchen mit\" und klicken Sie auf \"Ändern\"</li>" +
		"<li>Wählen Sie \"MetaGer: Sicher suchen & finden...\" und klicken Sie auf \"Als Standard\"</li>" +
		"</ol>";
		$("#plugin-modal .modal-body").html(content);
	}else{
		$("#plug").addClass("hidden");
		$(".seperator").addClass("hidden");
	}
}
*/

function setSettings(){
	for(var i = 0; i < localStorage.length; i++){
		var key = localStorage.key(i);
		var value = localStorage.getItem(key);
		if(key.startsWith("param_") && !key.endsWith("lang")){
			key = key.substring(key.indexOf("param_")+6);
			$("#searchForm").append("<input type=\"hidden\" name=\"" + key + "\" value=\"" + value + "\">");
		}
		$("#foki input[type=radio]#angepasst").attr("checked",true);
	}
	if($("fieldset#foki.mobile").length){
		$("fieldset.mobile input#bilder").val("angepasst");
		$("fieldset.mobile input#bilder").prop("checked", true);
		$("fieldset.mobile input#bilder").attr("id", "angepasst");
		$("fieldset.mobile label#bilder-label").attr("id", "anpassen-label");
		$("fieldset.mobile label#anpassen-label").attr("for", "angepasst");
		$("fieldset.mobile label#anpassen-label span.glyphicon").attr("class", "glyphicon glyphicon-cog");
		$("fieldset.mobile label#anpassen-label span.content").html("angepasst");
		console.log("test");
	}
}
//Polyfill for form attribute
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

// Opera 8.0+
var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
    // Firefox 1.0+
var isFirefox = typeof InstallTrigger !== 'undefined';
    // At least Safari 3+: "[object HTMLElementConstructor]"
var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
    // Internet Explorer 6-11
var isIE = /*@cc_on!@*/false || !!document.documentMode;
    // Edge 20+
var isEdge = !isIE && !!window.StyleMedia;
    // Chrome 1+
var isChrome = !!window.chrome && !!window.chrome.webstore;
    // Blink engine detection
var isBlink = (isChrome || isOpera) && !!window.CSS;