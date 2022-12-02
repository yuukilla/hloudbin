// $("#updateProfileForm #upd_submit").on('click', function (e) {
//     e.preventDefault();
//     $.ajax({
//         type: "POST",
//         url: "/api/user/update-account",
//         data: {
//             "username": $("#username")[0].value,
//             "firstname": $("#firstname")[0].value,
//             "lastname": $("#lastname")[0].value,
//             "email": $("#email")[0].value,
//         },
//         dataType: "json",
//         success: function (response) {
//             alert(response.message)
//         },
//         error: function (xhr, response, status) {
//             alert(response.message)
//         }
//     });
// })