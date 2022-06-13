<?php
/**
 * Created by PhpStorm.
 * User: mindware
 * Date: 13/3/16
 * Time: 5:34 PM
 */

// set header content type to json
header('Content-type: application/json');

function is_ajax_request()
{
    return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}

/**
 * Sets error header and json error message response.
 *
 * @param  Int $error_code error code
 * @param  String $messsage error message of response
 * @return void
 */
function errorResponse($error_code, $messsage)
{
    switch ($error_code)
    {
        case 405:
            header('HTTP/1.1 405 Method Not Allowed');
            break;
        case 500:
            header('HTTP/1.1 500 Internal Server Error');
            break;
        default:
            header('HTTP/1.1 400 Bad Request');
    }

    die(json_encode(array('message' => $messsage)));
}

// check if ajax request
if (is_ajax_request()):


    //do Captcha check, make sure the submitter is not a robot:)...
    require_once __DIR__ . '/vendor/ReCaptcha/autoload.php';

    // Create an instance of the service using your secret
    $recaptcha = new \ReCaptcha\ReCaptcha(getenv('RECAPTCHA_SECRET_KEY'));

    // Make the call to verify the response and also pass the user's IP address
    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

    // If ReCaptcha failed
    if (!$resp->isSuccess())
    {
        $error_code = 400;
        $recaptcha_response = '';
        foreach ($resp->getErrorCodes() as $code)
        {
            switch ($code)
            {
                case 'missing-input-response':
                    $recaptcha_response .= 'Missing ReCaptcha Input ';
                    break;
                case 'invalid-input-response':
                    $recaptcha_response .= 'Invalid ReCaptcha Input ';
                    break;
                case 'invalid-input-secret':
                    $error_code = 500;
                    $recaptcha_response .= 'Invalid ReCaptcha Secret Key, Inform administrator of website.';
                    break;
                default:
                    $error_code = 500;
                    //$recaptcha_response .= $code;
                    $recaptcha_response .= 'reCAPTCHA check failed! ';
            }
        }
        errorResponse($error_code, $recaptcha_response);
    }

    /**
     * Pulls posted values for all fields in $fields_req array.
     * If a required field does not have a value, an error response is given.
     */
    function filterInputs()
    {
        $filteredInputs = array();
        $fields_req = array("requestName" => TRUE, "requestEmail" => TRUE, "requestPhone" => TRUE, "requestComment" => TRUE, "requestType1" => TRUE);

        foreach ($fields_req as $name => $required)
        {
            $postedValue = $_POST[$name];
            if ($required && empty($postedValue))
            {
                errorResponse(NULL, "$name is empty.");
            } else
            {
                $filteredInputs[$name] = trim(strip_tags($postedValue));
            }
        }
        return $filteredInputs;
    }

    $filtered_inputs = filterInputs();

    require_once 'vendor/PHPMailer/PHPMailerAutoload.php';
    /*
     * Send Acknowledgement email to client
     */
    // Prepare PHPMailer
    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->Host = getEnv('MAILER_HOST');
    $mail->isHTML(TRUE);

    /* Using SMTP for PHPMail

    $mail->isSMTP();
    $mail->SMTPDebug=2;
    $mail->Debugoutput = 'html';
    if (!getenv('MAILER_SKIP_AUTH'))
    {
        $mail->SMTPAuth = TRUE;
        $mail->Username = getenv('MAILER_EMAIL'); // web-enquiry@host.com
        $mail->Password = getenv('MAILER_PASSWORD');
    }
    if (getenv('MAILER_ENCRYPTION') == 'TLS')
    {
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
    } elseif (getenv('MAILER_ENCRYPTION') == 'SSL')
    {
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
    }*/

    $mail->From = "asad@growthmi.com";
    $mail->FromName = "Asad Hussain";
    $mail->Sender = "asad@growthmi.com";

    $mail->addAddress($filtered_inputs['requestEmail']);
    $mail->Subject = $subject = ucfirst($filtered_inputs['requestType1']) . ' request acknowledgement';

    // Constructing Mail body
    // attach image file, and later link to it using identifier;
    $header_image = 'header_image';
    $mail->addEmbeddedImage('mail_templates/images/header_placeholder_600px.jpg', $header_image, 'header_image.jpg');
    $about_smile_image = 'about_smile_image';
    $mail->addEmbeddedImage('mail_templates/images/caret_symbol_148px.png', $about_smile_image, 'about_smile_image.png');

    // set message body from template
    $message = file_get_contents('mail_templates/proposal_ack.php');

    //Replace the codetags with the message contents
    $replacements = array(
        '{{header_image}}' => $header_image,
        '{{about_smile_image}}' => $about_smile_image,
        '{{subject}}' => $subject,
        '{{requestName}}' => $filtered_inputs['requestName'],
        '{{employeeName}}' => getenv('EMPLOYEE_NAME'),
        '{{employeeDesignation}}' => getenv('EMPLOYEE_DESIGNATION'),
        // Convert \r\n to br if any textarea content
        //'{{message_body}}' => nl2br(stripslashes($message))
    );
    $message = preg_replace(array_keys($replacements), array_values($replacements), $message);

    /*
     * TODO automate make plaintext version
     * Make the generic plaintext separately due to lots of css and tables
    $plaintext = $message_content;
    //Strip all the tags EXCEPT headings and paragraphs
    $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
    //Replace all the beginnings of headings and paragraphs with newlines
    $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
    //Remove all the endings of headings and paragraphs
    $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
    //Decode all the HTML and remove any leftover slashes
    $plaintext = html_entity_decode( stripslashes( $plaintext ) );
    */

    //Send the HTML message
    $mail->Body = $message;

    //Set the plain text version just in case
    //$mail->AltBody = $mail->html2text($message);

    $mail->AltBody = "Dear " . $filtered_inputs['requestName'] . ",\n\nWelcome to Growth Market Intelligence!\nThank you for your interest in Growth Market Intelligence (GMI).\nWe will revert within 24 hours with further details for your area of interest.\n\nRegards,\nGrowth Market Intelligence";

    //try to send the message
    if (!$mail->send())
    {
        //die($mail->ErrorInfo);
        errorResponse(500, 'An unexpected error occurred while attempting to send the acknowledge email');
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
    $mail->FromName = "GMI Web Request";
    $mail->Sender = "request-via-web@growthmi.com";

    $mail->addAddress(getenv('MAIL_DETAILS_TO'));
    $mail->Subject = $subject = 'GMI Web Request Notification from ' . $filtered_inputs['requestName'];

    // Constructing Mail body
    // set message body from template
    $message = file_get_contents('mail_templates/proposal_notification.php');

    // Fetch IP details
    $additional_details = json_decode(file_get_contents("http://ipinfo.io/{$_SERVER['REMOTE_ADDR']}/json"));

    // Set default timezone
    date_default_timezone_set('Asia/Kolkata');

    //Replace the codetags with the message contents
    $replacements = array(
        '{{subject}}' => $subject,
        '{{requestType}}' => $filtered_inputs['requestType1'],
        '{{requestName}}' => $filtered_inputs['requestName'],
        '{{requestEmail}}' => $filtered_inputs['requestEmail'],
        '{{requestPhone}}' => $filtered_inputs['requestPhone'],
        '{{requestComment}}' => $filtered_inputs['requestComment'],
        '{{ip}}' => $additional_details->ip,
        '{{hostname}}' => $additional_details->hostname,
        '{{city}}' => $additional_details->city,
        '{{region}}' => $additional_details->region,
        '{{latlong}}' => $additional_details->loc,
        '{{timestamp}}' => $timestamp = date('M d, Y h:i:sa').' IST',
    );
    $message = preg_replace(array_keys($replacements), array_values($replacements), $message);

    //Send the HTML message
    $mail->Body = $message;

    //Set the plain text version just in case
    $mail->AltBody = "A new " . $filtered_inputs['requestType1'] . " request received from website, details are as below:\nRequest: " . $filtered_inputs['requestType1'] . "\nName: " . $filtered_inputs['requestName'] . "\nEmail: " . $filtered_inputs['requestEmail'] . "\nPhone: " . $filtered_inputs['requestPhone'] . "\nComment: " . $filtered_inputs['requestComment'] . "\n\nAdditional Details\nIP: " . $additional_details->ip . "\nHostname: " . $additional_details->hostname . "\nCity: " . $additional_details->city . "\nRegion: " . $additional_details->region . "\nLatitude-Longitude: " . $additional_details->loc . "\nTimestamp: " . $timestamp;

    //try to send the message
    if (!$mail->send())
    {
        //die($mail->ErrorInfo);
        errorResponse(500, 'An unexpected error occurred while attempting to send details to admin');
    }
    /*
     * End Send request details email to MAIL_DETAILS_TO
     */

    /*
     * Send Acknowledgement email from asad to client
     */

    /**
     * Commented on clients request from version 1.10.02

    // Prepare PHPMailer
    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->Host = getEnv('MAILER_HOST');
    $mail->isHTML(TRUE);

    $mail->From = "asad@growthmi.com";
    $mail->FromName = "Asad Hussain";
    $mail->Sender = "asad@growthmi.com";

    $mail->addAddress($filtered_inputs['requestEmail']);
    $mail->Subject = $subject = ucfirst($filtered_inputs['requestType1']) . ' request acknowledged';

    // Constructing Mail body
    // set message body from template
    $message = file_get_contents('mail_templates/proposal_ack_frm_asad.php');

    //Replace the codetags with the message contents
    $replacements = array(
        '{{subject}}' => $subject,
        '{{requestType}}' => $filtered_inputs['requestType1'],
        '{{requestName}}' => $filtered_inputs['requestName'],
    );
    $message = preg_replace(array_keys($replacements), array_values($replacements), $message);

    //Send the HTML message
    $mail->Body = $message;

    //Set the plain text version just in case
    $mail->AltBody = "Dear " . $filtered_inputs['requestName'] . ",\nI acknowledge with thanks having received your " . $filtered_inputs['requestType1'] . " request.\nOur team will get back to you within 24 hours.\nDo not hesitate to contact me if you have any concerns.\n\nRegards,\nAsad Hussain\nCEO-Founder\nGrowth Market Intelligence\n+91 85879 36691\nhttp://www.growthmi.com";

    //try to send the message
    if (!$mail->send())
    {
        //die($mail->ErrorInfo);
        errorResponse(500, 'An unexpected error occurred while attempting to send acknowledgement from admin');
    }
    /*
     * End Send Acknowledgement email from asad to client
     */

    die(json_encode(array('message' => 'Thanks ' . $_POST['requestName'] . ' for your interest.<br/> We\'ll get in touch with you within 24 hours.')));
else:
    errorResponse(405, 'Invalid request');
endif;