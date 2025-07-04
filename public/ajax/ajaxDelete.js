let deleteAjaxCall = null;

function afterDeleteAction(res, swalResult) {
    //res is response of ajax (when status true and status  code 200)
    //swalAction is a event obj of swal dismiss
    // console.log(res, swalAction);

    if (res.redirect && res.redirect != "") {
        window.location.href = res.redirect;
    }
}
$(document).ready(function () {
    $(document).on("click", ".item-delete-btn", function (e) {
        const btn = $(this);
        const DeleteUrl = btn.data("delete-url");
        const DataId = btn.data("id");
        const Method = btn.data("method") ?? "GET";
        const ConfirmMessage = btn.data("confirm-message") ?? "Are You Sure ?";
        const afterDeleteActionMethod =
            btn.data("after-delete-function") ?? "afterDeleteAction";
        const LoderFunctionName =
            $(this).data("loder-function-name") ?? "setAjaxBtnLoader";
        const ajaxDataTableClass =
            $(this).data("ajax-data-table-class") ?? "ajaxDataTable";
        const otherData = $(this).data("ajax-other-data") ?? {};
        const data = {
            id: DataId,
            ...otherData
        };
        console.log("Delete => ", data);
        swalConfirmMessage({
            title: ConfirmMessage,
            action: (result) => {
                if (result.isConfirmed) {
                    if (DeleteUrl && DeleteUrl != "") {
                        if (deleteAjaxCall != null) deleteAjaxCall.abort();
                        if (typeof window[LoderFunctionName] === "function") {
                            window[LoderFunctionName](btn, true);
                        }
                        deleteAjaxCall = $.ajax({
                            url: DeleteUrl,
                            data: data,
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            method: Method,
                            success: function (res) {
                                if (typeof window[LoderFunctionName] === "function") {
                                    window[LoderFunctionName](btn, false);
                                }

                                // console.log();
                                if (res.status) {
                                    if ($(`.${ajaxDataTableClass}`)) {
                                        $(`.${ajaxDataTableClass}`).DataTable().ajax.reload()
                                    }
                                    swalMessage({
                                        title: "Success",
                                        text: res.message,
                                        action: (e) => {
                                            if (typeof window[afterDeleteActionMethod] === "function") {
                                                window[afterDeleteActionMethod](res, e);
                                            }


                                        },
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                if (typeof window[LoderFunctionName] === "function") {
                                    window[LoderFunctionName](btn, false);
                                }

                                var errorRes = xhr.responseJSON;
                                var status = xhr.status;
                                if (errorRes) {
                                    swalMessage({
                                        title: "Error",
                                        text: errorRes?.message ?? "",
                                        icon: "error",
                                    });
                                }
                            },
                        });
                    }
                }
            },
        });
    });
});
//class -> item-delete-btn
/* data-loder-function-name (optional) -> default is setAjaxBtnLoader function. function name for set loder when process this function has two argument class name of form and state for loader by default display loader in submit btn of form */

/* data-after-delete-function  (optional) => default is afterDeleteAction function. function name which is call after response status true and status code 200 it has two argument
        1. res -> response of ajax
        2. swalEventObj => swal dissmiss event obj
        ->in this method you set action that perform after success
*/
// data-ajax-other-data => json encoded string for send to request
// data-delete-url = > url  for request
// data-ajax-data-table-class => default is ajaxDataTable. name of data talbe class. which is reload after
//data-confirm-message (optional)=>default "Are You Sure ?" message. set confirm message.
