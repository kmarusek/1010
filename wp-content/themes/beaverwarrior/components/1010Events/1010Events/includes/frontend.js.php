(function($){
$(function(){ 

var options = {
  valueNames: [
    'categories',
    'title',
    { data: ['category']}
  ],
};




var TenTenEventsList = new List('TenTenEvents-list', options);

function resetList(){
  TenTenEventsList.search();
  TenTenEventsList.filter();
  TenTenEventsList.update();
  TenTenEventsList.filter();
  $(".filter-all").prop('checked', true);
  $('.filter').prop('checked', false);
  $('.search').val('');
  console.log('Reset Successfully!');
};

function updateList(){
  var values_category = $("#TenTenEvents-filter").val();
  console.log(values_category);

  TenTenEventsList.filter(function (item) {
    var categoryFilter = false;


    if(values_category == "all")
    { 
      categoryFilter = true;
    } else {
      categoryFilter = item.values().category == values_category;
    
    }
   
    return categoryFilter
  });
  TenTenEventsList.update();
  //console.log('Filtered: ' + values_gender);
}


$("#TenTenEvents-filter").change(updateList);



TenTenEventsList.on('updated', function (list) {
  if (list.matchingItems.length > 0) {
    $('.TenTenEvents-no-results').hide()
  } else {
    $('.TenTenEvents-no-results').show()
  }
});

});// End Isotope and Pagination JS
})(jQuery);


  



