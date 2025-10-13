import $ from 'jquery';
import "jquery-validation";
import { PxConfig } from "../PxConfig";
import { PxDataSetup } from "./PxDataSetup";
export class PxValidations extends PxConfig {

    pxDataSetup = undefined;
    constructor(props){
        super(props);
        this.pxDataSetup = new PxDataSetup;
    }

    /**
     * Validate form fields
     * @param {*} op - The options containing the validation rules and messages
     * @param {*} send - The function to call to send the form data
     * @param {*} callBack - The callback function to execute after validation
     */
    validate(op = {}, send, callBack) {
        const { element = "no", rules = {}, messages = {}, afterValidation = undefined } = op;
        let common_message = this?.pxDataSetup?.getMessageBags(rules, this?.G.mgs);
        let context = this;
        $("#" + element).validate({
            rules: rules,
            messages: (this?.G.isEmptyObjcet(messages)) ? common_message : messages,
            errorElement: "em",
            errorPlacement: function (error, element) {
                error.addClass("invalid-feedback");
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.next("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).addClass("is-valid").removeClass("is-invalid");
            },
            submitHandler: function (form) {
                if (afterValidation) {
                    afterValidation(form, op);
                } else {
                    let data = new FormData(form);
                    data.append("_token", context?.G?.csrf_token);
                    data.append("client", "w");
                    data.append("auth_uuid", $("#auth_uuid").val());
                    if (context?.pxDataSetup?.hasInternet()) {
                        op.body = data;
                        op.form = form;
                        send(op, callBack);
                    }
                }
                return false;
            }
        });
    }
}
