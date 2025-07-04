let ModalAjaxCall = null;

$(document).ready(function () {
    $(document).on("click", ".modalOpen", function (e) {
        const btn = $(this);
        const ModalUrl = btn.data("modal-url");
        const DataId = btn.data("id");
        const LoderFunctionName =
            $(this).data("loder-function-name") ?? "setAjaxBtnLoader";
        const data = {
            id: DataId,
        };
        if (ModalUrl && ModalUrl != "") {
            if (ModalAjaxCall != null) ModalAjaxCall.abort();
            if (typeof window[LoderFunctionName] === "function") {
                window[LoderFunctionName](btn, true);
            }
            ModalAjaxCall = $.ajax({
                url: ModalUrl,
                data: data,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "POST",
                success: function (res) {
                    if (typeof window[LoderFunctionName] === "function") {
                        window[LoderFunctionName](btn, false);
                    }

                    // console.log();
                    if (res.status) {
                        $(".ajaxModal").remove();
                        $("body").append(res.modal);
                        $(".ajaxModal").modal("show");
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
    });
});

//documentation
//.modalOpen class in btn
//ajaxModal class use in your modal
/* data-loder-function-name (optional) -> default is setAjaxBtnLoader function. function name for set loder when process this function has two argument class name of form and state for loader by default display loader in submit btn of form */

/*
    data-id (optional)=>use for give id parameter to route action method
*/

/*
    data-modal-url = url which give html code in response following response required with http code 200
    {
        "modal": "html code",
        "status" : true or false
        "message": "message optional"
    }
*/
