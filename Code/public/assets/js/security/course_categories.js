$(document).ready(function() {
    
    //Active Left Menu
    $(".menu-item").removeClass("active");
    $(".course-cateory").addClass("active");

    // DataTable
    if ($("#courseCategoryDT").length > 0)
    {
        $('#courseCategoryDT').DataTable({
            processing: true,
            serverSide: true,
            order: [[ 0, "asc" ]], //or asc 
            columnDefs: [{target: 0,visible: false,searchable: false}],
            ajax: "/coursecategory/list",
            columns: [
                { data: 'id' },
               { data: 'title' },
               { data: 'status' },
               { data: 'action' },
            ]
         });
    }
});

function openCreateCourseCategoryModal(url) {    
    $.ajax({
        url: url,
        dataType: 'json',
        processData: false,
        cache : false,
        success: function(responseObj) {
            console.log(responseObj);
              if (responseObj.status == "error") {   
                 swal("Error!", responseObj.message, "error");                   
              } else if (responseObj.status == "success") {
                 jQuery('#modal-add-course-category').html(responseObj.view).modal('show', { backdrop: 'static' });
                 $('#AddCategoryform').parsley();
              }
        },
        // A function to be called if the request fails. 
        error: function(jqXHR, textStatus, errorThrown) {
              var responseObj = jQuery.parseJSON(jqXHR.responseText);            
        }
     });    
}

$(document).on("submit", "#AddCategoryform", function(event) {
    event.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        data: new FormData(this),
        contentType:false,
        processData:false,
        dataType: "json",
        beforeSend: function() {
            $('.submitBtn').text('Please wait...');
            $('.submitBtn').prop('disabled', true);
        },
        success: function(responseTxt, textStatus, jqXHR) {
            if (responseTxt.status == "success") {
                $('.closeBtn').trigger('click');
                $('#courseCategoryDT').DataTable().ajax.reload();
            } else if (responseTxt.status == "error") {
                $('#ErrorDiv').removeClass('hide');
                $('#errorMsg').text(responseTxt.message);
                $('.submitBtn').text('Submit');
                $('.submitBtn').prop('disabled', false);
            }            
        },
        error: function(error) {
            $('.submitBtn').text('Submit');
            $('.submitBtn').prop('disabled', false);
        }
    });
});

function deleteSystemUser(elemObj, url) {
    
    var token = $('meta[name="csrf-token"]').attr('content');

    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
      },
      function(){
        $.ajax({
            url: url,
            type: 'POST',
            data: { '_method': 'delete', '_token': token }, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            dataType: "json",
            success: function(responseTxt, textStatus, jqXHR) {
                if (responseTxt.status == "success") {
                    swal("Deleted!", "System user has been deleted.", "success");
                    $('#courseCategoryDT').DataTable().ajax.reload();
                } else if (responseTxt.status == "error") {
                    swal("Error!", responseTxt.message, "error");               
                }
            },
            error: function(error) {
                console.log(error)
                var responseObj = jQuery.parseJSON(error.responseText);                
            }
        });        
      });

}





