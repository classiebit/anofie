$(function(){

    // send message
    $('form#form-create').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        var url      = $(this).attr('action');

        axios.post(url, formData)
        .then(function (res) {
            var response = res.data.data;

            if(res.data.flag == 1) {   
                setTimeout(() => {
                    window.location.href = response.url;
                }, 500);
            }
        })
        .catch(function (error) {});
        
        return false;           
    });
})