

// get favourites
var f_offset    = 0;
var f_more      = 1;
var f_type 		= 'f';
function getFavorite(refresh) {
    if(refresh == 1) {
		f_offset = 0;
		f_more   = 1;
		$("#favorite #favorite_items").html('');
	}  

	if(f_more == 1) {
        axios.get('/messages/get_favorite_messages/'+f_type+'/'+f_offset)
        .then(function (res) {
            var response = res.data.data;
            
            // append response to lists
            $("#favorite #favorite_items").append(response.view);
            // Tooltip
            $('[data-toggle="tooltip"]').tooltip();
	        f_offset     = response.offset;
            f_more       = response.more;

            if(f_more != 1)
            	$('#load_more_loader').text('');
        })
        .catch(function (error) {});
    }
};

// get sent messages
var s_offset    = 0;
var s_more      = 1;
var s_type      = 's';
function getSentmessages(refresh) {
    if(refresh == 1) {
        s_offset = 0;
        s_more   = 1;
        $("#sent_messages #sent_messages_items").html('');
    }  

    if(s_more == 1) {
        axios.get('/messages/get_sent_messages/'+s_type+'/'+s_offset)
        .then(function (res) {
            var response = res.data.data;
            
            // append response to lists
            $("#sent_messages #sent_messages_items").append(response.view);
            // Tooltip
            $('[data-toggle="tooltip"]').tooltip();
	        s_offset     = response.offset;
            s_more       = response.more;

            if(s_more != 1)
            	$('#load_more_loader').text('');
        })
        .catch(function (error) {});
    }
};


// make favorite messages 
function messageFavorite(id) {
    
    var data        = {
        csrf_token      : csrf_token,
        id              : id, 
    };
    var formData = toFormData(data);
        
    axios.post('/messages/favorite', formData)
    .then(function (res) {
        var response = res.data.data;
        if(response.favorite == '1') {
            $('#fav'+id).removeClass('bg-white');
        } else {
            $('#fav'+id).addClass('bg-white');
        } 
    })
    .catch(function (error) {});
    
};

// message Report With Sweetalert
function messageReport(id, type) {
    var title       = "{{action_report}}";
    var text        = "{{sm_report_message}}";
    var data        = {
        csrf_token      : csrf_token,
        id              : id, 
    };
    var url         = '/messages/report';

    axiosAlert(title, text, data, url);
};

// message Delete With Sweetalert
function messageDelete(id, type, e) {
    var title       = "{{action_delete}}";
    var text        = "{{sm_delete_message}}";
    var data        = {
        csrf_token      : csrf_token,
        id              : id, 
    };
    var url         = (type === 'r' || type === 'f' ? '/messages/delete' : '/messages/delete/1' );

    axiosAlert(title, text, data, url);
};


function loadMore() {
    // Received: Messages
    if(parent_tab == 'received') {
        // Received: Messages
        if(child_tab == 'messages') getReceivedMessages(0);
    } 

    // Sent: Messages
    else if(parent_tab == 'sent') {
        // Sent: Messages
        if(child_tab == 'messages') getSentmessages(0);
    } 
    
    // Favorites
    else if(parent_tab == 'favorites') {
        getFavorite(0);
    } 
}

$(function() {
    loadMore();
});