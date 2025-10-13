import { addPageNumber, createDomObject, sanitizeHtml, splitArray, tableStack } from "./bootstrap_pdfmake";

export function html_to_pdfmake(op = {}) {
    let {id="pdf",dataTable="no",dataSrc=[],pdf=[],columns=[],perPage=50} = op;
    let defaultValues = {
        pageOrientation: op?.pageOrientation ?? "portrait",
        pageSize: op?.pageSize ?? "A4",
        pageMargins: op?.pageMargins ?? [15,50,15,15],
    };
    let content = [];
    let images = {};
    let background = [];
    let watermark = {};
    const parentElement = document.getElementById(id);
    if (parentElement) {
        switch (dataTable) {
            case "yes":
                if(local) {
                    console.log(dataSrc);
                }
                if(dataSrc.length == 0) {
                    content = [];
                }
                if(perPage) {
                    dataSrc = splitArray(dataSrc,perPage);
                }
                op['filterColumn'] = [...columns].filter((item,key)=>{ return pdf.includes(key);});
                for (let index = 0; index < dataSrc.length; index++) {
                    let ob = {
                        stack: [tableStack(dataSrc[index],op)]
                    };
                    if(index !== (dataSrc.length - 1)) {
                        ob.pageBreak = "after";
                    }
                    content.push(ob);
                    for (let i = 0; i < dataSrc[index].length; i++) {
                        for (let j = 0; j < op['filterColumn'].length; j++) {
                            const col = op['filterColumn'][j];
                            if (col?.renderData) {
                                let type = col?.renderData?.type ?? "text";
                                if(type == "image") {
                                    images["image_"+dataSrc[index][i]?.id] = dataSrc[index][i][col?.renderData?.data];
                                }
                            }
                        }
                    }
                }
                break;
        
            default:
                let pdfData = parentElement.querySelectorAll('.px-pdf-body');
                if (pdfData && pdfData[0]) {
                    const pdfBody = createDomObject(sanitizeHtml(pdfData[0],"px-pdf-body"));
                    if (pdfBody) {
                        const { pdf = {}, stack } = pdfBody;
                        content = stack;
                        defaultValues = {
                            ...defaultValues,
                            ...pdf,
                        }
                    }
                }
                break;
        }
        let header = parentElement.querySelectorAll('.px-pdf-header');
        if (header) {
            let headerObject = createDomObject(header[0]);
            defaultValues = {
                ...defaultValues,
                header: function (currentPage, pageCount, pageSize) {
                    let injectPageNumber = {...headerObject,stack: addPageNumber(headerObject?.stack,{currentPage, pageCount},"tp")};
                    return [injectPageNumber];
                }
            }
        }
        let footer = parentElement.querySelectorAll('.px-pdf-footer');
        if (footer) {
            let footerObject = createDomObject(footer[0]);
            defaultValues = {
                ...defaultValues,
                footer: function (currentPage, pageCount) {
                    let injectPageNumber =  {...footerObject,stack: addPageNumber(footerObject?.stack,{currentPage, pageCount},"pp")};
                    return [injectPageNumber];
                } 
            }
        }
        let domImages = parentElement.querySelectorAll('img');
        if (domImages) {
            domImages.forEach((imageElement) => {
                if (imageElement.hasAttribute('name')) {
                    images[imageElement.getAttribute('name')] = imageElement.getAttribute('src');
                }
            });
        }
        let domBackground = parentElement.querySelectorAll('.px-pdf-bg-image');
        if (domBackground && domBackground[0]) {
            let bgObject = createDomObject(domBackground[0]);
            background.push(bgObject);
        }

        let domCutomWaterMark = parentElement.querySelectorAll('.px-pdf-watermark-image');
        if (domCutomWaterMark && domCutomWaterMark[0]) {
            let domCutomWaterMarkObject = createDomObject(domCutomWaterMark[0]);
            background.push(domCutomWaterMarkObject);
        }

        let domWatermark = parentElement.querySelectorAll('.px-pdf-watermark');
        if (domWatermark && domWatermark[0]) {
            let watremarkStyles = {};
            if (domWatermark[0].hasAttribute('data-style')) {
                watremarkStyles = JSON.parse(domWatermark[0].getAttribute('data-style'))
            }
            let watermarkObject = {
                text: domWatermark[0].textContent.trim(),
                ...watremarkStyles,
            };
            watermark = watermarkObject;
        }

    } else {
        console.log(`Element with ID '${parentElement}' not found.`);
    }
    return {
        content,
        ...defaultValues,
        images,
        background,
        watermark
    }
}
