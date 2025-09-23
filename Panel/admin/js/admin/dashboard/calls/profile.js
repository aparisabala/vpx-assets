$(document).ready(function(){
    if($("#frmUpdateSystemUser").length > 0) {
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
            }
        };
        px?.ajaxRequest({
            element: "frmUpdateSystemUser",
            validation: true,
            script: "admin/systemsettings/systemuser/updateRow",
            rules,
            afterSuccess: {
                type: "inflate_redirect_response_data"
            }
        });

    }
})
