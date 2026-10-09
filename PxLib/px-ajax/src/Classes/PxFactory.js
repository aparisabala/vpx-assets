import { PxConfirm } from "./Helpers/PxConfirm";
import { PxDataSetup } from "./Helpers/PxDataSetup";
import { PxErrors } from "./Helpers/PxErrors";
import { PxSuccess } from "./Helpers/PxSuccess";
import { PxConfig } from "./PxConfig";
import $ from 'jquery';
export class PxFactory extends PxConfig {

    #pxErrors;
    #pxDataSetup;
    #pxConfirm;
    #pxSuccess;

    constructor(props) {
        super(props);
        this.#pxErrors = new PxErrors();
        this.#pxDataSetup = new PxDataSetup();
        this.#pxConfirm = new PxConfirm();
        this.#pxSuccess = new PxSuccess();
    }

    /**
     * Check the request type and handle accordingly
     * @param {Object} op - The options for the AJAX request
     *
     * @returns {number} - Returns 0 if no options are provided
     * */
    requestGate(op, callBackArg = undefined) {
        const { type = "n/a", element = "n/a"} = op;
        const callBack = callBackArg ?? op?.callBack;
        const context = this;
        switch (type) {
            case "submit":
                $("#" + element).off("submit.px").on("submit.px", function (e) {
                    e.preventDefault();
                    let formData = context.#pxDataSetup?.getRequestData({ ...op, form: this })
                    if (context.#pxDataSetup?.hasInternet()) {
                        context?.send({ ...op, body: formData, form: this }, callBack);
                    }
                });
                break;
            case "request":
                if (this.#pxDataSetup?.hasInternet()) {
                    this?.send({...op,body: this.#pxDataSetup?.getRequestData(op)}, callBack);
                }
                break;
            default:
                return 0;
        }
    }

    /**
     * Send the AJAX request based on the provided options
     * @param {Object} op - The options for the AJAX request
     * @param {Function} callBack - The callback function to execute on success
     */
    send(op, callBack) {
        const { confirm = false, afterSuccess=undefined,beforeSend=undefined} = op;
        if((afterSuccess?.type == "load_html" && afterSuccess?.reload) || afterSuccess?.type == "api_response") {
            const {target="none"} = afterSuccess;
            $("#"+target).html("");
        }
        if(confirm) {
            this.#pxConfirm?.confirmAlert(op,(op)=>{this?.ajax(op, (op)=>{ (callBack) ? callBack(op) :  this.#pxSuccess?.success(op)})});
        } else {
            if(beforeSend) {
                beforeSend(op,(op)=> this?.ajax(op, (op)=>{ (callBack) ? callBack(op) : this.#pxSuccess?.success(op)}));
            } else {
                this?.ajax(op,(op)=>{ (callBack) ? callBack(op) :  this.#pxSuccess?.success(op)});
            }
        }
    }

    /**
     * Send an AJAX request
     * @param {Object} op - The options for the AJAX request
     * @param {Function} callback - The callback function to execute on success
     */
    ajax(op={},callback=undefined) {
        const {globLoader=true,loaderId='theGlobalLoader',loaderActiveId='activeGlobalLoader',error_view='error_view'} = op;
        const context = this;
        const isLocal = typeof local !== 'undefined' && local;
        if(globLoader) {
            $('#'+loaderId).addClass(loaderActiveId).css({ "display": "block" });
        }
        const config = this.#getAjaxReqConfig(op);
        $("."+error_view).html('');
        $.ajax(config).done(function (response) {
            if(isLocal) {
                console.log(response);
            }
            if (callback) {
                callback({...op,response});
            }
            return false;
        }).fail(function (xhr, status, error, req) {
            if (isLocal) {
                console.log(status);
                console.log(error);
                console.log(req);
            }
            if(globLoader) {
                $('#'+loaderId).removeClass(loaderActiveId).css({ "display": "none" });
            }
            if (xhr.readyState == 0) {
                context.#pxErrors?.noServer();
            } else {
                if (isLocal) {
                    console.log(xhr.responseText);
                    context.#pxErrors?.scriptError(xhr);
                }
            }
        });
    }
    /**
     * Get the AJAX request configuration based on the provided options
     * @param {Object} op - The options for the AJAX request
     * @returns {Object} - The AJAX request configuration
     * @private
     */
    #getAjaxReqConfig(op){
        const { script='/', body={}, method='POST', dataType='formData',bearer='',fullUri=false} = op;
        let config  = {
            method: method,
            url: (!fullUri) ? (typeof baseurl !== 'undefined' ? baseurl : '') + script  : fullUri,
            data: body,
            contentType: false,
            cache: true,
            processData: false,
            headers: {
                "X-CSRF-Token": this?.G?.csrf_token
            }
        }
        if(dataType == "json") {
            config.dataType = 'json'
            config.headers =  {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Authorization': `Bearer ${bearer}`,
                ...config?.headers
            }
            config.data = JSON.stringify(body);
            config.contentType = "application/json; charset=utf-8";
        }
        return config;
    }

}
