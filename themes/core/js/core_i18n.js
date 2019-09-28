'use strict';

/* ============== AXIOS INTERCEPTORS start=================== */
axios.defaults.baseURL = base_url;

// Add a request interceptor
axios.interceptors.request.use(function (config) {
    // start progress bar
    Pace.restart();
    return config;
}, function (error) {
    return Promise.reject(error);
});

// Add a response interceptor

// reload the page in case of consistent failed requests
var failed_count = 0;
axios.interceptors.response.use(function (response) {
    // catch php error
    if(/<p>/i.test(response.data)) {
        showToast("warning", "{{alert_wrong}}");
    }

    // show validation and custom errors
    if(response.data.flag == 0) {
        failed_count++;

        // show only if any message
        if(response.data.msg !== null) {
            showToast("error", response.data.msg);
        }

        // set input fields in error state if any
        if(response.data.error_fields.length > 0) {
            $.each(response.data.error_fields, (index, item) => {
                $("input[name*='"+item+"'], select[name*='"+item+"'], textarea[name*='"+item+"']").closest('.form-group').addClass('has-danger');
                $("input[name*='"+item+"'], select[name*='"+item+"'], textarea[name*='"+item+"']").addClass('is-invalid');
            });
        }
        
    } else {
        // reset failed request counter
        failed_count = 0;

        // show success messages
        // show only if any message
        if(response.data.msg !== null) {
            showToast("success", response.data.msg);
        }
    }

    // reload page after 10 failed attempts
    if(failed_count == 10)
        location.reload();
    
    return response;
}, function (error) {
    // catch CSRF error and reload 
    if(/403/i.test(error)) {
        location.reload();
    }
    
    return Promise.reject(error);
});
/* ============== AXIOS INTERCEPTORS start=================== */





/* ============== RESET FORM FIELDS ERROR STATE ON CHANGE start=============== */
// using live jquery
$(document).on('focus', "input, select, textarea", function() {
    $(this).removeClass('is-invalid');
    $(this).closest('.form-group').removeClass('has-danger');
});
/* ============== RESET FORM FIELDS ERROR STATE ON CHANGE end=============== */





/* ================ CONVERT OBJECT TO FORMDATA ================ */

function toFormData(data) {
    var formData = new FormData(); // Currently empty

    $.each(data, (index, item) => {
        formData.append(index, item);    
    });

    return formData;
}
/* ================ CONVERT OBJECT TO FORMDATA ================ */




/* ================ SHOW NOTIFICATION ================ */
function showToast(type, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-right',
        showConfirmButton: false,
        timer: 4000,
        customClass: {
            container: 'custom-swal-container',
            popup: 'custom-swal-popup custom-swal-popup-'+type,
            header: 'custom-swal-header',
            title: 'custom-swal-title',
            closeButton: 'custom-swal-close-button',
            image: 'custom-swal-image',
            content: 'custom-swal-content',
            input: 'custom-swal-input',
            actions: 'custom-swal-actions',
            confirmButton: 'custom-swal-confirm-button',
            cancelButton: 'custom-swal-cancel-button',
            footer: 'custom-swal-footer'
        }
    });
    Toast.fire({
        type: type,
        html: message
    })
}
/* ================ SHOW NOTIFICATION ================ */


/* ================ GENERATE RANDOM STRING ================ */
function randomString(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}

/* ================ GENERATE RANDOM STRING ================ */



/* ================ AXIOS CONFIRM ALERT ================ */
function axiosAlert(title, text, data, url, redirect) {
    Swal.fire({
        title: title,
        html: text,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: title,
        cancelButtonText : "{{action_cancel}}",
    }).then((result) => {
        if (result.value) {
            var formData = toFormData(data);
            axios.post(url, formData)
            .then(function (res) {
                var response = res.data.data;
                if(res.data.flag == 1) {
                    Swal.fire("{{alert_info}}", res.data.msg, "info")

                    if(typeof redirect === "undefined") {
                        setTimeout(() => {
                            location.reload();    
                        }, 500);
                    } else {
                        setTimeout(() => {
                            window.location.href = redirect;
                        }, 500);
                    }
                }
            })
            .catch(function (error) {});
        }
    });
}
/* ================ AXIOS CONFIRM ALERT ================ */