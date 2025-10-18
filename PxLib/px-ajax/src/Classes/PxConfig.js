export class PxConfig {
    constructor() {
        this.G = this.getConfig();
    }

    /**
     * Get the configuration object for the PX library
     * @returns {Object} - The configuration object
     */
    getConfig(){
        return {
            csrf_token : $('meta[name="_token"]').attr('content'),
            baseUrl : $("#base-url").val(),
            uploadUrl : $("#service-domain").val() + "/summernote/",
            mgs:  (document.getElementById('language-pack')) ? JSON.parse(document.getElementById('language-pack').value) : {},
            digits: (document.getElementById('digits')) ? JSON.parse(document.getElementById('digits').value) : {},
            attributes: (document.getElementById('attributes')) ? JSON.parse(document.getElementById('attributes').value) : {},
            pageLang: ($("#page-lang").length > 0) ? JSON.parse($("#page-lang").val()) : null,
            policy: ($("#systemPolicies").length > 0) ? JSON.parse($("#systemPolicies").val()) : {},
            user_access : ($("#user_access").length > 0) ? JSON.parse($("#user_access").val()) : {},
            local: 'en',
            lang: function (op = {}) {
                let ob = {};
                let lang = this.mgs;
                for (const langKey in lang) {
                    if (Object.hasOwnProperty.call(lang, langKey)) {
                        const langElement = lang[langKey];
                        if (typeof (langElement) == "object") {
                            for (const key in langElement) {
                                if (Object.hasOwnProperty.call(langElement, key)) {
                                    const element = langElement[key].toString();
                                    ob[key] = this.getMatchedString(element, op);
                                }
                            }
                        }
                        if (typeof (langElement) == "string") {
                            ob[langKey] = this.getMatchedString(langElement, op);
                        }
                    }
                }
                return ob;
            },
            isEmptyObjcet: (obj) => {
                for (let key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        return false;
                    }
                }
                return true;
            },
            getMatchedString: (element,op) => {
                let str = element.replace(/:digits|:type|:attribute/gi, function (matched) {
                    let s = op[matched.split(":")[1]];
                    return (s == void 0) ? matched : s;
                });
                return str;
            }
        }
    }

}
