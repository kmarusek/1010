jQuery(document).ready(function(){
  
  jQuery( ".OtherEvents-select--type" ).change(function() {
  var selector =jQuery(".OtherEvents-select--type option:selected" );

  if(selector.text() == 'SpaceX'){
    jQuery(".SpaceX").show();
    jQuery(".Team").hide();
    jQuery(".all").hide();
    jQuery(".Party").hide();
  }
  if(selector.text() == 'Party'){
    jQuery(".Party").show();
    jQuery(".Team").hide();
    jQuery(".all").hide();
    jQuery(".SpaceX").hide();
  }
  if(selector.text() == 'Team'){
    jQuery(".all").hide();
    jQuery(".Team").show();
    jQuery(".Party").hide();
    jQuery(".SpaceX").hide();
  }
  if(selector.text() == 'Event Type'){
    jQuery(".all").show();
    jQuery(".Team").show();
    jQuery(".Party").show();
    jQuery(".SpaceX").show();
  }

});
var result = jQuery('.OtherEvents-banner').sort(function (a, b) {

var contentA =parseInt( jQuery(a).data('sort'));
var contentB =parseInt( jQuery(b).data('sort'));
return (contentA < contentB) ? -1 : (contentA > contentB) ? 1 : 0;
});

jQuery('.OtherEvents-date_order').html(result);

  });



  



