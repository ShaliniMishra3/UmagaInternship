<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
 


$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "applicants";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$qualification = $_POST['qualification'];
$ctc = $_POST['ctc'];
$skills = $_POST['skills'];

// Handle file upload
$upload_dir = "applicants_CV/";
$resume_name = basename($_FILES['resume']['name']);
$resume_tmp_name = $_FILES['resume']['tmp_name'];

if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (move_uploaded_file($resume_tmp_name, $upload_dir . $resume_name)) {
    // Insert data into database
    $sql = "INSERT INTO applicants (full_name, email, phone, qualification, expected_ctc, skills, resume)
            VALUES ('$full_name', '$email', '$phone', '$qualification', '$ctc', '$skills', '$resume_name')";

    if ($conn->query($sql) === TRUE) {
        // Send acknowledgment email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'shalinimishraji2002@gmail.com'; // Your email
            $mail->Password = 'frbi sqvy zrou hlkl'; // Your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('shalinimishraji2002@gmail.com', 'Job Application Form');
            $mail->addAddress('shalinimishraji2002@gmail.com.com','Form'); // User's email

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Thank you for applying!';
            $mail->Body = 'Thank you for applying to our company. Stay tuned for more updates.';

            $mail->send();
            echo "Thank you for applying! An acknowledgment email has been sent.";
        } catch (Exception $e) {
            echo "Error sending acknowledgment email: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error uploading file.";
}

$conn->close();
?>