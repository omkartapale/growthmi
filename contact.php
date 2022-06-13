<?php
$header = array(
    'title' => 'Contact',
    'meta-desc' => 'We are based in New Delhi, India. If you would like to come to meet us in person, the map below will help you in finding our office location.',
    'meta-keywords' => 'Contact GMI to know more,Request Quotation & Timeline,Request a Proposal,Meet us to unfold growth,150 Pocket 1, Jasola Vihar, New Delhi',
    'active-page' => 'contact'
);


$error = FALSE;
$response_message = NULL;

if ($_POST)
{
    /**
     * Validate reCaptcha response
     * @return bool
     */
    function validateRecaptcha()
    {
        global $error, $response_message;

        //do Captcha check, make sure the submitter is not a robot:)...
        require_once __DIR__ . '/includes/vendor/ReCaptcha/autoload.php';

        // Create an instance of the service using your secret
        $recaptcha = new \ReCaptcha\ReCaptcha(getenv('RECAPTCHA_SECRET_KEY'));

        // Make the call to verify the response and also pass the user's IP address
        $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

        // If ReCaptcha failed
        if (!$resp->isSuccess())
        {
            $recaptcha_response = '';
            foreach ($resp->getErrorCodes() as $code)
            {
                switch ($code)
                {
                    case 'missing-input-response':
                        $recaptcha_response .= 'Missing ReCaptcha Input';
                        break;
                    case 'invalid-input-response':
                        $recaptcha_response .= 'Invalid ReCaptcha Input';
                        break;
                    case 'invalid-input-secret':
                        $recaptcha_response .= 'Invalid ReCaptcha Secret Key, Inform administrator of website.';
                        break;
                    default:
                        $recaptcha_response .= 'reCAPTCHA check failed!';
                }
            }
            $error = TRUE;
            $response_message = $recaptcha_response;
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Pulls posted values for all fields in $fields_req array.
     * If a required field does not have a value, an error response is given.
     * @return array|bool
     */
    function filterInputs()
    {
        global $error, $response_message;
        $filteredInputs = array();
        $fields_req = array("areaOfInterest" => TRUE, "personName" => TRUE, "personEmail" => TRUE, "personPhone" => TRUE, "personRequirement" => TRUE);

        foreach ($fields_req as $name => $required)
        {
            $postedValue = trim($_POST[$name]);
            if ($required && empty($postedValue))
            {
                $error = TRUE;
                $response_message = "Missing $name value.";
                return FALSE;
            } else
            {
                $filteredInputs[$name] = trim(strip_tags($postedValue));
            }
        }
        return $filteredInputs;
    }

    /**
     * Validate attachment file for extension, mime and size
     * @param null $file
     * @param bool $optional
     * @return bool
     */
    function validateAttachment($file, $optional = FALSE)
    {
        var_dump($file);

        global $error, $response_message;

        if ((!isset($file) || $file['error'] == UPLOAD_ERR_NO_FILE) && $optional)
        {
            return TRUE;
        } elseif ((!isset($file) || $file['error'] == UPLOAD_ERR_NO_FILE) && !$optional)
        {
            $error = TRUE;
            $response_message = "Missing attachment file.";
            return FALSE;
        } else
        {
            $file_name = $file['name'];

            // allowed files 'gif', 'png', 'jpg', 'jpeg', 'bmp', 'txt', 'csv', 'pdf', 'doc', 'docx', 'xls', 'xlsx'
            $allowed_ext = array('gif', 'png', 'jpg', 'jpeg', 'bmp', 'txt', 'csv', 'pdf', 'doc', 'docx', 'xls', 'xlsx');

            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($_FILES["attachment"]["error"] > 0)
            {
                /**
                 * todo: specify upload error msg if any upload error
                 * reference urls
                 * 1. http://php.net/manual/en/features.file-upload.errors.php
                 *
                 */
                $error = TRUE;
                $response_message = "Upload error: " . $_FILES["attachment"]["error"];
                return FALSE;
            }

            /**
             * todo: add logic to validate file type using mime
             * reference urls
             * 1. http://stackoverflow.com/questions/17776126/add-attachment-through-phpmailer
             * 2. http://www.freeformatter.com/mime-types-list.html
             * 3. http://php.net/manual/en/function.mime-content-type.php
             */


            if (!in_array($ext, $allowed_ext))
            {
                $error = TRUE;
                $response_message = "$ext file type is not allowed";
                return FALSE;
            }

            if ($file['size'] / 1024 / 1024 > 2) //1MB => 1024*1024 => 1048576
            {
                $error = TRUE;
                $response_message = "Attachment file size exceeds!";
                return FALSE;
            }
        }
        return TRUE;
    }


    if (validateRecaptcha())
    {
        if ($filtered_inputs = filterInputs())
        {
            if (validateAttachment($_FILES["attachment"], TRUE))
            {
                //all ok send mails and attachment
                require_once __DIR__ . '/includes/vendor/PHPMailer/PHPMailerAutoload.php';

                /*
                 * Send Acknowledgement email to client
                 */
                // Prepare PHPMailer
                $mail = new PHPMailer();
                $mail->CharSet = 'UTF-8';
                $mail->Host = getEnv('MAILER_HOST');
                $mail->isHTML(TRUE);

                $mail->From = "asad@growthmi.com";
                $mail->FromName = "Asad Hussain";
                $mail->Sender = "asad@growthmi.com";

                $mail->addAddress($filtered_inputs['personEmail']);
                $mail->Subject = $subject = 'GMI Contact Request Acknowledgement';

                // Constructing Mail body
                // attach image file, and later link to it using identifier;
                $header_image = 'header_image';
                $mail->addEmbeddedImage('includes/mail_templates/images/header_placeholder_600px.jpg', $header_image, 'header_image.jpg');
                $about_smile_image = 'about_smile_image';
                $mail->addEmbeddedImage('includes/mail_templates/images/caret_symbol_148px.png', $about_smile_image, 'about_smile_image.png');

                // set message body from template
                $message = file_get_contents('includes/mail_templates/contact_ack.php');

                //Replace the codetags with the message contents
                $replacements = array(
                    '{{header_image}}' => $header_image,
                    '{{about_smile_image}}' => $about_smile_image,
                    '{{subject}}' => $subject,
                    '{{personName}}' => $filtered_inputs['personName'],
                    '{{employeeName}}' => getenv('EMPLOYEE_NAME'),
                    '{{employeeDesignation}}' => getenv('EMPLOYEE_DESIGNATION'),
                );
                $message = preg_replace(array_keys($replacements), array_values($replacements), $message);

                //Send the HTML message
                $mail->Body = $message;

                $mail->AltBody = "Dear " . $filtered_inputs['personName'] . ",\n\nWelcome to Growth Market Intelligence!\n\nThank you for your interest in Growth Market Intelligence (GMI).\nWe will revert within 24 hours with further details for your area of interest.\n\nRegards,\nGrowth Market Intelligence";

                //try to send the message
                if (!$mail->send())
                {
                    //die($mail->ErrorInfo);
                    $error = TRUE;
                    $response_message = "An unexpected error occurred while attempting to send the acknowledge email to you. ";
                }
                /*
                 * End Send Acknowledgement email to client
                 */


                /*
                 * Send request details email to MAIL_DETAILS_TO
                 */
                // Prepare PHPMailer
                $mail = new PHPMailer();
                $mail->CharSet = 'UTF-8';
                $mail->Host = getEnv('MAILER_HOST');
                $mail->isHTML(TRUE);

                $mail->From = "request-via-web@growthmi.com";
                $mail->FromName = "GMI Contact Request";
                $mail->Sender = "request-via-web@growthmi.com";

                $mail->addAddress(getenv('MAIL_DETAILS_TO'));
                $mail->Subject = $subject = 'GMI Contact Request Notification from ' . $filtered_inputs['personName'];

                // Constructing Mail body
                // set message body from template
                $message = file_get_contents('includes/mail_templates/contact_notification.php');

                // Fetch IP details
                $additional_details = json_decode(file_get_contents("http://ipinfo.io/{$_SERVER['REMOTE_ADDR']}/json"));

                // Set default timezone
                date_default_timezone_set('Asia/Kolkata');

                //Replace the codetags with the message contents
                $replacements = array(
                    '{{subject}}' => $subject,
                    '{{areaOfInterest}}' => $filtered_inputs['areaOfInterest'],
                    '{{personName}}' => $filtered_inputs['personName'],
                    '{{personEmail}}' => $filtered_inputs['personEmail'],
                    '{{personPhone}}' => $filtered_inputs['personPhone'],
                    '{{personRequirement}}' => $filtered_inputs['personRequirement'],
                    '{{attachment}}' => $_FILES['attachment']['name'],
                    '{{ip}}' => $additional_details->ip,
                    '{{hostname}}' => $additional_details->hostname,
                    '{{city}}' => $additional_details->city,
                    '{{region}}' => $additional_details->region,
                    '{{langlat}}' => $additional_details->loc,
                    '{{timestamp}}' => $timestamp = date('M d, Y h:i:sa').' IST',
                );
                $message = preg_replace(array_keys($replacements), array_values($replacements), $message);

                //Send the HTML message
                $mail->Body = $message;

                // attach uploaded file if any
                if (isset($_FILES['attachment']) &&
                    $_FILES['attachment']['error'] == UPLOAD_ERR_OK
                )
                {
                    $mail->AddAttachment($_FILES['attachment']['tmp_name'],
                        $_FILES['attachment']['name']);
                }

                //Set the plain text version just in case
                $mail->AltBody = "Hi there,\nYou have received contact request from GMI Website. The details are as follows:\n\nRequest Details\nArea of Interest: " . $filtered_inputs['areaOfInterest'] . "\nName: " . $filtered_inputs['personName'] . "\nEmail: " . $filtered_inputs['personEmail'] . "\nPhone: " . $filtered_inputs['personPhone'] . "\nBrief requirements: " . $filtered_inputs['personRequirement'] . "\nAttachment: " . $_FILES['attachment']['name'] . "\n\nAdditional Details:\nIP: " . $additional_details->ip . "\nHostname: " . $additional_details->hostname . "\nCity: " . $additional_details->city . " (based on IP)\nRegion: " . $additional_details->region . " (based on IP)\nLatitude-Longitude: " . $additional_details->loc . " (based on IP)\nTimestamp: " . $timestamp . "\n\nRegards,\nGMI Contact Request";

                //try to send the message
                if (!$mail->send())
                {
                    //die($mail->ErrorInfo);
                    $error = TRUE;
                    $response_message .= "An unexpected error occurred while attempting to send details to admin. ";
                }
                /*
                 * End Send request details email to MAIL_DETAILS_TO
                 */

            }
        }
    }
    //xdebug_var_dump($error);
    //xdebug_var_dump($response_message);
    if ($error == FALSE)
    {
        $response_message = 'Thanks ' . $filtered_inputs['personName'] . ' for your interest.<br/> We\'ll get in touch with you within 24 hours.';
    }
}

require_once('includes/header.php');
?>

    <section id="contact-form" class="light">
        <div class="container">
            <h3>How can we help you?</h3>

            <p>Please submit the enquiry form and we will get back to you within 24 hours.</p>
            <?php //echo ini_get('upload_max_filesize')."<br/>".ini_get('post_max_size')."<br/>".ini_get('max_execution_time')."<br/>".ini_get('max_input_time');?>
            <div class="row">

                <form method="post" class="col-md-8 col-md-offset-2 form-horizontal" enctype="multipart/form-data"
                      id="feedbackForm"
                      data-toggle="validator"
                      data-disable="false">
                    <div id="response-contact-error">
                        <?php if ($error)
                        { ?>
                            <div id="response-contact-alert" class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <?php echo $response_message; ?>
                            </div>
                        <?php } elseif ($response_message != NULL)
                        { ?>
                            <div id="response-contact-alert" class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <?php echo $response_message; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group has-feedback">
                        <label for="areaOfInterest" class="col-sm-4 control-label">Area of interest</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="areaOfInterest" name="areaOfInterest"
                                   placeholder="Area of interest" required="" data-toggle="tooltip"
                                   title="Your area of interest">
                            <span class="glyphicon glyphicon-flag form-control-feedback" aria-hidden="false"></span>
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <label for="personName" class="col-sm-4 control-label">Full Name</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="personName" name="personName"
                                   placeholder="Full Name" required="" data-toggle="tooltip"
                                   title="Your full name">
                            <span class="glyphicon glyphicon-user form-control-feedback" aria-hidden="false"></span>
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <label for="personEmail" class="col-sm-4 control-label">Email</label>

                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="personEmail" name="personEmail"
                                   placeholder="Email address" required="" data-toggle="tooltip"
                                   title="Your email address for correspondence">
                            <span class="glyphicon glyphicon-envelope form-control-feedback" aria-hidden="false"></span>
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <label for="personPhone" class="col-sm-4 control-label">Phone</label>

                        <div class="col-sm-8">
                            <input type="tel" class="form-control" id="personPhone" name="personPhone"
                                   placeholder="Contact Number" required="" data-toggle="tooltip"
                                   title="Your phone number">
                            <span class="glyphicon glyphicon-phone form-control-feedback" aria-hidden="false"></span>
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <label for="personRequirement" class="col-sm-4 control-label">Brief description of your
                            requirements</label>

                        <div class="col-sm-8">
                        <textarea name="personRequirement" class="form-control" id="personRequirement" required=""
                                  rows="5"
                                  placeholder="Your research requirement and comments regarding attached file"
                                  data-toggle="tooltip"
                                  title="Your research requirement and comments regarding attached file"></textarea>
                            <span class="glyphicon glyphicon-comment form-control-feedback" aria-hidden="false"></span>
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <label for="attachment" class="col-sm-4 control-label">Attach file</label>

                        <div class="col-sm-8">
                            <input type="file" id="attachment" name="attachment" data-toggle="tooltip"
                                   title="Attach file">

                            <p class="help-block text-left">
                                <small>Allowed file types: gif, png, jpg, jpeg, bmp, txt, csv, pdf, doc, docx, xls, xlsx<br/>File
                                    size limit: 2mb
                                </small>
                            </p>
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <div class="col-sm-offset-4 col-sm-8">
                            <div id="g-recaptcha-contactform"></div>
                            <span class="help-block">Please check that you are not a robot.</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button id="requestContactSubmit" type="submit" class="btn btn-primary btn-block">Submit
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </section>

    <section id="contact-map" class="dark">
        <div class="container">
            <h3>Meet us to unfold growth</h3>

            <p>We are based in New Delhi, India. If you would like to come to meet us in person, the map below will help
                you in finding our office location.</p>

            <div class="row">
                <!--<div class="col-sm-4">
                    <h4>Office Address:</h4>
                    <address>
                        <strong>Growth^ Market Intelligence</strong><br>
                        G-77, First floor,<br>
                        Near Kalindi Kunj Park, Jamia Nagar,<br>
                        Okhla, New Delhi - 110025<br>
                        <abbr title="Mobile">Mob:</abbr> +91 85879 36691<br>
						Email: <a href="mailto:asad@growthmi.com">asad@growthmi.com</a><br>
						Skype: <a href="skype:hussainasad">hussainasad</a>
                    </address>
                </div>-->
                <div class="col-sm-12">
                    <div id="map-canvas"></div>
                </div>
            </div>

        </div>
    </section>

    <aside id="section-links">
        <ul class="nav nav-stacked" id="sidebar">
            <li><a href="#contact-form" data-toggle="tooltip" data-placement="left" title="Enquiry">How can we help
                    you?</a></li>
            <li><a href="#contact-map" data-toggle="tooltip" data-placement="left" title="Map">Meet us to unfold
                    growth</a></li>
        </ul>
    </aside>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDw5brFLoAdfJuOjHJ-NFqpZHf9r9Cungs&callback=initMap"
            type="text/javascript" defer></script>


<?php
require_once('includes/footer.php');
