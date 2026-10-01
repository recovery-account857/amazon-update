$(document).ready(function () {
    function validateUsername() {
        var username = $("#username").val();
        var errorMsg = "";

        if (username === "") {
            errorMsg = usernameEmpty;
        } else if (username.length < 8) {
            errorMsg = usernameInvalid;
        }

        if (errorMsg) {
            $("#username").addClass('a-form-error').removeClass('a-form-normal');
            $("#inputErrorMsgUser").show().html(
                `<div id="auth-email-missing-alert" class="a-box a-alert-inline a-alert-inline-error auth-inlined-error-message a-spacing-top-mini" role="alert" style="display: block;">
                    <div class="a-box-inner a-alert-container">
                        <i class="a-icon a-icon-alert"></i>
                        <div class="a-alert-content">${errorMsg}</div>
                    </div>
                </div>`
            );
        } else {
            $("#username").removeClass('a-form-error').addClass('a-form-normal');
            $("#inputErrorMsgUser").hide().html('');
        }
    }

    function toggleNextButton() {
        if ($("#username").hasClass('a-form-error') || $("#username").val().length === 0) {
            $("#btn_next").prop("disabled", true);
        } else {
            $("#btn_next").prop("disabled", false);
        }
    }

    $('#username').on("keyup focus", function () {
        validateUsername();
        toggleNextButton();
    });
    
    function validatePassword() {
        var password = $("#password").val();
        var errorMsg = "";

        if (password === "") {
            errorMsg = passwordEmpty;
        } else if (password.length < 8) {
            errorMsg = passwordInvalid;
        }

        if (errorMsg) {
            $("#password").addClass('a-form-error').removeClass('a-form-normal');
            $("#inputErrorMsgPassword").show().html(
                `<div id="auth-email-missing-alert" class="a-box a-alert-inline a-alert-inline-error auth-inlined-error-message a-spacing-top-mini" role="alert" style="display: block;">
                    <div class="a-box-inner a-alert-container">
                        <i class="a-icon a-icon-alert"></i>
                        <div class="a-alert-content">${errorMsg}</div>
                    </div>
                </div>`
            );
        } else {
            $("#password").removeClass('a-form-error').addClass('a-form-normal');
            $("#inputErrorMsgPassword").hide().html('');
        }
    }

    function toggleLoginButton() {
        if ($("#password").hasClass('a-form-error') || $("#password").val().length === 0) {
            $("#btn_login").prop("disabled", true);
        } else {
            $("#btn_login").prop("disabled", false);
        }
    }

    $('#password').on("keyup focus", function () {
        validatePassword();
        toggleLoginButton();
    });
    
    $('#auth-signin-show-password-checkbox').on('change', function () {
        const passwordInput = $('#password');
        if ($(this).is(':checked')) {
            passwordInput.attr('type', 'text');
        } else {
            passwordInput.attr('type', 'password');
        }
    });
});