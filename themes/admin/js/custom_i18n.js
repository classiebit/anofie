
// Ajax Delete With Sweetalert
function ajaxDelete(id, item_title, item) {
    var title       = "{{action_delete}}";
    var text        = "{{action_delete}} "+item+" "+item_title;
    var data        = {
        csrf_token      : csrf_token,
        id              : id, 
    };
    var url         = '/'+uri_seg_1+'/'+uri_seg_2+'/delete';
    var redirect    = site_url+uri_seg_1+'/'+uri_seg_2;

    axiosAlert(title, text, data, url, redirect);
}

// read notification
function readNotification(noti_type) {
    var data        = {
        csrf_token      : csrf_token,
        n_type          : noti_type, 
    };
    var formData = toFormData(data);
    var url         = '/'+uri_seg_1+'/notifications/delete_notification';
    axios.post(url, formData)
    .then(function (res) {
        var response = res.data.data;

        if(res.data.flag == 1) {
            window.location.href = site_url+'admin/'+noti_type;
        }
    })
    .catch(function (error) {});
}

/**
 * Form Submit 
 * 
 **/

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#c_image').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
};

$("#image").change(function(){
    readURL(this);
});

$(function () {
    $('form#form-create').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        var url      = $(this).attr('action');

        axios.post(url, formData)
        .then(function (res) {
            var response = res.data.data;

            if(res.data.flag == 1) {   
                setTimeout(() => {
                    window.location.href = site_url+uri_seg_1+'/'+uri_seg_2;
                }, 500);
            }
            
        })
        .catch(function (error) {});
        
        return false;           
    });
});

$('.manage_acl_groups').on('change', function() {
    var group_select = $(this).val();

    setTimeout(function() {
        window.location = site_url+uri_seg_1+'/'+uri_seg_2+'/index/'+group_select;
    }, 500);
});