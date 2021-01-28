<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/src/Exception.php';
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/SMTP.php';



class Emailservice {

    private $result;

    function __construct() {
        $this->result = [
            'success' => false,
            'msg' => ''
        ];
    }

    /**
     *
     * @param type $recep
     * @param type $content
     * @param type $sub
     * @return type
     */
    public function mail($recep, $content, $sub = 'Account Verification') {
        ob_start();
        // Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
// If necessary, modify the path in the require statement below to refer to the
// location of your Composer autoload.php file.
// Replace sender@example.com with your "From" address.
// This address must be verified with Amazon SES.
        $sender = 'testmail01755@gmail.com';
        $senderName = 'Cosultant Verify';

// Replace recipient@example.com with a "To" address. If your account
// is still in the sandbox, this address must be verified.
        $recipient = $recep;

// Replace smtp_username with your gmail SMTP user name.
        $usernameSmtp = 'testmail01755@gmail.com';

// Replace smtp_password with your gmail SMTP password.
        $passwordSmtp = 'Testmail123456';

// Specify a configuration set. If you do not want to use a configuration
// set, comment or remove the next line.
//$configurationSet = 'ConfigSet';
// If you're using Amazon SES in a region other than US West (Oregon),
// replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
// endpoint in the appropriate region.
        $host = 'smtp.gmail.com';
        $port = 587;

// The subject line of the email
        $subject = $sub;

// The plain-text body of the email
        $bodyText = $content;

// The HTML-formatted body of the email
        $bodyHtml = '<h1>Action Required</h1>' . $content;

        $mail = new PHPMailer(true);

        try {
            // Specify the SMTP settings.
            $mail->isSMTP();
            $mail->setFrom($sender, $senderName);
            $mail->Username = $usernameSmtp;
            $mail->Password = $passwordSmtp;
            $mail->Host = $host;
            $mail->Port = $port;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            //$mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);
            // Specify the message recipients.
            $mail->addAddress($recipient);
            // You can also add CC, BCC, and additional To recipients here.
            // Specify the content of the message.
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $bodyHtml;
            $mail->AltBody = $bodyText;
            $mail->Send();
            // echo "Email sent!" , PHP_EOL;

            $this->result = [
                'success' => true,
                'msg' => 'email sent successfully'
            ];
        } catch (phpmailerException $e) {
            $this->result['msg'] = "An error occurred. {$e->errorMessage()}" . PHP_EOL; //Catch errors from PHPMailer.
        } catch (Exception $e) {
            $this->result['msg'] = "Email not sent. {$mail->ErrorInfo}" . PHP_EOL; //Catch errors from Amazon SES.
        }
        ob_end_clean();

        return $this->result;
    }

}
