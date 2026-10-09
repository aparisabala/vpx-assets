import { PxErrors } from "../Helpers/PxErrors";
import { PxConfig } from "../PxConfig";

export class PxModal extends PxConfig {

    constructor(props) {
        super(props);
    }

    initModal(parentContext) {
        const context = this;
        if(window){
            $(document).ready(function(){
                $('.editmodal').on('show.bs.modal', function (e) {
                    var trig = $(e.relatedTarget);
                    if (trig.attr("data-bs-target") === ".editmodal" && trig.attr("data-edit-prop")) {
                        var op = JSON.parse(trig.attr("data-edit-prop"));
                        context?.actionModal({...op,context: parentContext},parentContext?.ajaxRequest?.bind(parentContext));
                    }
                });

                $('.editmodal').on('hide.bs.modal', function (e) {
                    var mod = $("#modalmodule").val();
                    if (mod != undefined) {
                        switch (mod) {
                            case 'redirect':
                                window.location.href = window.location.href;
                                break;
                            case 'fn':
                                let callBack = $("#modalmodule").attr('data-callback');
                                let body = ($("#modalmodule").attr('data-body')) ? JSON.parse($("#modalmodule").attr('data-body')) : {};
                                if(window[callBack] != undefined) {
                                    window[callBack](body);
                                }
                                break;
                            default:
                                break;
                        }
                    }
                    $(".modal-body, .modal-title").html("");
                });    
            })
        }
    }

    actionModal(op={},ajaxRequest) {
        const context = this;
        const {element="NA",script="/", body={},title="No title provided",modalSize=undefined} = op;
        $(".modal-body, .modal-title").html('<span class="px-spinner px-spinner-sm d-inline-block align-middle me-2"></span> Loading...');
        let modalSizeClass = "modal-dialog modal-xl";
        if(modalSize) {
            modalSizeClass = `modal-dialog modal-${modalSize}`;
            $(".modal-dialog").removeClass().addClass(modalSizeClass);
        }
        if(op?.html) {
            $(".modal-title").html(op?.title);
            $(".modal-body").html(op?.html);
            if(op?.modalCallback) {
                if(window[op?.modalCallback]) {
                    window[op?.modalCallback]();
                }
            }
        } else {
            ajaxRequest({
                ...op,
                element,
                script,
                body,
                dataType: "json",
                afterSuccess: {type: 'modal'},
                type: "request",
                target: element,
                title,
                noLoaderImg: true
            });
        }
    }
}