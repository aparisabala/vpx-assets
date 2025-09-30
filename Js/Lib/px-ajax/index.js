import { PxValidations } from '@app/Classes/Helpers/PxValidations';
import './src/Css/App.css';
import { PxFactory } from './src/Classes/PxFactory';
import { DataTable } from './src/Classes/DataTable/DataTable';
class PX extends PxFactory {
    constructor(props){
        super(props);
        if (typeof $ == 'undefined') {
            throw new Error("This library required JQuery library, please use cdn or local JQuery File");
        }
        this.validation = new PxValidations();
        this.init();
    }

    /**
     * Send an AJAX request
     * @param {Object} op - The options for the AJAX request
     * @param {Function} callBack - The callback function to execute on success
     * @returns {number} - Returns 0 if no options are provided
     */
    ajaxRequest(op = {}, callBack = undefined) {
        if (op == {}) {
            return 0;
        }
        const {validation = false } = op;
        (validation) ? this?.validation?.validate(op,(op)=>{this?.send(op,callBack)},callBack) : this?.chekRequest(op);
    }

    /**
     * Render a datatable in 
     * @param {String} table 
     * @param {Object} op 
     */
    renderDataTable(table,op){
        let dt = new DataTable;
        dt.makeAjaxDataTable(table,op);
    }

    init(){
        if(window){
            window.onload = function() {
                $(".disBtn").attr('disabled',false);
            };
            this.#pageAction();
        }
    }

    #pageAction(){
        let context = this;
        $(".viewAction").unbind("click");
        $(".viewAction").on("click", function () {
            const prop = JSON.parse($(this).attr('data-prop'));
            const { page = "addPage", server = "no", method = "post", type = "request", target = "loadEdit", afterSuccess = { type: "load_html" }, dataType = "json" } = prop;
            $(".pages").addClass('d-none').removeClass('d-block');
            $("#" + page).removeClass('d-none').addClass('d-block');
            if (server == "yes") {
                context.ajaxRequest({ ...prop, type, method, afterSuccess, target, dataType, server });
            }
        });
        $(".closeAction").unbind("click");
        $(".closeAction").on("click", function () {
            let target = $(this).attr('data-cl-action');
            $(".pages").addClass('d-none').removeClass('d-block');
            $("#" + target).removeClass('d-none').addClass('d-block');
        });
    }
}

const pxInstance = new PX();
Object.freeze(pxInstance);
export default pxInstance;
