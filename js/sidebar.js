/*==============================================================
   Appen To Button
/*==============================================================*/
jQuery( document ).ready(function($) {
jQuery('.sidebar-multiple-sidebar-custom .widgets-sortables').append('<div class="widdelet"><a href="" style="display:block;" class="wid_del"><i class="dashicons dashicons-dismiss" style="text-decoration:none"></a></div>');
jQuery(".inwidget").click(function() {
    jQuery(".inwidget").removeClass("error_wid");
});
jQuery(".button-primary").click(function() {  
var widget_name = jQuery(".inwidget").val();
var din = true;
/*==============================================================
   Add Validation
/*==============================================================*/
if( widget_name=='' ) {
    jQuery(".inwidget").addClass("error_wid");
    din=false;
}
else {
    var digit = (widget_name.length);
    if( digit==2 ) {
        jQuery(".inwidget").addClass("error_wid");
        din=false;
    }
    var spcwid = (widget_name.split(" ").length - 1);
	if( digit==spcwid ){
		jQuery(".inwidget").addClass("error_wid");
		din=false;  
	}
}
/*==============================================================
   Appen Delete Option
/*==============================================================*/
if( din==false ) {
    event.preventDefault();
}

});
var value = jQuery("object_name").val();
jQuery(".widdelet").click(function() {
event.preventDefault();
if (confirm('Are you sure you want to delete this?')) {
	var uniq=jQuery( this ).closest( ".widgets-sortables" ).attr( "id" );
	jQuery(this).closest(".widgets-holder-wrap").hide();

    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: {
            action: 'ams_delete_sidebar',
            id: uniq
        },
        success: function( data ) {
            
        }
    });
}
 
});
});

