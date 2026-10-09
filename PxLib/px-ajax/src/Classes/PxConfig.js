export class PxConfig {
    constructor() {
        this.G = this.getConfig();
    }

    /**
     * Get the configuration object for the PX library
     * @returns {Object} - The configuration object
     */
    getConfig(){
        const parseValue = (id, fallback) => {
            const el = document.getElementById(id);
            if (!el || !el.value) {
                return fallback;
            }
            try {
                return JSON.parse(el.value);
            } catch (e) {
                console.error(`PX: invalid JSON in #${id}`, e);
                return fallback;
            }
        };
        return {
            csrf_token : $('meta[name="_token"]').attr('content'),
            baseUrl : $("#base-url").val(),
            uploadUrl : $("#service-domain").val() + "/summernote/",
            mgs: parseValue('language-pack', {}),
            digits: parseValue('digits', {}),
            attributes: parseValue('attributes', {}),
            pageLang: parseValue('page-lang', null),
            policy: parseValue('systemPolicies', {}),
            user_access : parseValue('user_access', {}),
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
                                    const element = String(langElement[key] ?? '');
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
