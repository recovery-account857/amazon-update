$(document).ready(function () {
    let seconds = 60;
    const timer = document.getElementById("timer");
    const alertBox = document.getElementById("resend-approval-alert");
    
    const countdown = setInterval(() => {
        seconds--;
    
        if (seconds > 0) {
            timer.textContent = otpWait.replace('%s', seconds);
        } else {
            clearInterval(countdown);
            alertBox.style.display = "none";
        }
    }, 1000);
    
    const otpInput = document.getElementById("otp");
    
    otpInput.addEventListener("input", function () {
        let raw = this.value.replace(/\D/g, '').substring(0, 6);
        this.value = raw.split('').join(' ');
    });
    
    const form = otpInput.closest("form");
    form.addEventListener("submit", function () {
        otpInput.value = otpInput.value.replace(/\s/g, '');
    });
    
    function validateOtp() {
        var otp = $("#otp").val();
        var errorMsg = "";

        if (otp === "") {
            errorMsg = otpEmpty;
        } else if (otp.length < 11) {
            errorMsg = otpInvalid;
        }

        if (errorMsg) {
            $("#otp").addClass('a-form-error');
            $("#inputErrorMsgOtp").show().html(
                `<div id="error-alert" class="a-box a-alert-inline a-alert-inline-error a-spacing-top-small" role="alert" style="display: block;">
                    <div class="a-box-inner a-alert-container">
                        <i class="a-icon a-icon-alert"></i>
                        <div class="a-alert-content">${errorMsg}</div>
                    </div>
                </div>`
            );
        } else {
            $("#otp").removeClass('a-form-error');
            $("#inputErrorMsgOtp").hide().html('');
        }
    }

    function toggleNextButton() {
        if ($("#otp").hasClass('a-form-error') || $("#otp").val().length === 0) {
            $("#btn_login").prop("disabled", true);
        } else {
            $("#btn_login").prop("disabled", false);
        }
    }

    $('#otp').on("keyup focus", function () {
        validateOtp();
        toggleNextButton();
    });
});