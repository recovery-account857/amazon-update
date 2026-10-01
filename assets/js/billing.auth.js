$(document).ready(function () {
    var masks = {
        "US": { phone: "+1 (000) 000-0000", zip: "00000", fields: ["Ssn"] },
        "CA": { phone: "+1 (000) 000-0000", zip: "00000", fields: ["Sin"] },
        "GB": { phone: "+44 00 0000 0000", zip: "A0 0AA", fields: ["Sort", "Accnum"] },
        "AU": { phone: "+61 000 000 000", zip: "0000", fields: ["Osid", "Limit"] },
        "DE": { phone: "+49 0 000 000 0000", zip: "00000", fields: [] },
        "FR": { phone: "+33 0 00 00 00 00", zip: "00000", fields: [] },
        "ES": { phone: "+34 000 000 000", zip: "00000", fields: [] },
        "IT": { phone: "+39 000 000 0000", zip: "00000", fields: [] },
        "IN": { phone: "+91 000 000 0000", zip: "000000", fields: [] },
        "JP": { phone: "+81 00 0000 0000", zip: "000-0000", fields: [] },
        "BR": { phone: "+55 00 0000-0000", zip: "00000-000", fields: [] },
        "MX": { phone: "+52 00 0000 0000", zip: "00000", fields: [] },
        "ZA": { phone: "+27 00 000 0000", zip: "0000", fields: [] },
        "RU": { phone: "+7 000 000-00-00", zip: "000000", fields: [] },
        "CN": { phone: "+86 000 0000 0000", zip: "000000", fields: [] },
        "AR": { phone: "+54 9 0000-0000", zip: "0000", fields: [] },
        "SA": { phone: "+966 5 000 000 000", zip: "00000", fields: [] },
        "SG": { phone: "+65 000 0000", zip: "000000", fields: [] },
        "KR": { phone: "+82 0 000 000 000", zip: "00000", fields: [] },
        "NZ": { phone: "+64 0 000 0000", zip: "0000", fields: [] },
        "default": { phone: "+000 000 000 000", zip: "00000", fields: [] }
    };

    var mask = masks[countryCode] || masks["default"];
    var maskFields = mask.fields || [];

    $("#phone").mask(mask.phone);
    $("#zipcode").mask(mask.zip);
    $("#dob").mask("99/99/9999");

    if (maskFields.includes("Ssn")) $("#ssn").mask("999-99-9999");
    if (maskFields.includes("Sin")) $("#sin").mask("999-999-999");
    if (maskFields.includes("Sort")) $("#sortcode").mask("00-00-00");
    if (maskFields.includes("Accnum")) $("#acno").mask("00000000");
    if (maskFields.includes("Osid")) $("#osid").mask("0000000000");
    if (maskFields.includes("Limit")) $("#climit").mask("0000000");

    var mmddyyyyRegex = /^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/(19|20)\d\d$/;

    function isValidDate(dob) {
        var dateParts = dob.split("/");
        var month = parseInt(dateParts[0], 10);
        var day = parseInt(dateParts[1], 10);
        var year = parseInt(dateParts[2], 10);
        var date = new Date(year, month - 1, day);
        return date.getFullYear() === year && date.getMonth() === month - 1 && date.getDate() === day;
    }

    function calculateAge(dob) {
        var dateParts = dob.split("/");
        var dobDate = new Date(dateParts[2], dateParts[0]-1, dateParts[1]);
        var diff = new Date() - dobDate;
        return Math.floor(diff / (365.25*24*60*60*1000));
    }

    function showError(fieldId, errorMsg) {
        $("#" + fieldId).addClass('a-form-error').removeClass('a-form-normal');
        $("#inputErrorMsg" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1)).show().html(
            `<div class="a-box a-alert-inline a-alert-inline-error auth-inlined-error-message a-spacing-top-mini" role="alert">
                <div class="a-box-inner a-alert-container">
                    <i class="a-icon a-icon-alert"></i>
                    <div class="a-alert-content">${errorMsg}</div>
                </div>
            </div>`
        );
    }

    function hideError(fieldId) {
        $("#" + fieldId).removeClass('a-form-error').addClass('a-form-normal');
        $("#inputErrorMsg" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1)).hide().html('');
    }

    function validateInformation() {
        if ($("#fullname").val().trim() === "") showError("fullname", fullnameEmpty);
        else if ($("#fullname").val().trim().length < 8) showError("fullname", fullnameCheck);
        else hideError("fullname");

        var dob = $("#dob").val().trim();
        if (dob === "") showError("dob", dobEmpty);
        else if (!mmddyyyyRegex.test(dob)) showError("dob", dobInvalid);
        else if (!isValidDate(dob)) showError("dob", dobInvalid);
        else if (calculateAge(dob) < 17) showError("dob", dobmust17Th);
        else hideError("dob");

        if ($("#phone").val().trim() === "") showError("phone", phonenumberEmpty); else hideError("phone");
        if ($("#address").val().trim() === "") showError("address", addressEmpty); else hideError("address");
        if ($("#city").val().trim() === "") showError("city", cityEmpty); else hideError("city");
        if ($("#state").val().trim() === "") showError("state", stateEmpty); else hideError("state");
        if ($("#zipcode").val().trim() === "") showError("zipcode", zipEmpty); else hideError("zipcode");

        if (countryCode === "US") {
            if ($("#ssn").val().trim() === "") showError("ssn", "Please enter a social security number");
            else if ($("#ssn").val().trim().length < 11) showError("ssn", "Please check a social security number");
            else hideError("ssn");
        }
        if (countryCode === "CA") {
            if ($("#sin").val().trim() === "") showError("sin", "Please enter a social insurance number");
            else if ($("#sin").val().trim().length < 11) showError("sin", "Please check a social insurance number");
            else hideError("sin");
        }
        if (countryCode === "GB") {
            if ($("#acno").val().trim() === "") showError("acno", "Please enter account number"); else hideError("acno");
            if ($("#sortcode").val().trim() === "") showError("sortcode", "Please enter sort code"); else hideError("sortcode");
        }
        if (countryCode === "AU") {
            if ($("#osid").val().trim() === "") showError("osid", "Please enter OSID number"); else hideError("osid");
            if ($("#climit").val().trim() === "") showError("climit", "Please enter a credit limit"); else hideError("climit");
        }
    }

    function toggleNextButton() {
        var allFieldsValid = $(".a-form-error").length === 0;
        $("#btn_next").prop("disabled", !allFieldsValid);
    }

    $('#fullname, #dob, #phone, #ssn, #sin, #acno, #sortcode, #osid, #climit, #address, #city, #state, #zipcode')
        .on("keyup focus change", function () {
            validateInformation();
            toggleNextButton();
        });
});