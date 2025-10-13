$(document).ready(function(){
    if ($('#frmAddNewTable').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253,
            },
        };
        PX.ajaxRequest({
            element: 'frmAddNewTable',
            validation: true,
            script: 'server.php',
            confirm: true,
            rules,
            afterSuccess: {
                type: 'inflate_redirect_response_data',
            }
        });
    }
})