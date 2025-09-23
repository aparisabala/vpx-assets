if($("#frmAdminLogin").length > 0) {
    let rules = {
        mobile_number: {
            required: true,
        },
        password:{
            required: true,
            minlength: 8,
        },
    };
    px?.ajaxRequest({
        element: "frmAdminLogin",
        validation: true,
        script: "admin/login",
        rules,
        afterSuccess: {
            type: "inflate_redirect_response_data",
        }
    });
}
