import { getExcelBody } from '@app/lib/excel/boostrap_xlsx.js';
import { PxConfig } from '../PxConfig.js';
export class ExcelGeneration extends PxConfig {


    dowloadExcel(op){
        let btn = op?.btn ?? "excelDownload";
        $("#" + btn).unbind('click');
        let context = this;
        $("#" + btn).on("click", function () {
            let newOp = { ...op, ...JSON.parse($(this).attr('data-excel-op') ?? '{"no":"no"}') };
            console.log(newOp);
            context?.MakeExcel(newOp);
        });
    }

    MakeExcel(op = {}) {
        const { file_name = "file_name", dataTable = undefined, dataSrc = [], columns = [], pdf = [] } = op;
        if (dataSrc.length == 0) {
            data = [];
        }
        op['filterColumn'] = [...columns].filter((item, key) => { return pdf.includes(key); });
        let { body = [], width = [] } = getExcelBody(dataSrc, op);
        if (body.length > 0) {
            var wb = XLSX.utils.book_new();
            var ws = XLSX.utils.aoa_to_sheet(body);
            for (var i = 0; i < width.length; i++) {
                ws['!cols'] = ws['!cols'] || [];
                ws['!cols'].push({ wch: width[i] });
            }
            XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
            XLSX.writeFile(wb, file_name + '.xlsx');
        }
    }
}