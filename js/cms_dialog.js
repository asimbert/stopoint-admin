$(document).ready(function() {

   $("#dialog").dialog({
     autoOpen: false,
     modal: true
	});
		
  $(".confirm_link").click(function(e) {
alert('sdfs');
 	e.preventDefault();	
    var targetUrl = $(this).attr("href");

	$('#dialog').dialog('destroy');
	$('#dialog').show();

    $("#dialog").dialog({
		resizable: false,
					height:140,
					modal: true,
					overlay: {
						backgroundColor: '#000',
						opacity: 0.5
	 },
      buttons : {
        "Yes" : function() {
          window.location.href = targetUrl;
        },
        "No" : function() {
          $(this).dialog("close");
        }
      }

    });

	$("#dialog").dialog("open");

  });
});