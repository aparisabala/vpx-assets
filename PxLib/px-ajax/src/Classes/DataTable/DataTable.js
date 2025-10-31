import { PxConfig } from "../PxConfig";

export class DataTable extends PxConfig {

    makeAjaxDataTable(table, op = {}) {
        if(local){
            console.log(op);
        }
        let f = {};
        let s = [];
        f.glob = (op.glob == undefined) ? false : op.glob;
        f.searching = (op.searching == undefined) ? true : op.searching;
        f.ordering = (op.ordering == undefined) ? false : op.ordering;
        f.paging = (op.paging == undefined) ? true : op.paging;
        f.info = (op.info == undefined) ? true : op.info;
        f.pageLength = (op.pageLength === undefined) ? defaultDtSize : op.pageLength;
        f.responsive = (op.responsive == undefined) ? true : op.responsive;
        f.bLengthChange = (op.bLengthChange == undefined) ? true : op.bLengthChange;
        f.stateSave = (op.stateSave == undefined) ? false : op.stateSave;
        f.cache = (op.cache == undefined) ? false : op.cache;
        f.url = op.url || "";
        f.columns = op.columns || [];
        f.body = op.body || {};
        f.language = {
            url: baseurl + "../resources/lang/" + $("#locale").val() + "/dt.json"
        }
        if (op.select != undefined) {
            if (op.select) {
                s = [{
                    targets: 0,
                    checkboxes: {
                        'selectRow': true
                    },
                    render: function (data, type, row, meta) {
                        if (row?.can_select == "no") {
                            return '';
                        }
                        return '<input type="checkbox" class="dt-checkboxes">'
                    }
                }];
            }
        }
        f.selected = op.selected || [];
        let post_data = { _token: this?.G?.csrf_token, auth_uuid: $("#auth_uuid").val() };
        for (let key in f.body) {
            post_data[key] = f.body[key]
        }
        if ($("#" + table).length > 0) {
            let dt = $("#" + table).DataTable({
                paging: f.paging,
                searching: f.searching,
                searchDelay: 500,
                serverSide: true,
                "bStateSave": true,
                ordering: f.ordering,
                info: f.info,
                responsive: f.responsive,
                bLengthChange: f.bLengthChange,
                stateSave: f.stateSave,
                cache: f.cache,
                pageLength: f.pageLength,
                language: f.language,
                columnDefs: s,
                processing: "<span class='fa-stack fa-lg'>\n\ <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\</span>&nbsp;&nbsp;&nbsp;&nbsp;Processing ...",
                serverSide: true,
                lengthMenu: [
                    [10, 50, 100, 500, 1000, 1500, 2000],
                    [10, 50, 100, 500, 1000, 1500, 2000]
                ],
                ajax: {
                    type: "POST",
                    url: baseurl + f.url,
                    data: post_data,
                    dataSrc: function (data) {
                        op = { ...op, data: data, dataSrc: data.data }
                        if (local) {
                            console.log(data.data);
                        }
                        return data.data;
                    },
                    error: function (response) {
                        if (local) {
                            console.log(response);
                        }
                    }
                },
                columns: f.columns,
                select: {
                    style: 'multi'
                },
                order: [
                    [0, 'DESC']
                ],
                "fnDrawCallback": function (oSettings) {
                    let api = this.api();
                    if (typeof window[table] === 'function') {
                        window[table](table, api, op);
                    }
                }
            });
            this.selectAction(table, dt, op);
        }

    }
    selectAction(table, dt, op) {
        let context = this;
        $('#' + table + ' tbody').on('click', 'input[type="checkbox"]', function (e) {
            let $row = $(this).closest('tr');
            if (this.checked) {
                $row.addClass('selected');
            } else {
                $row.removeClass('selected');
            }
            context.showSelected(dt, op);
            e.stopPropagation();
        });
        $('#' + table + ' thead', dt.table().container()).on('click', 'input[type="checkbox"]', function (e) {
            if (this.checked) {
                let cb = $('#' + table + ' tbody input[type="checkbox"]');
                cb.prop('checked', true);
                cb.parent().parent().addClass('selected');
            } else {
                let cb = $('#' + table + ' tbody input[type="checkbox"]');
                cb.prop('checked', false);
                cb.parent().parent().removeClass('selected');
            }
            context.showSelected(dt,op);
            e.stopPropagation();
        });
    }
    showSelected(dt,op={}) {
        let count = dt.rows('.selected').data().length;
        if (count == "0") {
            $("#show_selected").html('');
            $("#show_selected_base").css({ marginLeft: "-500px" });
        } else {
            $("#show_selected_base").css({ marginLeft: 8 });
            $("#show_selected").html('Selected: ' + count)
        }
        if (op?.onSelectRows) {
            op?.onSelectRows(dt,op);
        }
    }
    getDtData(type, dt, col, c) {
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