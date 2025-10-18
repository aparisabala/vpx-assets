import { PxConfig } from "../PxConfig";
import { PxDataSetup } from "./PxDataSetup";
import { PxErrors } from "./PxErrors";

export class PxSuccess extends PxConfig {

    #pxErros;
    #pxDataSetup;

    constructor(props) {
        super(props);
        this.#pxErros = new PxErrors();
        this.#pxDataSetup = new PxDataSetup();
    }

    /**
     * Handle the success response from the server
     * @param {Object} op - The options containing the response and page details
     */
    success(op = {}) {
        const { prev = "load-data", response = null, element = "no", customHideLoader=true, showInflate=true} = op;
        $("#" + element + ' label span').html("").removeClass("required success");
        let type = typeof (response);
        if (local) {
            console.log(response);
        }
        if (type === "object") {
            if (response.success) {
                let { extraData = { inflate: this?.G?.lang()?.no_message_return, redirect: window.location.href }, view = "" } = response.data;
                const { afterLoad = undefined, target = "edit-view" } = op?.afterSuccess;
                let obj = { ...op, response };
                if (op?.afterSuccess?.type) {
                    switch (op?.afterSuccess?.type) {
                        case "inflate_response_data":
                            if(showInflate) {
                                this.#pxErros?.inflatesuccess(extraData?.inflate);
                            }
                            break;
                        case "inflate_reset_response_data":
                            if(showInflate) {
                                this.#pxErros?.inflatesuccess(extraData?.inflate);
                            }
                            this.#pxDataSetup?.fReset(element);
                            break;
                        case "inflate_redirect_response_data":
                            if(showInflate) {
                                this.#pxErros?.inflatesuccess(extraData?.inflate);
                            }
                            this.#pxErros?.timeoutReload(extraData.redirect, 400);
                            break;
                        case "load_html":
                            if(showInflate) {
                                this.#pxErros?.inflatesuccess(extraData?.inflate);
                            }
                            $("#" + target).html(view);
                            break;
                        case "api_response":
                            if(customHideLoader) {
                                this.#pxErros?.hideLoader();
                            }
                            break;
                        case "modal":
                            this.#afterLoadModal(op);
                            break;
                        default:
                            this.#pxErros?.inflatesuccess(this?.G.lang()?.action_success);
                            this.#pxErros?.timeoutReload(extraData.redirect, 400);
                            break;
                    }
                } else {
                    this.#pxErros?.inflatesuccess(this?.G.lang()?.action_success);
                    this.#pxErros?.timeoutReload(extraData.redirect, 400)
                }
                (afterLoad) ? afterLoad(obj,response?.data) : 0;
            } else {
                this.#pxErros?.displayAllErrors(op);
            }
        } else {
            $('#global-loader').removeClass("active-global-loader").css({ "display": "none" });
        }
    }

    #afterLoadModal(op) {
        let type = typeof(op);
        if (local) {
            console.log(op);
        }
        if (type === "object") {
            const {response,title,modalCallback = undefined,globLoader=true} = op;
            if (response.success) {
                let { extraData = { inflate: G?.lang()?.no_message_return, redirect: window.location.href},view="reached"} = response.data;
                if(globLoader) {
                   this.#pxErros?.inflatesuccess(extraData?.inflate);
                }
                console.log(view);
                $(".modal-title").html(title);
                $(".modal-body").html(view);
                if(modalCallback) {
                    if(window[modalCallback]) {
                        window[modalCallback](response);
                    }
                }
            }
        } else {
            $('#theGlobalLoader').removeClass("activeGlobalLoader").css({ "display": "none" });
        }
    }
}
