>   <?php

// Redirect to a new page
header("Location: contactus.html");
exit; // Ensure no further PHP code is executed




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set recipient email address
    $to_email = "recipient@example.com"; // Replace with the actual recipient's email address

    // Get form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    // Create email headers
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Compose the email message
    $subject = "New Contact Form Submission from $name";
    $email_message = "Name: $name\n";
    $email_message .= "Email: $email\n";
    $email_message .= "Message:\n$message\n";

    // Send the email
    if (mail($to_email, $subject, $email_message, $headers)) {
        echo "Thank you for your message! We'll get back to you shortly.";
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
} else {
    // Handle non-POST requests
    echo "Invalid request.";
}
?>
