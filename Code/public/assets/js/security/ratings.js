$(document).ready(function() {
    
    //Active Left Menu
    $(".menu-item").removeClass("active");
    $(".ratings").addClass("active");

    // DataTable
    if ($("#ratingsDT").length > 0)
    {
        $('#ratingsDT').DataTable({
            processing: true,
            serverSide: true,
            order: [[ 0, "desc" ]], //or asc 
            columnDefs: [{target: 0,visible: false,searchable: false}],
            ajax: "/ratings/list",
            columns: [
                { data: 'id' },
                { data: 'comment' },
                { data: 'status' },
            ]
         });
    }
});

function updateRatingStatus(url, isActive){
    var token = $('meta[name="csrf-token"]').attr('content');

    swal({
        title: "Are you sure?",
        text: "To change rating status",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
      },
      function(){
        $.ajax({
            url: url,
            type: 'POST',
            data: { '_method': 'post', '_token': token, 'is_active': isActive }, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            dataType: "json",
            success: function(responseTxt, textStatus, jqXHR) {
                if (responseTxt.status == "success") {
                    swal("Updated!", "Status Updated.", "success");
                    $('#ratingsDT').DataTable().ajax.reload();
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
