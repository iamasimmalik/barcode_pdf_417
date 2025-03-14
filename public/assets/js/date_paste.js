window.onload = function() {
    document.querySelector("#inputBrithDate").onpaste = function(event) {
        this.value = event.clipboardData.getData('text/plain').replace(/[\/\.-]/g, '');
    }
    document.querySelector("#inputIssueDate").onpaste = function(event) {
        this.value = event.clipboardData.getData('text/plain').replace(/[\/\.-]/g, '');
    }
    document.querySelector("#inputExpiryDate").onpaste = function(event) {
        this.value = event.clipboardData.getData('text/plain').replace(/[\/\.-]/g, '');
    }
};