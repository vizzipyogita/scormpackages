var selectedUserIdsArr = [];
$(document).ready(function() {
    
    //Active Left Menu
    $(".menu-item").removeClass("active");
    $(".systemusers").addClass("active");

    // DataTable
    if ($("#systemUserDT").length > 0)
    {
        $('#systemUserDT').DataTable({
            processing: true,
            serverSide: true,
            order: [[ 0, "desc" ]], //or asc 
            columnDefs: [{target: 0,visible: false,searchable: false}],
            ajax: "/users/list?organizationId="+$('#organization_id').val(),
            columns: [
               { data: 'id' },
               { data: 'name' },
               { data: 'role' },
               { data: 'status' },
               { data: 'action' },
            ]
         });
    }

    // DataTable
    if ($("#importUserDT").length > 0)
    {
        $('#importUserDT').DataTable({

         });
    }
});

function openCreateSystemUserModal(url) {
    $.ajax({
       url: url,
       dataType: 'json',
       processData: false,
       cache : false,
       success: function(responseObj) {
             if (responseObj.status == "error") {   
                swal("Error!", responseObj.message, "error");                   
             } else if (responseObj.status == "success") {
                jQuery('#modal-add-systemuser').html(responseObj.view).modal('show', { backdrop: 'static' });
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
                $('#systemUserDT').DataTable().ajax.reload();
            } else if (responseTxt.status == "error") {
                if(responseTxt.message == 'License Error'){
                    location.reload();
                }else{
                    $('#ErrorDiv').removeClass('hide');
                    $('#errorMsg').text(responseTxt.message);
                    $('.submitBtn').text('Submit');
                    $('.submitBtn').prop('disabled', false);
                }
                
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
                    if(responseTxt.message == 'License Available'){
                        location.reload();
                    }else{
                        swal("Deleted!", "System user has been deleted.", "success");
                        $('#systemUserDT').DataTable().ajax.reload();
                    }
                    
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

function openChangePasswordModal(url) {
    $.ajax({
       url: url,
       dataType: 'json',
       processData: false,
       cache : false,
       success: function(responseObj) {
             if (responseObj.status == "error") {   
                swal("Error!", responseObj.message, "error");                   
             } else if (responseObj.status == "success") {
                jQuery('#modal-change-password').html(responseObj.view).modal('show', { backdrop: 'static' });
                $('#ChangePassword').parsley();
             }
       },
       // A function to be called if the request fails. 
       error: function(jqXHR, textStatus, errorThrown) {
             var responseObj = jQuery.parseJSON(jqXHR.responseText);            
       }
    });    
}

$(document).on("submit", "#ChangePassword", function(event) {
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

function openImportUserModal(url) {
    $.ajax({
       url: url,
       dataType: 'json',
       processData: false,
       cache : false,
       success: function(responseObj) {
             if (responseObj.status == "error") {   
                swal("Error!", responseObj.message, "error");                   
             } else if (responseObj.status == "success") {
                jQuery('#modal-import-systemuser').html(responseObj.view).modal('show', { backdrop: 'static' });
                $('#Importform').parsley();
             }
       },
       // A function to be called if the request fails. 
       error: function(jqXHR, textStatus, errorThrown) {
             var responseObj = jQuery.parseJSON(jqXHR.responseText);            
       }
    });    
}

function getOrganizationUsers(elemObj){
    var organizationId = $(elemObj).val();
    window.location.href = "users?organizationId="+organizationId;
}

function sendUserLoginDetails(url) {
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url,
        type: 'POST',
        data: { '_token': token },
        dataType: "json",
        success: function(responseObj) {
            if (responseObj.status == "error") {   
                swal("Error!", responseObj.message, "error");                   
            }else if (responseObj.status == "success") {
                swal("Sent!", "Login Details sent to user.", "success");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var responseObj = jQuery.parseJSON(jqXHR.responseText);            
        }
    });

}

function selectUserForSendLogin(elemObj){
    var status = $(elemObj).prop('checked') ? 1 : 0;
    if(status == 1){
        selectedUserIdsArr.push($(elemObj).val());
        $('#selectedUserIds').val(selectedUserIdsArr);
    }else{
        selectedUserIdsArr = $.grep(selectedUserIdsArr, function(value) {
            return value != $(elemObj).val();
        });
        $('#selectedUserIds').val(selectedUserIdsArr);
    }
}

function sendMultipleUsersLoginDetails(url){
    var userIds = $('#selectedUserIds').val();
    if(userIds !=''){
        $.ajax({
            url: url,
            type: 'POST',
            data: { '_method': 'post', '_token': $('meta[name="csrf-token"]').attr('content'), 'userIds': userIds},
            dataType: "json",
            success: function(responseObj) {
                if (responseObj.status == "error") {   
                    swal("Error!", responseObj.message, "error");                   
                }else if (responseObj.status == "success") {
                    swal("Sent!", "Login Details sent to user.", "success");
                    $('#systemUserDT').DataTable().ajax.reload();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                var responseObj = jQuery.parseJSON(jqXHR.responseText);            
            }
        });
    }else{
        swal("Error!", "Select atleast one user", "error"); 
    }
}