import { PxConfig } from '../PxConfig.js';
import { html_to_pdfmake } from '@app/lib/pdfmake/html_to_pdfmake.js';
export class PdfGeneration extends PxConfig {

    dowloadPdf(op = {},ajaxRequest) {
        let btn = op?.btn ?? "pdfDownload";
        $("#" + btn).unbind('click');
        let context = this;
        $("#" + btn).on("click", function () {
            if (local) {
                console.log(op);
            }
            let newOp = { ...op, ...JSON.parse($(this).attr('data-pdf-op') ?? '{"no":"no"}') };
            context?.MakePdf(newOp,ajaxRequest);
        });
    }

    MakePdf(op = {},ajaxRequest) {

        pdfMake.fonts = {
            Roboto: {
                normal: baseurl + 'css/px/fonts/english/RobotoRegular.ttf',
                bold: baseurl + 'css/px/fonts/english/RobotoBold.ttf',
                italics: baseurl + 'css/px/fonts/english/RobotoItalic.ttf',
                bolditalics: baseurl + 'css/px/fonts/english/RobotoBoldItalic.ttf',
            }
        }
        pdfMake.tableLayouts = {
            allDashBorders: {
                hLineStyle: function (i, node) {
                    return { dash: { length: 3, space: 5 } }
                },
                vLineStyle: function (i, node) {
                    return { dash: { length: 3, space: 5 } }
                },
            },
            admitCardBorder: {
                hLineWidth: function (i, node) {
                    return 2;
                },
                vLineWidth: function (i) {
                    return 2;
                },
                hLineColor: function (i) {
                    return '#252161';
                },
                vLineColor: function (i) {
                    return '#252161';
                },
            },
            allBorders: {
                hLineWidth: function (i, node) {
                    return 1;
                },
                vLineWidth: function (i) {
                    return 1;
                },
                hLineColor: function (i) {
                    return 'black';
                },
                paddingLeft: function (i) {
                    return 1;
                },
                paddingRight: function (i, node) {
                    return 1;
                }
            },
            horizontalBorders: {
                hLineWidth: function (i, node) {
                    return 1;
                },
                vLineWidth: function (i) {
                    return 0;
                },
                hLineColor: function (i) {
                    return '#d1d1d1';
                },
                paddingLeft: function (i) {
                    return 8;
                },
                paddingRight: function (i, node) {
                    return 8;
                }
            }
        };

        const { file_name = "file_name", id = "pdf", dataTable = undefined, pdfFonts = [], tableLayouts = [] } = op;
        let docDefination = { defaultStyle: { font: 'Roboto' }, ...html_to_pdfmake(op) };
        if (local) {
            console.log(docDefination);
        }
        pdfFonts.map((font) => {
            pdfMake = {
                ...pdfMake,
                fonts: {
                    ...pdfMake?.fonts,
                    [font?.name]: {
                        normal: font?.n,
                        bold: font?.n,
                        italics: font?.i,
                        bolditalics: font?.bi,
                    }
                }
            }
        });
        tableLayouts.map((layout) => {
            pdfMake = {
                ...pdfMake,
                tableLayouts: {
                    ...pdfMake?.tableLayouts,
                    [layout?.name]: layout?.value
                }
            }
        });
        $("#theDownloadLoader").show();
        pdfMake.createPdf(docDefination).download(file_name + ".pdf", function () {
            const { script, body = {} } = op;
            $("#theDownloadLoader").hide();
            if (script) {
                ajaxRequest({
                    element: id,
                    script,
                    body,
                    dataType: "json",
                    type: "request",
                    afterSuccess: {
                        type: "inflate_response_data"
                    }
                });
            }
        });
    }

}