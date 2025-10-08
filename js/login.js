// js/login.js
$(document).ready(function () {
    $('#login-form').submit(function (e) {
        e.preventDefault();

        let email = $('#email').val().trim();
        let password = $('#password').val().trim();

        // Validation
        if (email === '' || password === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });
            return;
        }

        $.ajax({
            url: '../actions/login_customer_action.php',
            type: 'POST',
            dataType: 'json',
            data: {
                email: email,
                password: password
            },
            success: function (response) {
                console.log("Raw response:", response);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
<<<<<<< HEAD
                            // Redirect based on user role
                            if (response.role === '2' || response.role === 2) {
                                // Admin
                                window.location.href = '../admin/category.php';
                            } else {
                                // Regular customer
                                window.location.href = '../index.php';
                            }
=======
                            window.location.href = '../index.php';
>>>>>>> ccf60bc0925d5da4c196d7ac0a7fd89ac8dc8f46
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                console.log("XHR responseText:", xhr.responseText); 
                console.log("Status:", status);
                console.log("Error:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An error occurred! Please try again later.',
                });
            }
        });
    });
});
