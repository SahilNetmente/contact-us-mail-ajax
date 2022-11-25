<?php

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
    if (isset($_POST['userName']) && !empty($_POST['userName']) && isset($_POST['email']) && !empty($_POST['email'])) {
        try {
            //Content
            $content = "<b>Name:</b> " . $_POST["userName"] . "<br>";
            $content .= "<b>Email:</b> " . $_POST["email"] . "<br>";

            if (isset($_POST["subject"])) {
                $content .= "<b>Subject:</b> " . $_POST["subject"] . "<br>";
            }

            // if (isset($_POST["phoneNumber"])) {
            //     $content .= "<b>Phone Numbner:</b> " . $_POST["phoneNumber"] . "<br>";
            // }

            if (isset($_POST["message"])) {
                $content .= "<b>Message:</b> " . $_POST["message"] . "<br>";
            }

            $from = $_POST["email"];
            $to = "sahil.sharma@netmente.com";
            $subject = "Contact Form";
            $message =  $content;
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
            $headers .= 'From: ' . $from . "\r\n";
            $headers .= 'Reply-To: ' . $from . "\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();
            $mail = mail($to, $subject, $message, $headers);

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
    echo '<div class="alert alert-' . $class . ' alert-dismissible show" role="alert">
            <strong>' . $type . '</strong>' . $message . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>';
}
