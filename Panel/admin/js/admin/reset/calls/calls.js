if($("#frmSendAdminCode").length > 0) {
    let rules = {
        email: {
            required: true,
        }
    };
    px?.ajaxRequest({
        element: "frmSendAdminCode",
        validation: true,
        script: "admin/reset/sendCode",
        rules,
        afterSuccess: {
            type: "load_html",
            target: "resetBase",
            afterLoad: (response)=>{
                VerifyCode();
            },
            reload: false,
        },

    });

    function VerifyCode(){
        if($("#frmVerifyAdminCode").length > 0) {
            let verifyrules  = {
                code: {
                    required: true,
                    number: true,
                }
            };
            px?.ajaxRequest({
                element: "frmVerifyAdminCode",
                validation: true,
                script: "admin/reset/verifyCode",
                verifyrules,
                afterSuccess: {
                    type: "load_html",
                    reload: false,
                    target: "resetBase",
                    afterLoad: (response)=>{
                        SetNewPassword();
                    }
                },

            });
        }
    }

    function SetNewPassword(){
        if($("#frmChangePassword").length > 0) {
            let chnagerules = {
                password: {
                    required: true,
                    minlength:8,
                },
                confirm_password: {
                    required: true,
                    minlength:8,
                    same: '#password'
                }
            };
            px?.ajaxRequest({
                element: "frmChangePassword",
                validation: true,
                script: "admin/reset/chnagePass",
                chnagerules,
                afterSuccess: {
                    type: "inflate_redirect_response_data",
                }
            });
        }

    }
}
