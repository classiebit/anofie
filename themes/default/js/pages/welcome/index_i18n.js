function toggleCard() {
    if($('#login-card:visible').length) {
        $('#signup-card').slideDown();
        $('#login-card').hide();
        
        $('.login-signup-toggle').html("{{action_login}}");
    } else {
        $('#signup-card').hide();
        $('#login-card').slideDown();
        
        $('.login-signup-toggle').html("{{action_signup}}");
    }
}