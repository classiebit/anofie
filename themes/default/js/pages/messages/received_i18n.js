// get received
var rm_offset    = 0;
var rm_more      = 1;
var rm_type      = 'r';
function getReceivedMessages(refresh) {
    if(refresh == 1) {
		rm_offset = 0;
		rm_more   = 1;
		$("#received_messages #received_messages_items").html('');
	}  

	if(rm_more == 1) {
        axios.get('/messages/get_received_messages/'+rm_type+'/'+rm_offset)
        .then(function (res) {
            var response = res.data.data;
            
            // append response to lists
            $("#received_messages #received_messages_items").append(response.view);
            // Tooltip
            $('[data-toggle="tooltip"]').tooltip();
	        rm_offset     = response.offset;
            rm_more       = response.more;

            if(rm_more != 1)
            	$('#load_more_loader').text('');
        })
        .catch(function (error) {});
    }
};

