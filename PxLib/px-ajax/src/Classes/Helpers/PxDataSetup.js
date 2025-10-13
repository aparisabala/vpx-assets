import { PxConfig } from "../PxConfig";

export class PxDataSetup extends PxConfig {

    /**
     * Check if the user has internet connectivity
     * @returns {boolean} - Returns true if online, false otherwise
     */
    hasInternet() {
        var status = navigator.onLine;
        if (status) {
            return true;
        } else {
            $.alert('<span style="font-size: 13px;color: #ff0101;">'+this?.G.mgs.no_internet+'</span>');
            return false;
        }
    }

    getRequestData(op){
        const {form=null,body={}} = op;
        let data = null;
        let auth_uuid = $("#auth_uuid").val() ?? op?.auth_uuid;
        if(form != null) {
            let fData = new FormData(form);
            fData.append("_token",csrf_token);
            fData.append("client","w");
            fData.append('auth_uuid',auth_uuid)
            data = fData;
        } else {
            body['client'] = "w";
            body['auth_uuid'] = auth_uuid;
            data = body;
        }
        return data;
    }

    getMessageBags(rules,mgs){
        let messages = {};
        let langOb = {};
        for (const key in rules) {
            if (Object.hasOwnProperty.call(rules, key)) {
                const element = rules[key];
                for (const mgsKey in element) {
                    if (Object.hasOwnProperty.call(element, mgsKey)) {
                        const mgsElement = element[mgsKey];
                        let  digits = this?.G?.digits[mgsElement];
                        if (digits !== void 0) {
                            langOb['digits'] = digits;
                        }
                        let  attributes = this?.G?.attributes[mgsElement];
                        if (attributes !== void 0) {
                            langOb['attributes'] = attributes;
                        }
                    }
                }
            }
        }
        for (const key in rules) {
            if (Object.hasOwnProperty.call(rules, key)) {
                messages[key] = this?.G?.lang(langOb);
            }
        }
        return messages;
    }

    fReset(f) {
        $("#" + f)[0].reset();
    }
}
