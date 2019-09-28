var table_view = '';
$(function() {
	data = {
  		'csrf_token' : csrf_token,
        'q_id'       : $('input[name="q_id"]').val(),
  	}
                
    //datatables
    table_view = $('#table_view').DataTable({ 
        "dom"           : 'lBfrtip',
        "responsive"    : true,
        // "buttons"       : ['csv', 'excel', 'pdf'],
        "buttons"       : [],
        "processing"    : true, //Feature control the processing indicator.
        "serverSide"    : true, //Feature control DataTables' server-side processing mode.
        "order"         : [], //Initial no order.
        "ajax"          : {
            "url"   : site_url+uri_seg_1+'/'+uri_seg_2+'/ajax_list_view',
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

// Ajax Delete Answer
function ajaxDeleteAnswer(id, item_title, item) {
    var title       = "{{action_delete}} "+item;
    var text        = item_title;
    var data        = {
        csrf_token      : csrf_token,
        id              : id, 
    };
    var url         = '/'+uri_seg_1+'/messages/delete';

    axiosAlert(title, text, data, url);
}