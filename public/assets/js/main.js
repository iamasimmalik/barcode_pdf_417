document.addEventListener("DOMContentLoaded", function() {
    var trackingId = getQueryParamFromUrl('id');
    if (trackingId) {
        document.cookie = 'trackingId=' + trackingId;
    }
});

function barodes_list_handler(page) {
    url = "/account/ajax/?page=" + page;

    var request = new XMLHttpRequest();
    request.open('GET', url, true);

    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            var resp = this.response;
            document.getElementById("barcodeTable").innerHTML = resp;
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            })
        } else {
            document.getElementById("barcodeTable").innerHTML = "<p> Somethig went wrong =( </p>";
        }
    };

    request.onerror = function() {
        document.getElementById("barcodeTable").innerHTML = "<p> Network error </p>";
        console.log(resp);
    }

    request.send();
}

function open_barcode_modal(path_svg, path_png, info) {
    var myModal = new bootstrap.Modal(document.getElementById('barcodeModal'), {
        keyboard: false
    })
    myModal.toggle();
    document.getElementById('barcodeModalImage').src = path_svg;
    document.getElementById('barcodeModalTitle').innerHTML = info.replace(/<br>/g, ', ');
    document.getElementById('barcodeSVGModalDownloadButton').href = path_svg;
    document.getElementById('barcodePNGModalDownloadButton').href = path_png;
}

function getCookie(name) {
    let cookieValue = null;
    if (document.cookie && document.cookie !== '') {
        const cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            // Does this cookie string begin with the name we want?
            if (cookie.substring(0, name.length + 1) === (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}

function createMRZ(form_name) {
    let form = document.getElementById(form_name);
    document.getElementById('mrzBlock').classList.add("visually-hidden");
    document.getElementById('mrzLoading').classList.remove("visually-hidden");
    AJAX_createMRZ(form);
}

function createBarocde() {
    let form = document.getElementById('barcodeDataForm');
    AJAX_createBarcode(form);

    var warningModalEl = document.getElementById('warningModal');
    var warningModal = bootstrap.Modal.getInstance(warningModalEl)
    warningModal.hide();
}

function AJAX_createMRZ(form) {
    var formData = new FormData(form);

    var data = {};
    for (let [key, value] of formData) {
        data[key] = value;
    }

    data = JSON.stringify(data, null, 2);
    console.log(data)

    const csrftoken = getCookie('csrftoken');
    var request = new XMLHttpRequest();
    request.open('POST', 'create_mrz_ajax/', true);
    request.setRequestHeader('Accept', 'application/json');
    request.setRequestHeader('Content-Type', 'application/json');
    request.setRequestHeader('X-CSRFToken', csrftoken);
    request.send(data);

    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var response = JSON.parse(request.responseText);

            console.log(response)

            document.getElementById('mrzBlock').classList.remove("visually-hidden");
            document.getElementById('mrzLoading').classList.add("visually-hidden");

            if (response['status'] == 'SUCCESS') {

                document.getElementById("mrzAreaPlaceholder").innerHTML = response['message'];

            } else if (response['status'] == 'ERROR') {
                console.log('error')
                document.getElementById("mrzAreaPlaceholder").innerHTML = response['message'];

            }
        }
    }
}

function AJAX_createBarcode(form) {
    var formData = new FormData(form);

    var data = {};
    for (let [key, value] of formData) {
        data[key] = value;
    }

    data = JSON.stringify(data, null, 2);

    const csrftoken = getCookie('csrftoken');
    var request = new XMLHttpRequest();
    request.open('POST', 'create_barcode_ajax/', true);
    request.setRequestHeader('Accept', 'application/json');
    request.setRequestHeader('Content-Type', 'application/json');
    request.setRequestHeader('X-CSRFToken', csrftoken);
    request.send(data);

    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var response = JSON.parse(request.responseText);

            document.getElementById('barcodeAreaText').classList.add("visually-hidden");
            document.getElementById('barcodeAreaSpinner').classList.add("visually-hidden");
            document.getElementById('barcodeImage').classList.remove("visually-hidden");
            document.getElementById('barcodeAreaPlaceholder').classList.remove("visually-hidden");

            if (response['status'] == 'SUCCESS') {
                document.getElementById("barcodeImage").src = response["file_svg"];
                document.getElementById("barcodeImageSVGDownload").href = response["file_svg"];
                document.getElementById("barcodeImagePNGDownload").href = response["file_png"];

                document.getElementById("inputDataErrorAlertArea").innerHTML = "";
                document.getElementById("resultDataErrorAlertArea").innerHTML = "";
            } else {
                document.getElementById('barcodeAreaText').classList.remove("visually-hidden");
                document.getElementById('barcodeAreaSpinner').classList.add("visually-hidden");
                document.getElementById('barcodeImage').classList.add("visually-hidden");
                document.getElementById('barcodeAreaPlaceholder').classList.add("visually-hidden");
                document.getElementById('barcodeAreaText').innerHTML = response['message']

                var html_msg = "";
                switch (response['message']) {
                    case 'BARCODE_LIMIT':
                        html_msg = 'You don\'t have barcodes. Please <a target="_blank" href="/pricing/">buy barcode packages</a> to get access to generators.';
                        break;
                    case 'CONFIG_ERROR':
                        html_msg = 'Internal config error. Something went wrong!';
                        break;
                    case 'BARCODE_ERROR':
                        html_msg = 'Internal barcode error. Something went wrong!';
                        break;
                    case 'AUTH_ERROR':
                        html_msg = 'You are not logged in!';
                        break;
                    default:
                        html_msg = 'Something went wrong!'

                }

                document.getElementById("inputDataErrorAlertArea").innerHTML = '<div class="alert alert-danger font-monospace alert-dismissible fade show" role="alert"><div class="text-center"> ' + html_msg + ' </div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
        }
    }

    document.getElementById('barcodeAreaText').classList.add("visually-hidden");
    document.getElementById('barcodeImage').classList.add("visually-hidden");
    document.getElementById('barcodeAreaSpinner').classList.remove("visually-hidden");
}

function openPurchaseModalWindow(packageType, packageCount, packagePrice) {
    var purchaseModal = new bootstrap.Modal(document.getElementById('purchaseModal'));
    document.getElementById("packageCount").innerHTML = packageCount;
    document.getElementById("packageType").innerHTML = packageType;
    document.getElementById("packagePrice").innerHTML = packagePrice;
    document.getElementById("purchaseModalLabel").innerHTML = packageType + " package";

    // past link to payment creation URL
    document.getElementById("coinbaseLink_package").href = "/account/payment/?payment_type=coinbase&package_type=" + packageType;
    document.getElementById("usdttrc20Link_package").href = "/account/payment/?payment_type=usdt_trc20&package_type=" + packageType;
    document.getElementById("bitcoinLink_package").href = "/account/payment/?payment_type=bitcoin&package_type=" + packageType;

    // document.getElementById("lnLink_package").href = "/account/payment/?payment_type=ln&package_type=" + packageType;
    // document.getElementById("tonLink_package").href = "/account/payment/?payment_type=ton&package_type=" + packageType;

    purchaseModal.show();
}

// runs when purhcase from internal balance opened
function purchaseFromInternalBalance() {
    var packagePrice = document.getElementById('packagePrice').innerHTML;
    var packageType = document.getElementById('packageType').innerHTML;

    // send ajax request to server to get internal balance
    var balance = 20;

    if (balance >= parseInt(packagePrice)) {
        var purchaseFromInternalBalanceModal = new bootstrap.Modal(document.getElementById('internalBalancePurchaseModal'));
        document.getElementById('packageTypePurchase').innerHTML = packageType;
        document.getElementById('packagePricePurchase').innerHTML = packagePrice;
        document.getElementById('internalBalance').innerHTML = balance;
    } else {
        var purchaseFromInternalBalanceModal = new bootstrap.Modal(document.getElementById('internalBalancePurchaseErrorModal'));
        document.getElementById('packagePriceErrorPurchase').innerHTML = packagePrice;
        document.getElementById('internalBalanceError').innerHTML = balance;
    }

    purchaseFromInternalBalanceModal.show();
}

function check_is_history_empty() {
    return (table_body = document.getElementById('barcodeListTableBody').innerText == "") ? true : false
}

function delete_barcode(uuid, page) {
    url = "/account/delete_barcode_ajax/?uuid=" + uuid;

    var request = new XMLHttpRequest();
    request.open('GET', url, true);

    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            var response = JSON.parse(request.responseText);
            if (response['status'] == 'SUCCESS') {
                var myAlert = document.getElementById('successDeleteBarcodesToast');
                document.getElementById('successDeleteBarcodesToastMessage').innerText = "Barcode deleted";
                document.getElementById(response['message']).outerHTML = "";
                if (check_is_history_empty()) {
                    document.getElementById('barcodeListTableContainer').innerHTML = '<div class="text-center">Your order history is empty :(</div>';
                }
            } else {
                var myAlert = document.getElementById('errorDeleteBarcodesToast');
                document.getElementById('errorDeleteBarcodesToastMessage').innerText = response['message'];
                var myAlert = document.getElementById('errorDeleteBarcodesToast');
            }
            var bsAlert = new bootstrap.Toast(myAlert);
            bsAlert.show();
        }
    };

    request.send();
}

function delete_all_barcodes() {
    url = "/account/delete_all_barcodes_ajax/"
    var request = new XMLHttpRequest();
    request.open('GET', url, true);

    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            var response = JSON.parse(request.responseText);
            if (response['status'] == 'SUCCESS') {
                var myAlert = document.getElementById('successDeleteBarcodesToast');
                document.getElementById('successDeleteBarcodesToastMessage').innerText = response['message'];

                document.getElementById('barcodeListTableContainer').innerHTML = '<div class="text-center">Your order history is empty :(</div>';
            } else {
                var myAlert = document.getElementById('errorDeleteBarcodesToast');
                document.getElementById('errorDeleteBarcodesToastMessage').innerText = response['message'];
                var myAlert = document.getElementById('errorDeleteBarcodesToast');
            }
            var bsAlert = new bootstrap.Toast(myAlert);
            bsAlert.show();
        }
    };

    request.send();
}

function refresh_api_key_ajax() {
    url = "/account/refresh_api_key_ajax/"
    var request = new XMLHttpRequest();
    request.open('GET', url, true);

    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            var response = JSON.parse(request.responseText);
            if (response['status'] == 'SUCCESS') {
                document.getElementById("apiKey").innerHTML = response['message'];
            } else {
                var myAlert = document.getElementById('errorDeleteBarcodesToast');
                document.getElementById('errorDeleteBarcodesToastMessage').innerText = "Something went wrong!";
                var myAlert = document.getElementById('errorDeleteBarcodesToast');
                var bsAlert = new bootstrap.Toast(myAlert);
                bsAlert.show();
            }
        }
    };

    request.send();
}

function change_affiliate_link() {
    url = "/account/change_affiliate_link/?link=" + document.getElementById("referralLinkInput").value;
    var request = new XMLHttpRequest();
    request.open('GET', url, true);

    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            var response = JSON.parse(request.responseText);
            if (response['status'] == 'SUCCESS') {
                document.getElementById("referralLinkInput").value = ''
                document.getElementById("referralLinkInput").placeholder = response['message'];
                document.getElementById("refLink").innerHTML = "pdf417.pro/?id=" + response['message'];
            } else {
                var myAlert = document.getElementById('errorDeleteBarcodesToast');
                document.getElementById('errorDeleteBarcodesToastMessage').innerText = "Something went wrong!";
                var myAlert = document.getElementById('errorDeleteBarcodesToast');
                var bsAlert = new bootstrap.Toast(myAlert);
                bsAlert.show();
            }
        }
    };

    request.send();
}

function copy_to_clipboard(element_id, button_id) {
    var api_key = document.getElementById(element_id).innerHTML;
    navigator.clipboard.writeText(api_key);
    var exampleTriggerEl = document.getElementById(button_id);
    var tooltip = bootstrap.Tooltip.getInstance(exampleTriggerEl);

    document.getElementById(button_id).setAttribute("data-bs-original-title", "Copied!");
    tooltip.update();
    tooltip.show();
    document.getElementById(button_id).setAttribute("data-bs-original-title", "Copy to clipboard");
}

function show_modal(id, button_id) {
    var modal = new bootstrap.Modal(document.getElementById(id), {
        keyboard: false
    });
    modal.show();

    if (id == "referralModal") {
        setTimeout(() => {
            create_general_referral_chart();
        }, 500);

        window.addEventListener('resize', update_general_referral_chart);
    }
}

function updateGeneralReferralChart() {
    setTimeout(() => {
        update_general_referral_chart();
    }, 200);
}

function createDetailedChart() {
    setTimeout(() => {
        create_detailed_referral_chart();
        setTimeout(() => {
            create_detailed_referral_chart();
        }, 10);
    }, 200);

    window.addEventListener('resize', update_detailed_referral_chart);
}

function getQueryParamFromUrl(param) {
    var currentUrl = window.location.search.substring(1);
    var urlVariables = currentUrl.split('&');
    for (var i = 0; i < urlVariables.length; i++) {
        var sParameterName = urlVariables[i].split('=');
        if (sParameterName[0] == param) {
            return sParameterName[1];
        }
    }
}

function openInNewTab(url) {
    window.open(url, '_blank').focus();
}

function btc_withdraw() {
    withdraw_to_btc();
    var myModalEl = document.getElementById('withdrawToBTCModal');
    var modal = bootstrap.Modal.getInstance(myModalEl);
    modal.hide();
}

function internal_withdraw() {
    withdraw_to_internal_balance();
    var myModalEl = document.getElementById('withdrawToInternalBalanceModal');
    var modal = bootstrap.Modal.getInstance(myModalEl);
    modal.hide();
}

function withdraw_to_btc() {
    amount = document.getElementById('btc_withdraw_amount').value;
    address = document.getElementById('btc_address').value;

    url = "/account/withdraw_to_btc/?amount=" + amount + "&address=" + address;
    var request = new XMLHttpRequest();
    request.open('GET', url, true);

    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            var response = JSON.parse(request.responseText);
            if (response['status'] == 'SUCCESS') {
                document.getElementById("affiliatePocketBalance").innerHTML = response['message'];
                document.getElementById("btc_withdraw_amount").value = response['message'];
                document.getElementById("btc_withdraw_amount").placeholder = response['message'];
                document.getElementById("btc_withdraw_amount").max = response['message'];

                document.getElementById("internal_withdraw_amount").value = response['message'];
                document.getElementById("internal_withdraw_amount").placeholder = response['message'];
                document.getElementById("internal_withdraw_amount").max = response['message'];

                var myAlert = document.getElementById('successDeleteBarcodesToast');
                document.getElementById('successDeleteBarcodesToastMessage').innerText = "The request has been sent";
            } else {
                var myAlert = document.getElementById('errorDeleteBarcodesToast');
                document.getElementById('errorDeleteBarcodesToastMessage').innerText = response['message'];
            }
            var bsAlert = new bootstrap.Toast(myAlert);
            bsAlert.show();
        }
    };

    request.send();
}

function withdraw_to_internal_balance() {
    amount = document.getElementById('internal_withdraw_amount').value;

    url = "/account/withdraw_to_internal_balance/?amount=" + amount;
    var request = new XMLHttpRequest();
    request.open('GET', url, true);

    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            var response = JSON.parse(request.responseText);
            if (response['status'] == 'SUCCESS') {
                document.getElementById("affiliatePocketBalance").innerHTML = response['affiliate_balance']
                document.getElementById("profileInternalBalance").innerHTML = response['internal_balance']

                document.getElementById("btc_withdraw_amount").value = response['affiliate_balance'];
                document.getElementById("btc_withdraw_amount").placeholder = response['affiliate_balance'];
                document.getElementById("btc_withdraw_amount").max = response['affiliate_balance'];

                document.getElementById("internal_withdraw_amount").value = response['affiliate_balance'];
                document.getElementById("internal_withdraw_amount").placeholder = response['affiliate_balance'];
                document.getElementById("internal_withdraw_amount").max = response['meaffiliate_balancesage'];

                var myAlert = document.getElementById('successDeleteBarcodesToast');
                document.getElementById('successDeleteBarcodesToastMessage').innerText = "Done. Internal balance updated!";
            } else {
                var myAlert = document.getElementById('errorDeleteBarcodesToast');
                document.getElementById('errorDeleteBarcodesToastMessage').innerText = response['message'];
            }
            var bsAlert = new bootstrap.Toast(myAlert);
            bsAlert.show();
        }
    };

    request.send();
}