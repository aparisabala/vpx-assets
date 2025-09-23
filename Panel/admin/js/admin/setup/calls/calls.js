$(document).ready(function(){
    if($("#frmUpdateSetupProfile").length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
            mobile_number: {
                required: true,
            },
            email: {
                required: true,
                maxlength: 253,
                email: true,
            },
            new_password: {
                required: true,
                minlength: 8
            },
            confim_password: {
                required: true,
                minlength: 8
            },
        };
        if($("#img_uploaded").val() == "no") {
            rules.user_image = {
                required: true,
            }
        }
        px?.ajaxRequest({
            element: "frmUpdateSetupProfile",
            validation: true,
            script: "admin/setup/profile/update",
            rules,
            afterSuccess: {
                type: "inflate_redirect_response_data"
            }
        });

    }
})
