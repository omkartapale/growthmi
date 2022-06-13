/**
 * Created by mindware on 13/3/16.
 */
// Render recaptcha
var onloadRecaptchaCallback = function () {
    // Renders the HTML element with id 'g-recaptcha-modal' as a reCAPTCHA widget.
    modalWidget = grecaptcha.render(document.getElementById('g-recaptcha-modal'), {
        'sitekey': '6LdpsBoTAAAAAFrg4ggAvNI3bWsm_etId0mbNwxS'
    });

    if (contactWidget = document.getElementById('g-recaptcha-contactform'))
        contactWidget = grecaptcha.render(contactWidget, {
            'sitekey': '6LdpsBoTAAAAAFrg4ggAvNI3bWsm_etId0mbNwxS'
        })
};

// App Init
(function () {
    // validating utilities for request quotation form
    var requestQuoteFormUtils = {
        isValidEmail: function (email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        },
        clearErrors: function () {
            $('#response-alert').remove();
            $('#requestQuoteForm .form-group').removeClass('has-error');
        },
        clearForm: function () {
            $('#response-alert').remove();
            $('#requestQuoteForm input,textarea').val("");
            grecaptcha.reset();
        },
        addError: function ($input) {
            var parentFormGroup = $input.parents('.form-group');
            parentFormGroup.addClass('has-error');
        },
        addAjaxMessage: function (msg, isError) {
            // hide form and show popup
            if (isError == false) {
                $('#request-proposal').modal('hide');
                $('#response').html(msg);
                $('#response-proposal').modal('show');
            } else {
                $("#response-proposal-error").html('<div id="response-alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + msg + '</div>');
            }
        }
    };

    /* smooth scrolling sections */
    var jump = function (e) {
        if (e) {
            e.preventDefault();
            var target = $(this).attr("href");
        } else {
            var target = location.hash;
        }

        $('html,body').animate(
            {
                scrollTop: $(target).offset().top - 90
            }, 1000);
    };

    $(document).ready(function () {

        $("#requestQuoteSubmit").click(function (e) {
            e.preventDefault();
            var $btn = $(this);
            $btn.button('loading');

            requestQuoteFormUtils.clearErrors();

            //do a little client-side validation -- check that each field has a value and e-mail field is in proper format
            //use bootstrap validator (https://github.com/1000hz/bootstrap-validator) if provided, otherwise a bit of custom validation
            var $form = $("#requestQuoteForm"),
                hasErrors = false;
            if ($form.validator) {
                hasErrors = $form.validator('validate').hasErrors;
            } else {
                //$('#requestQuoteForm input,#requestQuoteForm textarea').not('.optional').each(function () {
                //    var $this = $(this);
                //    if (($this.is(':checkbox') && !$this.is(':checked')) || !$this.val()) {
                //        hasErrors = true;
                //        requestQuoteFormUtils.addError($(this));
                //    }
                //});
                var $name = $('#requestName');
                if (!$name.val()) {
                    hasErrors = true;
                    requestQuoteFormUtils.addError($name);
                }
                var $email = $('#requestEmail');
                if (!requestQuoteFormUtils.isValidEmail($email.val())) {
                    hasErrors = true;
                    requestQuoteFormUtils.addError($email);
                }
                var $phone = $('#requestPhone');
                if ((!$phone.val()) || $phone.val() && $phone.intlTelInput && !$phone.intlTelInput("isValidNumber")) {
                    hasErrors = true;
                    requestQuoteFormUtils.addError($phone.parent());
                }
                var $comment = $('#requestComment');
                if (!$comment.val()) {
                    hasErrors = true;
                    requestQuoteFormUtils.addError($comment);
                }
            }
            //if there are any errors return without sending e-mail
            if (hasErrors) {
                $btn.button('reset');
                return false;
            }
            //send the feedback e-mail
            $.ajax({
                type: "POST",
                url: "includes/sendmail.php",
                data: $form.serialize(),
                success: function (data) {
                    requestQuoteFormUtils.addAjaxMessage(data.message, false);
                    //requestQuoteFormUtils.clearForm();
                },
                error: function (response) {
                    requestQuoteFormUtils.addAjaxMessage(response.responseJSON.message, true);
                },
                complete: function () {
                    $btn.button('reset');
                }
            });
            return false;
        });

        // Only enable if the document has a long scroll bar
        // Note the window height + offset
        if (($(window).height() + 100) < $(document).height()) {
            $('#top-link-block').removeClass('hidden').affix({
                // how far to scroll down before link "slides" into view
                offset: {top: 100}
            });
        }

        /* activate scrollspy menu */
        var $body = $(document.body);
        var navHeight = $('header').outerHeight(true) + 10;

        $body.scrollspy({
            target: '#section-links',
            offset: navHeight
        });

        //$('html, body').hide();

        if (location.hash) {
            setTimeout(function () {
                $('html, body').scrollTop(0).show();
                jump();
            }, 0);
        } else {
            $('html, body').show();
        }

        $('#section-links a[href*=#]:not([href=#])').bind("click", jump);
        $('.scroll-links a[href*=#]:not([href=#])').bind("click", jump);

        // bind international tel input
        var requestPhone = $('#requestPhone');
        requestPhone.intlTelInput({
            initialCountry: "auto",
            geoIpLookup: function (callback) {
                $.get('//ipinfo.io', function () {
                }, "jsonp").always(function (resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            utilsScript: "vendor/intl-tel-input/js/utils.js" // just for formatting/placeholders etc
        });
        $(".intl-tel-input").css('width', '100%');

        // bind onchange international tel input validation
        requestPhone.on("keyup change", function () {
            requestQuoteFormUtils.clearErrors();
            if ($.trim(requestPhone.val())) {
                if (!requestPhone.intlTelInput("isValidNumber")) {
                    requestQuoteFormUtils.addError(requestPhone.parent());
                }
            }
        });

        // make modal ready to be shown on link click
        $('#request-proposal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var title = button.data('title'); // Extract info from data-* attributes
            var requestType = button.data('request-type'); // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this);
            modal.find('.modal-title').text('Request a ' + title);
            // reset form inputs & errors
            requestQuoteFormUtils.clearForm();
            requestQuoteFormUtils.clearErrors();
            $("#requestQuoteSubmit").button('reset');
            $('#requestType').val(requestType);
        });

        //enable tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
})();
