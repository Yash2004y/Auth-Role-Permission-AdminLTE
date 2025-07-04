function afterSuccessForm(res, swalAction=null) {
    //res is response of ajax (when status true and status  code 200)
    //swalAction is a event obj of swal dismiss
    // console.log(res, swalAction);

    if (res.redirect && res.redirect != "") {
        window.location.href = res.redirect;
    }
}
// setAjaxBtnLoader($("button[type='submit']"), true);

$(document).ready(function () {
    $(document).on("submit", ".ajaxForm", function (e) {
        e.preventDefault();
        const URL = $(this).prop("action");
        const form = $(this);
        const submitBtn = form.find("button[type='submit']");
        const LoderFunctionName =
            $(this).data("loder-function-name") ?? "setAjaxBtnLoader";
        const BeforeAjaxCall = $(this).data("before-ajax-call-function");
        const METHOD = $(this).prop("method").toUpperCase() ?? "GET";
        const CommonErrorClass =
            $(this).data("common-error-class") ?? "error-common";
        const AfterSuccessForm =
            $(this).data("after-success-function-name") ?? "afterSuccessForm";
        const ajaxDataTableClass =
            $(this).data("ajax-data-table-class") ?? "ajaxDataTable";
        const ajaxModalClass =
            $(this).data("ajax-modal-class") ?? "ajaxModal";

        form.find(`.${CommonErrorClass}`).html("");
        // console.log(`.${CommonErrorClass}`)
        // const submitter = e.originalEvent?.submitter;
        var formData = new FormData(this);
        if (METHOD == "GET") {
            formData = $(this).serialize();
        }
        // Optional: debug log
        console.log("method =>", METHOD);
        console.log("Form Data =>");
        if (METHOD != "GET") {
            for (let pair of formData.entries()) {
                console.log(pair[0] + ": " + pair[1]);
            }
        }

        flag = true;
        if (typeof window[BeforeAjaxCall] === "function") {
            flag = window[BeforeAjaxCall](form, formData) ?? true;
        }
        if (!flag) return;

        if (URL && URL != "") {
            //run loder
            if (typeof window[LoderFunctionName] === "function") {
                window[LoderFunctionName](submitBtn, true);
            }
            $.ajax({
                url: URL,
                data: formData,
                method: METHOD,
                success: function (res) {
                    //stop loder
                    if (typeof window[LoderFunctionName] === "function") {
                        window[LoderFunctionName](submitBtn, false);
                    }

                    var isDisplayMessage = res?.isDisplaySuccessMessage ?? true;
                    // console.log(res);
                    if (res.status) {
                        if (isDisplayMessage) {
                            swalMessage({
                                title: "Success",
                                text: res.message,
                                action: (e) => {
                                    if (typeof window[AfterSuccessForm] === "function") {
                                        window[AfterSuccessForm](res, form, e);
                                    }
                                    if ($(`.${ajaxModalClass}`)) {
                                        $(`.${ajaxModalClass}`).modal('hide');
                                    }
                                    if ($(`.${ajaxDataTableClass}`)) {
                                        $(`.${ajaxDataTableClass}`).DataTable().ajax.reload()
                                    }

                                },
                            });
                        }
                        else {
                            if (typeof window[AfterSuccessForm] === "function") {
                                window[AfterSuccessForm](res, form);
                            }
                        }

                    } else {
                        swalMessage({
                            text: res.message,
                            title: "Error",
                            icon: "error",
                        });
                    }
                },
                dataType: "json",
                processData: false,
                contentType: false,
                error: function (xhr, status, error) {
                    //stop loder
                    if (typeof window[LoderFunctionName] === "function") {
                        window[LoderFunctionName](submitBtn, false);
                    }
                    var errorRes = xhr.responseJSON;
                    var status = xhr.status;
                    if (status == 422) {
                        var error = errorRes?.error || errorRes?.errors;
                        Object.entries(error).forEach((item, index) => {
                            const [Key, Value] = item;
                            form.find(`.${Key}-error`).html(Value);
                            // console.log(item);
                        });
                    } else {
                        if (errorRes?.errors || errorRes?.error) {
                            var error = errorRes?.error || errorRes?.errors;
                            Object.entries(error).forEach((item, index) => {
                                const [Key, Value] = item;
                                form.find(`.${Key}-error`).html(Value);
                                // console.log(item);
                            });
                        }
                        else {
                            swalMessage({
                                title: "Error",
                                text: errorRes?.message ?? "",
                                icon: "error",
                            });
                        }
                    }
                },
            });
        }
    });
});

//documentation

/* data-before-ajax-call-function (optional) -> function name which is call before call ajax this method which must return boolean value. this method has following two parameter
    1.form => object of form
    2.formData => formData object base on form method
*/
/* data-loder-function-name (optional) -> default is setAjaxBtnLoader function. function name for set loder when process this function has two argument class name of form and state for loader by default display loader in submit btn of form */

/*data-common-error-class (optional) -> default is error - common class. set class name which available in each error display span or small(use for clear error)*/

/* data-after-success-function-name  (optional) => default is afterSuccessForm function. function name which is call after response status true and status code 200 it has two argument
        1. res -> response of ajax
        2. form => current form object
        2. swalEventObj => swal dissmiss event obj
        ->in this method you set action that perform after  success
*/

// data-ajax-data-table-class => default is ajaxDataTable. name of data talbe class. which is reload after success
// data-common-error-class this class and error class must be inside form tag

//all override method defind after utils.js
