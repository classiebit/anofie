

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// delete account
function accountDelete() {
    var title       = "{{action_delete}}";
    var text        = "{{settings_delete_account}}";
    var data        = {
        csrf_token      : csrf_token,
    };
    var url         = '/settings/delete_account';

    axiosAlert(title, text, data, url);
}

$(function(){

    // Prepare the preview for profile picture
    $("#wizard-picture").change(function(){
        readURL(this);
    });

})