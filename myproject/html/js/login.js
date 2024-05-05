$(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        var userData = {
            username: $('#username').val(),
            password: $('#password').val()
        };

        $.ajax({
            type: "POST",
            url: "login.php",
            data: userData,
            success: function(response) {
                var data = response;
                if (data.success) {
                    localStorage.setItem('token', data.token); // Save token to local storage
                    window.location.href = 'index.html'; // Redirect to main page
                } else {
                    alert(data.message); // Show error message
                }
            },
            error: function(e) {
                alert('Login failed. Please try again later.');
                console.error('Login failed.', e);
            }
        });
    });
});
