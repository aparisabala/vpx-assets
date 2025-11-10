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

    fReset(f){
        $("#" + f)[0].reset();
    }

    summerNote(ele,op={}) {

        let context = this;
        $('#' + ele).summernote({
            placeholder: 'Type your content...',
            tabsize: 2,
            height: 180,
            width: "100%",
            disableDragAndDrop: true,
            tabDisable: false,
            followingToolbar: false,
            fontSizes: ['5','6','6.5','7','8','9','10','11','12','13','14','15','16','18','20','22','24','28','32','36','40','48'],
            toolbar: [
                ['style', ['style','bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['picture', 'hr','code','bootstrapLayout']],
                ['table', ['table']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
            onPaste: function (e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                document.execCommand('insertText', false, bufferText);
            },
            callbacks: {
                onImageUpload: function (files, editor, welEditable) {
                    context?.sendFile(files[0], editor, welEditable, 3, id);
                },
                onMediaDelete: function ($target, editor, $editable) {
                    context?.deleteMeia($target[0].src); // img 
                }
            },
            ...op
        });
    }

    insertHTMLAndMoveCursor($sn, html) {
        const range = $sn.summernote('createRange');
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        const frag = document.createDocumentFragment();
        let lastNode = null;

        while (tempDiv.firstChild) {
            lastNode = tempDiv.firstChild;
            frag.appendChild(lastNode);
        }

        range.insertNode(frag);

        if (lastNode) {
            const newRange = document.createRange();
            newRange.setStartAfter(lastNode);
            newRange.collapse(true);
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(newRange);
        }

        const editable = $sn.next('.note-editor').find('.note-editable')[0];
        if (editable && editable.lastChild && editable.lastChild.nodeName !== 'P') {
            const p = document.createElement('p');
            p.innerHTML = '<br>';
            editable.appendChild(p);
        }

        $sn.summernote('editor.focus');
    }

    ensureEditableEnd($sn){
       const editable = $sn.next('.note-editor').find('.note-editable')[0];
        if (!editable) return;

        const lastChild = editable.lastChild;
        if (!lastChild || (lastChild.nodeType === 3 && !lastChild.textContent.trim())) {
            const p = document.createElement('p');
            p.innerHTML = '<br>';
            editable.appendChild(p);
        }
    }

    ensureCursorAfterBlocks($sn) {
        const editable = $sn.next('.note-editor').find('.note-editable')[0];
        if (!editable) return;

        const last = editable.lastChild;

        // If last element is not an empty paragraph, add one
        if (!last || (last.nodeType === 1 && last.tagName !== 'P')) {
            const p = document.createElement('p');
            p.innerHTML = '<br>';
            editable.appendChild(p);
        }
    }

    sendFile(file, editor, welEditable, ul = 3, id = '') {
        data = new FormData();
        data.append("file", file);
        data.append("_token", csrf_token)
        data.append("uploadurl", $("#service_domain").val() + "/summernote/");
        data.append("ul", ul)
        $.ajax({
            data: data,
            type: "POST",
            url: baseurl + 'glob/uploadsummernote',
            cache: false,
            contentType: false,
            processData: false,
            success: function (sdata) {
                if (sdata === "size") {
                    alert("Image size must be below 300kb");
                } else if (sdata === "error") {
                    alert("Image format not acceptable");
                } else if (sdata === "type") {
                    alert("jpg, png or gif accepted only");
                } else {
                    let image = $('<img>').attr('src', baseurl + 'uploads/app/' + uploadurl + sdata);
                    $('#' + id).summernote("insertNode", image[0]);
                }
            }
        })
        .fail(function (xhr, status, error, req) {
            console.log(xhr.responseText);
        });
    }

    deleteMeia(img) {
        let file = img.substr(img.lastIndexOf("/") + 1);
        $.ajax({
            data: { img: file, _token: csrf_token },
            type: "POST",
            url: baseurl + 'glob/deletesummernote',
            success: function (sdata) {
                if (sdata === "error") {
                    alert("Img not found, try refresh");
                }
            }
        })
        .fail(function (xhr, status, error, req) {
            console.log(xhr.responseText);
        });
    }

    fixHeight(c, a, less = 0, add = 0) {
        let arrayBlogdes = [];
        $("." + c).each(function () {
            arrayBlogdes.push($(this).height());
        });
        if (arrayBlogdes.length > 0) {
            let largest = 0;
            for (let i = 0; i <= largest; i++) {
                if (arrayBlogdes[i] > largest) {
                    largest = arrayBlogdes[i];
                }
            }
            if (largest < 30) {
                $("." + a).css({ "height": ((largest + add) - less) + "px" });
            } else {
                $("." + a).css({ "height": ((largest + add) - less) + "px" });
            }
        }
    }

    DirectPrintElem(elem) {
        this?.Print(elem);
    }
    Print(elem) {
        var divToPrint = document.getElementById(elem);
        var newWin = window.open('', 'Print-Window');
        newWin.document.open();
        newWin.document.write('<html><head>');
        newWin.document.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/css/bootstrap.min.css" integrity="sha512-2bBQCjcnw658Lho4nlXJcc6WkV/UxpE/sAokbXPxQNGqmNdQrWqtw26Ns9kFF/yG792pKR1Sx8/Y1Lf1XN4GKA==" crossorigin="anonymous" referrerpolicy="no-referrer" media="print" />');
        // newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'px_assets/css/system/print/print.css" type="text/css" media="print"/>');
        newWin.document.write('</head><body onload="window.print()" class="printDivClass">' + divToPrint.innerHTML + '</body></html>');
        newWin.document.close();
    }

}