$(function () {

    "use strict";


    /**
     * Set message as read when opened
     */
    $(document).on('shown.bs.modal', ".modal", function(e) {
        var id  = $(this).data('id');
        var ref = $(this).data('read');

        if (!ref) {
            var data        = {
                csrf_token      : csrf_token,
                id              : id,
            };
            var formData = toFormData(data);
            var url         = '/'+uri_seg_1+'/contacts/read';
            axios.post(url, formData)
            .then(function (res) {})
            .catch(function (error) {});
        }
    });

    $(document).on('hidden.bs.modal', ".modal", function(e) {
        var ref = $(this).data('read');
        if(!ref) table.ajax.reload( null, false ); // user paging is not reset on reload
    });

});
