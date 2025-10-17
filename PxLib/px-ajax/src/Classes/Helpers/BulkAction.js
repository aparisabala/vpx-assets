import { PxFactory } from "../PxFactory";
import { PxErrors } from "./PxErrors";

export class BulkAction  {

    constructor(){
        this.errors = new PxErrors;
        this.factory = new PxFactory;
    }

       deleteAll(op,ajaxRequest) {
        const context = this;
        const { element = "N/A", script = "/", extra = {}, api = [], dataType = "json", type = "request", afterSuccess = { type: "inflate_redirect_response_data" }, tableLoadType="ajax" } = op;
        $("." + element).unbind("click");
        $("." + element).on("click", function () {
            let rows_selected = api.rows('.selected').data().toArray();
            if (rows_selected.length > 0) {
                let d = {};
                if (extra != "no") {
                    d["extra"] = extra;
                }
                let bodyData =  $(this).attr('data-bodyData') ?? null;
                if($(this).attr('data-bodyData')) {
                    bodyData = JSON.parse(bodyData);
                    d = {
                        ...d,
                        bodyData,
                    }
                }
                let ids = [];
                if(tableLoadType == "ajax") {
                    rows_selected.map((v, k) => { ids.push(v?.id) })
                } else {
                    rows_selected.map((v, k) => { ids.push(v[0]) })
                }
                d["ids"] = ids;
                ajaxRequest({
                    ...op,
                    element,
                    script,
                    body: d,
                    dataType,
                    type,
                    afterSuccess
                });

            } else {
               context?.errors?.showAlert(`<span class="text-danger fs-14">No items selected </span>`, "");
            }
        });
    }

    updateAll(op,ajaxRequest) {
        const context = this;
        const { element = "N/A", script = "/", extra = {}, api = [], dataType = "json", type = "request", afterSuccess = { type: "inflate_redirect_response_data" }, dataCols = [] } = op;
        $("." + element).unbind("click");
        $("." + element).on("click", function () {
            let d = {};
            if (extra != "no") {
                d["extra"] = extra;
            }
            let colData = {};
            dataCols.items.map((colValue, colKey) => {
                colValue.data = context.#getDtData(colValue.type, api, colValue.index, colValue.name);
                return colValue;
            });
            let keyDataItem = dataCols.items.find((value) => { return value.name == dataCols.key });
            if (keyDataItem) {
                let keyData = keyDataItem.data;
                dataCols.items.map((colValue, colKey) => {
                    let dataArray = {};
                    colValue.data.map((value, key) => {
                        dataArray[keyData[key]] = value;
                    });
                    d[colValue.name] = dataArray;
                });
            }
            ajaxRequest({
                ...op,
                element,
                script,
                body: d,
                dataType,
                type,
                afterSuccess
            });
        });
    }

    #getDtData(type, dt, col, c) {
        let rdata = [];
        dt.column(col).nodes().to$().each(function () {
            switch (type) {
                case "input":
                    rdata.push($(this).find("." + c).val());
                    break;
                case "td":
                    rdata.push($(this).text().trim());
                    break;
                default:
                    break;
            }
        });
        return rdata;
    }
}