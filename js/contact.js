/**
 * Created by mindware on 27/3/16.
 */

// Initialize Map on contact page
function initMap() {
    var mapCanvas = document.getElementById('map-canvas');
    var myLatLng = new google.maps.LatLng(28.540444, 77.295250);
    var mapOptions = {
        center: myLatLng,
        zoom: 16,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(mapCanvas, mapOptions);
    //var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
    //var iconBase = 'http://maps.google.com/mapfiles/kml/pal2/';

    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        //icon: iconBase + 'icon13.png'
        animation: google.maps.Animation.BOUNCE
    });

    var msg = "<address>" +
        "<strong>Growth^ Market Intelligence</strong><br>" +
        "150 Pocket 1, Jasola Vihar<br>" +
        "New Delhi - 110025<br>" +
        "<abbr title=\"Contact\">Contact:</abbr> 011 46590137<br>" +
        "Email: <a href=\"mailto:asad@growthmi.com\">asad@growthmi.com</a><br>" +
        "Skype: <a href=\"skype:hussainasad\">hussainasad</a>" +
        "</address>";

    var infowindow = new google.maps.InfoWindow({
        content: msg
    });

    marker.addListener('click', function () {
        infowindow.open(map, marker)
    });

    infowindow.open(map, marker);
}

(function () {
    // validating utilities for request quotation form
    var contactFormUtils = {
        isValidEmail: function (email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        },
        clearErrors: function () {
            $('#response-contact-alert').remove();
            $('#feedbackForm .form-group').removeClass('has-error');
        },
        clearForm: function () {
            $('#response-contact-alert').remove();

            //$('#feedbackForm').get(0).clear();
            $('#feedbackForm input,textarea').val("");
            grecaptcha.reset();
        },
        addError: function ($input) {
            var parentFormGroup = $input.parents('.form-group');
            parentFormGroup.addClass('has-error');
        }
    };

    $(document).ready(function () {
        $("#requestContactSubmit").click(function (e) {
            //e.preventDefault();
            var $btn = $(this);
            $btn.button('loading');

            contactFormUtils.clearErrors();

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
                var $interest = $('#areaOfInterest');
                if (!$interest.val()) {
                    hasErrors = true;
                    contactFormUtils.addError($interest);
                }
                var $name = $('#personName');
                if (!$name.val()) {
                    hasErrors = true;
                    contactFormUtils.addError($name);
                }
                var $email = $('#personEmail');
                if (!contactFormUtils.isValidEmail($email.val())) {
                    hasErrors = true;
                    contactFormUtils.addError($email);
                }
                var $phone = $('#personPhone');
                if ((!$phone.val()) || $phone.val() && $phone.intlTelInput && !$phone.intlTelInput("isValidNumber")) {
                    hasErrors = true;
                    contactFormUtils.addError($phone.parent());
                }
                var $requirement = $('#personRequirement');
                if (!$requirement.val()) {
                    hasErrors = true;
                    contactFormUtils.addError($requirement);
                }
                var $attachment = $('#attachment');
                if ($attachment.val()) {

                    var ext = $attachment.val().split('.').pop().toLowerCase();
                    if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg', 'bmp', 'txt', 'csv', 'pdf', 'doc', 'docx', 'xls', 'xlsx']) == -1) {
                        hasErrors = true;
                        contactFormUtils.addError($attachment);
                        alert('Unsupported attachment file type!');
                    }

                    // 10485760=10mb 1048576=1mb
                    if ($attachment[0].files[0].size/1024/1024 > 2) {
                        hasErrors = true;
                        contactFormUtils.addError($attachment);
                        alert('Attachment file size exceeds!');
                    }
                }
            }
            //if there are any errors return without sending e-mail
            if (hasErrors) {
                $btn.button('reset');
                return false;
            }
            ////send the feedback e-mail
            //$.ajax({
            //    type: "POST",
            //    url: "includes/sendmail.php",
            //    data: $form.serialize(),
            //    success: function (data) {
            //        requestQuoteFormUtils.addAjaxMessage(data.message, false);
            //        //requestQuoteFormUtils.clearForm();
            //    },
            //    error: function (response) {
            //        requestQuoteFormUtils.addAjaxMessage(response.responseJSON.message, true);
            //    },
            //    complete: function () {
            //        $btn.button('reset');
            //    }
            //});
            return true;
        });

        // bind international tel input
        var personPhone = $('#personPhone');
        personPhone.intlTelInput({
            initialCountry: "auto",
            geoIpLookup: function (callback) {
                $.get('http://ipinfo.io', function () {
                }, "jsonp").always(function (resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            utilsScript: "vendor/intl-tel-input/js/utils.js" // just for formatting/placeholders etc
        });
        $(".intl-tel-input").css('width', '100%');

        // bind onchange international tel input validation
        personPhone.on("keyup change", function () {
            contactFormUtils.clearErrors();
            if ($.trim(personPhone.val())) {
                if (!personPhone.intlTelInput("isValidNumber")) {
                    contactFormUtils.addError(personPhone.parent());
                }
            }
        });

        //var rattachment = $('#attachment');
        //rattachment.on('change',function(){
        //    console.log('This '+ this.val() +' size is: ' + (this.files[0].size/1024/1024).toFixed(2) + " MB");
        //});
    });

})();
