$(document).ready(function() {
    
    //Active Left Menu
    $(".menu-item").removeClass("active");
    $(".role").addClass("active");

    // DataTable
    if ($("#roleDT").length > 0)
    {
        $('#roleDT').DataTable({
            processing: true,
            serverSide: true,
            order: [[ 0, "desc" ]], //or asc 
            columnDefs: [{target: 0,visible: false,searchable: false}],
            ajax: "/admin/role/list",
            columns: [
               { data: 'id' },
               { data: 'name' },
               { data: 'action' },
            ]
         });
    }
});

function openCreateRoleModal(url) {
    $.ajax({
       url: url,
       dataType: 'json',
       processData: false,
       cache : false,
       success: function(responseObj) {
             if (responseObj.status == "error") {    
                swal("Error!", responseObj.message, "error");                  
             } else if (responseObj.status == "success") {
                jQuery('#modal-add-role').html(responseObj.view).modal('show', { backdrop: 'static' });
                $('#Addform').parsley();
             }
       },
       // A function to be called if the request fails. 
       error: function(jqXHR, textStatus, errorThrown) {
             var responseObj = jQuery.parseJSON(jqXHR.responseText);            
       }
    });    
}


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
            if (responseTxt.status == "success") {
                $('.closeBtn').trigger('click');
                $('#roleDT').DataTable().ajax.reload();
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

function deleteRole(elemObj, url) {
    
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
                    swal("Deleted!", "Role has been deleted.", "success");
                    $('#roleDT').DataTable().ajax.reload();
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


function selectAllCheckbox(elemObj)
{
    if(elemObj.checked){
        $('.checkboxall').each(function(){
            $(".checkboxall").prop('checked', true);
        })
    }else{
        $('.checkboxall').each(function(){
            $(".checkboxall").prop('checked', false);
        })
    }
}





