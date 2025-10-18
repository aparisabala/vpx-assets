import './src/Css/App.css';
import { PxValidations } from '@app/Classes/Helpers/PxValidations';
import { PxFactory } from './src/Classes/PxFactory';
import { DataTable } from './src/Classes/DataTable/DataTable';
import { BulkAction } from './src/Classes/Helpers/BulkAction';
import { PxConfig } from './src/Classes/PxConfig';
import { PdfGeneration } from './src/Classes/Helpers/PdfGeneration';
import { ExcelGeneration } from './src/Classes/Helpers/ExcelGeneration';
import { PxErrors } from './src/Classes/Helpers/PxErrors';
import { PxModal } from './src/Classes/Modal/PxModal';
import { PxUtils } from './src/Classes/Helpers/PxUtils';
class PX extends PxFactory {
    constructor(props){
        super(props);
        if (typeof $ == 'undefined') {
            throw new Error("This library required JQuery library, please use cdn or local JQuery File");
        }
        this.validation = new PxValidations();
        this.bulk = new BulkAction();
        this.pdf = new PdfGeneration();
        this.excel = new ExcelGeneration();
        const config = new PxConfig();
        this.config = config?.G;
        this.errors = new PxErrors();
        this.modal = new PxModal();
        this.utils  = new PxUtils();
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
        const {validation = undefined } = op;
        if(validation) {
            this?.validation?.validate(op,(op)=>{this?.send(op,callBack)},callBack)
        } else {
            if(op?.context) {
                op?.context?.requestGate(op);
            } else {
                this?.requestGate(op);
            }
        }
    }

    deleteAll(op){
        this?.bulk?.deleteAll({...op,context: this},this.ajaxRequest);
    }

    dowloadPdf(op){
        this?.pdf?.dowloadPdf({...op,context: this});
    }

    dowloadExcel(op){
        this?.excel?.dowloadExcel({...op,context: this});
    }


    updateAll(op){
        this?.bulk?.updateAll({...op,context: this},this.ajaxRequest);
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
        const context =this;
        if(window) {
            window.onload = function() {
                $(".disBtn").attr('disabled',false);
            };
            this.#pageAction();
            $(".p-link").each(function () {
                if ($(this).attr("href") == window.location.href) {
                $(this).addClass("p-link-active");
                }
            });

            $(".navbar-nav-link,.module-link").each(function () {
                if ($(this).attr("href") == window.location.href) {
                $(this).addClass("active");
                }
            });
            
            $(".sub-link").each(function () {
                if ($(this).attr("href") == window.location.href) {
                $(this).addClass("sub-link-active");
                }
            });
            $(".p-link-hr").each(function () {
                if ($(this).attr("href") == window.location.href) {
                $(this).addClass(" p-link-hr-active");
                }
            });
            $(".sub-link-hr").each(function () {
                if ($(this).attr("href") == window.location.href) {
                $(this).addClass(" sub-link-hr-active");
                }
            });
            $("#closeError").on("click", function () {
                $("#showErros").html("");
                $("#errorBase").removeClass("activateErrors").fadeOut(500);
            });
            $("#closeDownload").on("click", function () {
                $("#theDownloadLoader").css({ display: "none" });
            });

            $(".openNav").on("click",function(){
                let id = $(this).attr('data-open-id') ?? 'pageSideBar';
                context?.openNav(id);
            });
            $(".closeNav").on("click",function(){
                let id = $(this).attr('data-close-id') ?? 'pageSideBar';
                context?.closeNav(id);
            });
            this?.modal?.initModal(context);
        }
    }

    openNav(id,width=250) {
        document.getElementById(id).style.width = width+"px";
    }

    closeNav(id) {
        document.getElementById(id).style.width = "0";
    }

    inflatesuccess(msg) {
        this?.errors?.inflatesuccess(msg);
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
