import { PxConfig } from "../PxConfig";

export class PxErrors extends PxConfig {

    /**
     * Show an alert when the server is not found
     */
    noServer() {
        $.confirm({
            title: '<span style="font-size: 16px;color: #ff0101;"> <i class="fa fa-exclamation-circle"></i> '+this?.G?.mgs?.server_not_found+' </span> <hr> <span style="font-size: 13px;color: #ff0101;"> Check your internet connection or try reload the page </span>',
            content: '',
            buttons: {
                confirm: {
                    text: "OK",
                    btnClass: 'btn btn-info',
                    action: () => {
                    }
                }
            }
        });
    }
    /**
     * Show an alert when the server is wrong
     * @param {Object} xhr - The XMLHttpRequest object containing the error details
     */
    scriptError(xhr) {
        const {responseJSON} = xhr;
        $.confirm({
            title: '<span style="font-size: 14px;color: #ff0101;"> <i class="fa fa-exclamation-circle"></i> '+this?.G?.mgs.server_wrong+' </span>',
            content: '<span style="font-size: 12px;color: black;"> <i class="fa fa-exclamation-circle"></i> '+responseJSON?.message+' </span>',
            buttons: {
                confirm: {
                    text: "OK",
                    btnClass: 'btn btn-info',
                    action: () => {
                    }
                }
            }
        });
    }

    /**
     * Show an alert when the server is not responding
     * @param {Object} xhr - The XMLHttpRequest object containing the error details
     */
    inflatesuccess(msg, r = null) {
        if(msg != null) {
            var d = '<div class="sufee-alert alert with-close alert-success alert-dismissible fade show">' + msg +'</div>';
            $("#inflate").append(d);
        }
        var t = setTimeout(function() {
            $("#inflate").html("");
            clearTimeout(t);
            $('#theGlobalLoader').removeClass("activeGlobalLoader").css({ "display": "none" });
        }, 500);
    }

    /**
     * Show an alert when the server is not responding
     * @param {string} msg - The message to display in the alert
     */
    inflaterequire(msg) {
        if(msg != null) {
            var d = '<div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">' + msg +'</div>';
            $("#inflate").append(d);
        }
        var t = setTimeout(function() {
            $("#inflate").html("");
            clearTimeout(t);
            $('#theGlobalLoader').removeClass("activeGlobalLoader").css({ "display": "none" });
        }, 500);
    }

    /**
     * Hide the global loader
     */
    hideLoader(){
        $('#theGlobalLoader').removeClass("activeGlobalLoader").css({ "display": "none" });
    }

    /**
     * Display all errors from the server response
     * @param {Object} op - The options containing the response and page details
    */
    displayAllErrors(op) {
        const {response={},page="addPage",server=false} = op;
        var err = this?.G?.mgs.inflate_error;
        var big_err = this?.G?.mgs.action_error;
        if(server) {
            $("#defaultPage").addClass('d-block').removeClass('d-none');
            $("#"+page).addClass('d-none').removeClass('d-block');
        }
        if (response.bigError) {
            var ele = '<div style="margin-bottom: 10px;font-size: 20px;"> </div> '+big_err+'<ul style="padding-left: 20px;">';
            for (var i = 0; i < response.bigErrors.length; i++) {
                ele += '<li>' + response.bigErrors[i] + '</li>';
            }
            ele += '</ul>';
            inflaterequire(this?.G?.mgs.action_error);
            $("#showErros").html(ele);
            $("#errorBase").addClass("activateErrors").fadeIn(500);
            $('#theGlobalLoader').removeClass("activeGlobalLoader").css({ "display": "none" });
        } else {
            if (response.noUpdate) {
                $('#theGlobalLoader').removeClass("activeGlobalLoader").css({ "display": "none" });
                this?.noUpdate(op);
            } else {
                this?.jsonshow(response.errors);
                $('#theGlobalLoader').removeClass("activeGlobalLoader").css({ "display": "none" });
                if (response.emsg != undefined) {
                    if (!response.partial) {
                        this?.inflaterequire(err+" <br> " + response.emsg);
                    }
                } else {
                    if (!response.partial) {
                        this?.inflaterequire(err);
                    }
                }
            }
        }
    }
    showerrornormal(errors) {
        for (var k in errors) {
            if (errors.hasOwnProperty(k)) {
                $("#" + k + '_error').addClass("required").html(errors[k]);
            }
        };
    }

    jsonshow(errors) {
        for (var k in errors) {
            if (errors.hasOwnProperty(k)) {
                let hasAr = k.split('.');
                if(hasAr.length == 1) {
                    $("#" + k + '_error').addClass("required").html(errors[k][0]);
                } else {
                    $("#" + hasAr[0] +'\\.'+hasAr[1]+'_error').addClass("required").html(errors[k][0]);
                }
            }
        };
    }

    noUpdate(op) {
        const  {response={}} = op;
        let btns = {};
        let name = this?.G?.mgs.btns.glob_close;
        btns[name] = {
            btnClass: 'btn btn-primary text-white text-capitalize',
            function() {
                if (type == "expaired") {
                    this?.timeoutReload(null, 200);
                }
            }
        };
        $.alert({
            title:  response.title,
            content: response.content,
            animation: 'zoom',
            closeAnimation: 'scale',
            buttons: btns
        })
    }

    showAlert(title, content) {
        let btns = {};
        let name = this?.G?.mgs.btns.glob_ok;
        btns[name] = {
            btnClass: 'btn btn-primary text-white text-capitalize',
            function() {
            }
        };
        $.alert({
            title: title,
            content: content,
            buttons: btns
        });
    }

    timeoutReloadFull(url, t = 1200) {
        var p = setTimeout(function() {
            clearTimeout(p);
            window.location.href = url;
        }, t);
    }

    timeoutReload(url, t = 1200) {
        let context = this;
        var p = setTimeout(function() {
            clearTimeout(p);
            context?.reload(url);
        }, t);
    }

    reload(url = null) {
        if (url == null) {
            window.location.href = window.location.href;
        } else {
            window.location.href = baseurl + url;
        }
    }

}
