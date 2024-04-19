$(document).ready(function() {
    //Active Left Menu
    $(".menu-item").removeClass("active");
    $(".plans").addClass("active");

    // DataTable
    if ($("#planDT").length > 0)
    {
        $('#planDT').DataTable({
            order: [[ 0, "desc" ]], //or asc 
            columnDefs: [{target: 0,visible: false,searchable: false}]
         });
    }
});


function openCreatePlanModal(url) {
   $.ajax({
      url: url,
      dataType: 'json',
      processData: false,
      cache : false,
      success: function(responseObj) {
            if (responseObj.status == "error") {   
               swal("Error!", responseObj.message, "error");                   
            } else if (responseObj.status == "success") {
               jQuery('#modal-add-plan').html(responseObj.view).modal('show', { backdrop: 'static' });
               $('#Addform').parsley();
            }
      },
      // A function to be called if the request fails. 
      error: function(jqXHR, textStatus, errorThrown) {
            var responseObj = jQuery.parseJSON(jqXHR.responseText);            
      }
   });    
}

function changePlanAmount(elemObj, url, type){
   var token = $('meta[name="csrf-token"]').attr('content');
   var amount = $(elemObj).val();
   var data = '';

   if(type == 'M'){
      var data = { '_method': 'post', '_token': token, 'monthly_plan_amount': amount };
   }else{
      var data = { '_method': 'post', '_token': token, 'yearly_plan_amount': amount };
   }

   $.ajax({
      url: url,
      type: 'POST',
      data: data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
      dataType: "json",
      success: function(responseTxt, textStatus, jqXHR) {
          if (responseTxt.status == "success") {
              swal("Updated!", "Updated.", "success");
          } else if (responseTxt.status == "error") {
              swal("Error!", responseTxt.message, "error");               
          }
      },
      error: function(error) {
          console.log(error)
          var responseObj = jQuery.parseJSON(error.responseText);                
      }
  }); 
}

function openViewPlansModal(url, isUserPlan=0) {
   $.ajax({
      url: url+"?isUserPlan="+isUserPlan,
      dataType: 'json',
      processData: false,
      cache : false,
      success: function(responseObj) {
            if (responseObj.status == "error") {   
               swal("Error!", responseObj.message, "error");                   
            } else if (responseObj.status == "success") {
               jQuery('#modal-view-plan').html(responseObj.view).modal('show', { backdrop: 'static' });
            }
      },
      // A function to be called if the request fails. 
      error: function(jqXHR, textStatus, errorThrown) {
            var responseObj = jQuery.parseJSON(jqXHR.responseText);            
      }
   });    
}