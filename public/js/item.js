$(document).ready(function () {
    $('#itable').DataTable({
        ajax:{
            url:"/api/item",
            dataSrc:""
        },
        dom: 'Bftrip',
        buttons:
        [
            'pdf',
            'excel',
                {
                    text: 'Add Item',
                    className: 'btn btn-primary',
                    action: function ( e, dt, node, config )
                    {
                        $("#iform").trigger("reset");
                        $('#itemModal').modal('show');
                    }
                    
                }
        ],
        columns:[
            columns: [
                { data: null,
                        render: function (data, type, row) {
                            console.log(data.imagePath)
                            return `<img src="/storage/app/public/images/${data.imagePath}" width="50" height="60">`;
                        }
                },
            {data: "item_id"},
            {data:'description'},
            {data:'sell_price'},
            {data:'cost_price'},
            {data: null,
                render: function (data, type, row) {
                    return "<a href='#' data-bs-toggle='modal' data-bs-target='#editModal' id='editbtn' data-id=" +
                        id +data.item_id+"><i class='fa fa-pencil' aria-hidden='true' style='font-size:24px' ></a></i><a href='#'  class='deletebtn' data-id=" +
                        id +data.item_id+"><i  class='fa fa-trash' style='font-size:24px; color:red' ></a></i>";
                }
                }
        ], //end datatable
});



// $("#ItemSubmit").on("click", function (e) {
//     e.preventDefault();
//     var data = $("#iform").serialize();
//     console.log(data);
//     let formData = new FormData($('#iform')[0])

//     console.log(formData);
//     for(var pair of formData.enteries()){
//         console.log(pair[0]+ ',' + pair[1]);

//     }
//     $.ajax({
//         type: "POST",
//         url: "/api/item",
//         data: formData,
//         headers: {
//             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//         },
//         dataType: "json",
//         success: function (data) {
//             console.log(data);
//             $("myModal").modal("hide");
//             var $itable = $('#itable').DataTable();
//             $itable.row.add(data).draw(false);
//         },
//         error: function (error) {
//             console.log(error);
//         },
//     });
// });
});
