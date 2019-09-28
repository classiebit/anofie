/* Common Frontend JS */

/* NEW VERSION JS */
function searchUser(e) {
    if(e.value.length >= 2) {
        axios.get('/profile/search_user/'+e.value)
        .then(function (res) {
            var response = res.data.data;
            if(res.data.flag == 1) {   
                $('#list-users').html(response.view);
            }
        })
        .catch(function (error) {});
    }
}