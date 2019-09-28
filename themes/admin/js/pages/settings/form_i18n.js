// institute logo
function readURL(input, i_id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {$(i_id).attr('src', e.target.result);}
        reader.readAsDataURL(input.files[0]);
    }
};
$("#site_logo").change(function(){readURL(this, '#i_site_logo');});