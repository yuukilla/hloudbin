/**
 * Check authentification forms and submit them
 */
//      EMAIL REGEX LMAO?
//  /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|
//     \\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|
//     \[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:
//     (?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/

var typingTimer;
var doneTypingInt = 1500;

var bolSignupHasErrors = false;
var bolLoginHasErrors = false;

// TODO: 
//    on username field update, check if username is available
//    on email field update, check if email is available
//    on password field update, check if meets requirements
//    on password-confirm update, check if same as password field

$("#signupForm #reg_username").on('keyup', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTypingUsername, doneTypingInt);
})
$("#signupForm #reg_username").on('keydown', function() {
    clearTimeout(typingTimer);
    $("#signupForm #reg_username").removeClass('border-success')
    $("#signupForm #reg_username").removeClass('border-danger')
});

function doneTypingUsername() {
    if ($("#signupForm #reg_username")[0].value.length != 0) {
        $.ajax({
            type: "GET",
            url: "/api/user/exists/username?search="+$("#signupForm #reg_username")[0].value,
            dataType: "json",
            success: function (response) {
                if (response == true) {
                    $("#signupForm #reg_username").removeClass('border-success')
                    $("#signupForm #reg_username").addClass('border-danger')
                    bolSignupHasErrors = true;
                } else {
                    $("#signupForm #reg_username").removeClass('border-danger')
                    $("#signupForm #reg_username").addClass('border-success')
                    bolSignupHasErrors = false;
                }
            }
        });
    }
}

$("#signupForm #reg_email").on('keyup', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTypingEmail, doneTypingInt);
})

$("#signupForm #reg_email").on('keydown', function() {
    clearTimeout(typingTimer);
    $("#signupForm #reg_email").removeClass('border-success')
    $("#signupForm #reg_email").removeClass('border-danger')
});

function doneTypingEmail() {
    if ($("#signupForm #reg_email")[0].value.length != 0) {
        $.ajax({
            type: "GET",
            url: "/api/user/exists/email?search="+$("#signupForm #reg_email")[0].value,
            dataType: "json",
            success: function (response) {
                if (response == true) {
                    $("#signupForm #reg_email").removeClass('border-success')
                    $("#signupForm #reg_email").addClass('border-danger')
                    bolSignupHasErrors = true;
    
                } else {
                    $("#signupForm #reg_email").removeClass('border-danger')
                    $("#signupForm #reg_email").addClass('border-success')
                    bolSignupHasErrors = false;
                }
            }
        });
    }
}

$("#signupForm #reg_submit").on('click', function (e) {
    e.preventDefault();

    if ( $("#signupForm #reg_username")[0].value.length <= 0 ) {
        $("#signupForm #reg_username").addClass('border-danger');
        bolSignupHasErrors = true;
    }
    if ( $("#signupForm #reg_firstname")[0].value.length <= 0 ) {
        $("#signupForm #reg_firstname").addClass('border-danger');
        bolSignupHasErrors = true;
    }
    if ( $("#signupForm #reg_lastname")[0].value.length <= 0 ) {
        $("#signupForm #reg_lastname").addClass('border-danger');
        bolSignupHasErrors = true;
    }
    if ( $("#signupForm #reg_email")[0].value.length <= 0 ) {
        $("#signupForm #reg_email").addClass('border-danger');
        bolSignupHasErrors = true;
    }
    if ( $("#signupForm #reg_password")[0].value.length <= 0 ) {
        $("#signupForm #reg_password").addClass('border-danger');
        bolSignupHasErrors = true;
    }
    if ( $("#signupForm #reg_password-repeat")[0].value.length <= 0 ) {
        $("#signupForm #reg_password-repeat").addClass('border-danger');
        bolSignupHasErrors = true;
    }
    if (
        $("#signupForm #reg_password")[0].value != $("#signupForm #reg_password-repeat")[0].value ||
        $("#signupForm #reg_password-repeat")[0].value != $("#signupForm #reg_password")[0].value
    ) {
        $("#signupForm #reg_password").addClass('border-danger')
        $("#signupForm #reg_password-repeat").addClass('border-danger')
        bolSignupHasErrors = true;
    }

    if (!bolSignupHasErrors) {
        $('#signupForm').submit()
    } else {
        $("#signupForm #signupErrors").append(
            `<div class="alert alert-danger">
                Check highlighted inputs
            </div>`
        )
    }
})

// // signin form
// $("#loginForm").submit(function (e) { 
//     e.preventDefault();
//     if (!bolLoginHasErrors) {
//         // submit
        
//     } else {
//         // show errors
//     }
// });