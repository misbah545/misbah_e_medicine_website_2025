<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Include database connection
include('../config/db.php');

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/home/root123/SDP 2024-2025/e-medicine-website/vendor/autoload.php';

// Email configuration
$from = 'emedicinewebsite@gmail.com';
$subject = 'Password Reset Request';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['forgot_email']), FILTER_VALIDATE_EMAIL);

    if (!$email) {
        echo "<script>alert('Please enter a valid email address.');</script>";
        exit();
    }

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM Customer WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $new_password = generateRandomPassword();
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $update_stmt = $conn->prepare("UPDATE Customer SET password = ? WHERE email = ?");
        $update_stmt->bind_param("ss", $hashed_password, $email);

        if ($update_stmt->execute()) {
            $mail = new PHPMailer(true);

            try {
                // SMTP settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'emedicinewebsite@gmail.com';
                $mail->Password   = 'jpqt dkaw zhgs qclr';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // SMTP Debugging
                $mail->SMTPDebug  = 0;
                $mail->Debugoutput = 'html';

                // Recipients
                $mail->setFrom($from, 'E-Medicine Website');
                $mail->addAddress($email);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = "Your password has been reset. Your new password is: <b>$new_password</b>";

                $mail->send();
                echo "<script>alert('A new password has been sent to your email address.'); window.location.href='http://localhost:8000/frontend/pages/Customer_login.html';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Failed to send email. Error: " . $mail->ErrorInfo . "');</script>";
            }
        } else {
            echo "<script>alert('Failed to update password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('No account found with this email address.'); window.location.href='http://localhost:8000/frontend/pages/forgot_password.html';</script>";
    }
}

function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $password;
}
?>
