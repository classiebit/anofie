/*
 * Author: DK
 * Description: Common index.js
 **/

var table;
var data;

$(function () {

  	"use strict";

  	data = {
  		'csrf_token' : csrf_token
  	}
                
    //datatables
    table = $('#table').DataTable({ 
        "dom"           : 'lBfrtip',
        "responsive"    : true,
        // "buttons"       : ['csv', 'excel', 'pdf'],
        "buttons"       : [],
        "processing"    : true, //Feature control the processing indicator.
        "serverSide"    : true, //Feature control DataTables' server-side processing mode.
        "order"         : [], //Initial no order.
        "ajax"          : {
            "url"   : site_url+uri_seg_1+'/'+uri_seg_2+'/ajax_list',
            "type"  : "POST",
            "data"  : data,
        },                      // Load data for the table's content from an Ajax source
        "columnDefs": [{ 
            "targets": [ 0, -1 ], //first & last column
            "orderable": false, //set not orderable
        }],
        "language": {
            "paginate": {
                "previous": '<i class="fas fa-angle-left"></i>',
                "next": '<i class="fas fa-angle-right"></i>',
            }
        }
    });
 
    
    
 });

// Status Update
function statusUpdate(status, id) {
    if(status.checked)  status = 1;
    else                status = 0;


    var data        = {
        csrf_token      : csrf_token,
        status          : status,
        id              : id,
    };
    var formData = toFormData(data);
    var url      = '/'+uri_seg_1+'/'+uri_seg_2+'/status_update';
    axios.post(url, formData)
    .then(function (res) {
        var response = res.data.data;

        if(res.data.flag == 1) {
            showToast('success', res.data.msg);
            setTimeout(function () {
                table.ajax.reload( null, false ); // user paging is not reset on reload
            }, 500);    
        }
    })
    .catch(function (error) {});
}