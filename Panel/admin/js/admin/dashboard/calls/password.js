$(document).ready(function(){
    if($("#frmUpdatePassword").length > 0) {
        let rules = {
            old_password: {
                required: true,
            },
            password: {
                required: true,
                minlength: 8,
            },
            confirm_password: {
                required: true,
                minlength: 8,
            },
        };
        px?.ajaxRequest({
            element: "frmUpdatePassword",
            validation: true,
            script: "admin/dashboard/updatePassword",
            rules,
            afterSuccess: {
                type: "inflate_redirect_response_data"
            }
        });

    }
})
