$(document).ready(function() {
    
    //Active Left Menu
    $(".menu-item").removeClass("active");
    $(".campus").addClass("active");

    // DataTable
    if ($("#campusDT").length > 0)
    {
        $('#campusDT').DataTable({
            processing: true,
            serverSide: true,
            order: [[ 0, "desc" ]], //or asc 
            columnDefs: [{target: 0,visible: false,searchable: false}],
            ajax: "/admin/campus/list",
            columns: [
               { data: 'id' },
               { data: 'name' },
               { data: 'code' },
               { data: 'code_expire_date' },
               { data: 'action' },
            ]
         });
    }
});

function openCreateCampusModal(url) {
    $.ajax({
       url: url,
       dataType: 'json',
       processData: false,
       cache : false,
       success: function(responseObj) {
             if (responseObj.status == "error") {         
                swal("Error!", responseObj.message, "error");                
             } else if (responseObj.status == "success") {
                jQuery('#modal-add-campus').html(responseObj.view).modal('show', { backdrop: 'static' });
                $('#Addform').parsley();
             }
       },
       // A function to be called if the request fails. 
       error: function(jqXHR, textStatus, errorThrown) {
             var responseObj = jQuery.parseJSON(jqXHR.responseText);            
       }
    });    
}

function AddCampusRow()
{
$('#CampusArray').append('<div class="row mb-3 new-div"><div class="col-md-10"><label class="form-label" for="name"></label><input type="text" class="form-control" name="name[]" placeholder="" value="" required /></div><div class="col-md-2"><a href="javascript:void(0)" class="btn btn-sm btn-outline-danger btn-fw deleteRow" style="margin-top:33px;">Delete</a></div></div>');
}

$(document).on("click",".deleteRow",function(e) {
    e.preventDefault();
       $(this).closest('.new-div').remove();
});


$(document).on("submit", "#Addform", function(event) {
    event.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        data: $(this).serialize(),
        dataType: "json",
        beforeSend: function() {
            $('.submitBtn').text('Please wait...');
            $('.submitBtn').prop('disabled', true);
        },
        success: function(responseTxt, textStatus, jqXHR) {
            if (textStatus == "success") {
                $('.closeBtn').trigger('click');
                $('#campusDT').DataTable().ajax.reload();
            } else if (textStatus == "error") {
            }
        },
        error: function(error) {
            $('.submitBtn').text('Submit');
            $('.submitBtn').prop('disabled', false);
        }
    });
});

function deleteCampus(elemObj, url) {
    
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
                    swal("Deleted!", "Campus has been deleted.", "success");
                    $('#campusDT').DataTable().ajax.reload();
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





