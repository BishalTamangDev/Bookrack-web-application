<?php

require_once __DIR__ . '/../../bookrack/app/connection.php';

require_once __DIR__ . '/../../bookrack/vendor/autoload.php';

// for user authentication
use Kreait\Firebase\Auth;

// for sending otp code to the email address
use PHPMailer\PHPMailer\PHPMailer;

// for checking the exception
use PHPMailer\PHPMailer\Exception;

// function to generate OTP code
function generateOTP()
{
    return rand(100000, 999999); // Generate a 6-digit OTP
}

function sanitizeEmail($email)
{
    return str_replace(['.', '@'], [',', ':'], $email);
}

// function to send otp code to the provided email address
function sendOTPEmail($from, $to, $otp)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0; // Disable verbose debug output
        $mail->isSMTP(); // Send using SMTP
        $mail->Host = 'smtp.example.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = $from; // SMTP username
        $mail->Password = '!Bookrack021508'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        //Recipients
        $mail->setFrom($from, 'Bookrack Admin');
        $mail->addAddress($to); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Your OTP Code';
        $mail->Body = 'Your OTP code is: ' . $otp;
        $mail->AltBody = 'Your OTP code is: ' . $otp; // Plain text version for non-HTML mail clients

        $mail->send();

        $_SESSION['status'] = "OTP has been sent.";
    } catch (Exception $e) {
        $_SESSION['status'] = "OTP couldn't be sent.";
    }
}