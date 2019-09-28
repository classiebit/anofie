"use strict";

$(function () {
    var username = '';
    
    $('input[name="username"]').on('change', function() {
        username = $(this).val();

        if(username.length >= 3) {
            var data        = {
                'csrf_token' : csrf_token,
                'username'   : username,
            };
            var formData = toFormData(data);
            
            axios.post('/auth/do_check_username', formData)
            .then(function (res) {
                var response = res.data;
                $('input[name="username"]').removeClass('is-valid');
                if(response.flag == 1) {
                    $('input[name="username"]').addClass('is-valid');
                } 
            })
            .catch(function (error) {});
        }
    });

    // Ajax register
    $('form#form_register').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData($(this)[0]);

        axios.post('/auth/do_register', formData)
        .then(function (res) {
            var response = res.data;
            if(response.flag == 1) {   
                setTimeout(() => {
                    location.reload();    
                }, 1000);
            }
        })
        .catch(function (error) {});
    });

    // ajax login
    $('form#form_login').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData($(this)[0]);

        axios.post('/auth/do_login', formData)
        .then(function (res) {
            var response = res.data;
            if(response.flag == 1) {   
                setTimeout(() => {
                    location.reload();    
                }, 1000);
            }
        })
        .catch(function (error) {});
    });

});