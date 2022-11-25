<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
error_reporting(0);
if (isset($_POST['captcha'])) {
    $captcha = $_POST['captcha'];
} else {
    $captcha = false;
}

if (!$captcha) {
    echo message("Please check the captcha.", 'Error: ', 'danger');
    return false;
} else {
    $secret   = 'Secret--key';
    $response = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']
    );
    // use json_decode to extract json response
    $response = json_decode($response);

    if ($response->success === false) {
        echo message("Captcha Failed !! ", 'Error: ', 'danger');
        return false;
    }
}

//... The Captcha is valid you can continue with the rest of your code
//... Add code to filter access using $response . score
if ($response->success == true && $response->score <= 0.5) {
    if (isset($_POST['userName']) && !empty($_POST['userName']) && isset($_POST['email']) && !empty($_POST['email'])  && isset($_POST["phoneNumber"]) && !empty($_POST["phoneNumber"])) {
        try {
            //Content
            $content = "<b>Name:</b> " . $_POST["userName"] . "<br>";
            $content .= "<b>Email:</b> " . $_POST["email"] . "<br>";
            $content .= "<b>Phone Number:</b> " . $_POST["phoneNumber"] . "<br>";

            $from ="gmi@gmibusinessparkmohali.com";
            // $to = "sahil.sharma@netmente.com";
            $to = "business@netmente.com";
            $subject = "Contact Us : GMI Business Park Mohali";
            $message =  $content;
            // $headers = "MIME-Version: 1.0" . "\r\n";
            // $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
            // $headers .= 'From: ' . $from . "\r\n";
            // $headers .= 'Reply-To: ' . $from . "\r\n";
            // $headers .= 'Cc: manpreets.narang@gmail.com'." \r\n";
            // $headers .= 'X-Mailer: PHP/' . phpversion();
            // $mail = mail($to, $subject, $message, $headers);

            $mail = new PHPMailer(true);
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                   //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'host';                                 //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'username';                             //SMTP username
            $mail->Password   = 'password';                             //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom($from);
            $mail->addAddress($to);                               //Add a recipient
            $mail->addReplyTo($from);
            $mail->addCC('manpreets.narang@gmail.com');
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    =  $message;
        
            $mail->send();

            echo message("Thank You For Contacting Us. Our representative will get back to you." , 'Success: ', 'success');
            return true;
        } catch (Exception $e) {
            echo message("Problem in Sending Mail.", 'ERROR: ', 'danger');
            return false;
        }
    } else {
        echo message('Please fill all the fields.', 'ERROR: ', 'danger');
        return false;
    }
} else {
    echo message("Our System Detected You as a Bot. Your Bot Score is " . $response->score, 'Error: ', 'danger');
    return false;
}


function message($message, $type, $class)
{
    echo '<div class="alert alert-' . $class . ' show" role="alert">
            <strong>' . $type . '</strong>' . $message . '
        </div>';
}
