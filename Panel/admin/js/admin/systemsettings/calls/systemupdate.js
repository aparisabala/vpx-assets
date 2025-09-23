$(document).ready(function () {
    SeedTable("institute");
    let timeout = null;
    let second = 0;
    function SeedTable(table) {
        if ($("#onPressUpdate").length > 0) {
            $("#onPressUpdate").on("click", function () {
                AutoUpdate(table);
                timeout = setInterval(function(){
                    second++;
                    $("#took").html(second+" seconds")
                }, 1000);
            });
        }
    }

    function AutoUpdate(table) {
        px?.ajaxRequest({
            element: "onPressUpdate",
            type: "request",
            dataType: "json",
            body: { table },
            script: "admin/systemsettings/systemupdate/update",
            afterSuccess: {
                type: "api_response",
                afterLoad: (op) => {
                    $("#updateCount").html(op?.response?.data?.updateCount);
                    $("#currentTable").html(table);
                    if (op?.response?.data?.hasTable == "yes") {
                        AutoUpdate(op?.response?.data?.nextTable);
                    } else {
                        clearInterval(timeout);
                        setTimeout(()=>{
                            $("#status").html("Updated successfully in "+second+" seconds");
                        },1000);
                    }
                }
            }
        });
    }
});
