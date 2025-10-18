export class PxUtils {

    dynamicDom(op) {
        let { clickId = "", domId = "", cloneId = "", addRemoveclass = "", replaceClass = [] } = op;
        let context = this;
        $("#" + clickId).on("click", function () {
            let html = $("#" + domId).html();
            let d = $(html).find('.' + addRemoveclass).html('<i class="fa fa-minus p-2 cursor-pointer border rounded-1 required deleteField"></i>').parent().parent().html();
            $("#" + cloneId).append('<div class="row">' + d + '</div>');
            if (replaceClass.length > 0) {
                for (let i = 0; i < replaceClass.length; i++) {
                    context?.replaceID(replaceClass[i])
                }
            }
            context?.removeFiled('deleteField');
        });
    }

    replaceID(className = "") {
        let collection = $(document).find("." + className)
        if (collection.length > 0) {
            for (let i = 0; i < collection.length; i++) {
                collection[i].id = className + '.' + i + '_error'
            }
        }
    }
    removeFiled(removeClass) {
        $("." + removeClass).on("click", function () {
            $(this).parent().parent().parent().remove();
        })
    }

    makeArrayUnique(arr, prop) {
        return arr.filter((obj, index, self) =>
            index === self.findIndex((t) => (
                t[prop] === obj[prop]
            ))
        );
    }

    sortByKey(array, key, order = 'asc') {
        return array.sort((a, b) => {
            if (order === 'asc') {
                return a[key] < b[key] ? -1 : a[key] > b[key] ? 1 : 0;
            } else if (order === 'desc') {
                return b[key] < a[key] ? -1 : b[key] > a[key] ? 1 : 0;
            }
            return array;
        });
    }

    dp(op = {}) {
        const {element='dp'} = op;
        $('.'+element).datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            scrollMonth: false,
            scrollInput: false,
            ...op
        });
    }
}