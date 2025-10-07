import { PxConfig } from "../PxConfig";

export class PxConfirm extends PxConfig {

    /**
     * Show a confirmation alert
     * @param {*} op - The options for the confirmation alert
     * @param {*} callBack - The callback function to execute on confirmation
     */
    confirmAlert(op,callBack=undefined) {
        const { element="n/a", confirmTitle=this?.G?.mgs?.confirm, confirmMessage="", customConfirm=false} = op;
        if (customConfirm) {
            switch (element) {
                default:
                    alert("Case not found in confirm for "+element)
                    break;
            }
        } else {
            let buttons = {};
            console.log(this?.G);
            buttons[this?.G?.mgs?.btns?.confirm] = {
                btnClass: 'btn btn-primary',
                action: function () {
                    if(callBack) {
                        callBack(op);
                    } else {
                        if(local) {
                            console.warn("No callback defined for confrm in "+element)
                        }
                    }
                },
            };
            buttons[this?.G?.mgs?.btns?.cancel] =  {
                btnClass: 'btn btn-danger',
                style: 'cancel',
                action: function () { }
            };
            $.confirm({
                title: '<span style="font-size: 14px; color: green;"> ' + confirmTitle + ' </span>',
                content: confirmMessage,
                animation: 'zoom',
                closeAnimation: 'scale',
                buttons: buttons
            });
        }
    }
}
