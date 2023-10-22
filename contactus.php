<?php 
session_start();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images/favicon.png">
    <meta charset="UTF-8">
    <title>SmartDilig - About Us</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        h1 {
            margin: 0;
        }
        h3 {
            text-align: center;

        }
        nav {
            background-color: #eee;
            padding: 10px;
        }
        main {
            margin: 20px;
            background-color: #F5F8ED;
            padding: 20px;
            padding-bottom: 5px;
            border-radius: 5px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
            border: 1px solid;
        }
        .team-list {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: space-around;
            flex-direction: row;
            flex-wrap: wrap;
            text-align: center;

        }
        .team-list li {
    flex: 1;
    padding: 0 10px;
    max-width: 300px;
    font-size: 18px;
}

    .contact-info {
        font-size: 17px; /* Make the font size smaller */
    }

    .small-text {
        font-size: 15px; /* Further reduce the font size for phone numbers and email addresses */
    }

        .history {
            margin-top: 20px;
        }
        footer {
            font-size: large;
            color: #261f46;
            padding: 5px;
            text-align: center;
        }
        p{
            font-size: 20px;
            text-align: justify;
        }
        .aboutusimage{
            width: 100px;
            height: 100px;
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);

        }
        /* Style for the Contact Form */
        form {
        margin-top: 20px;
        border: 1px solid #ccc;
        padding: 30px;
        max-width: 300px;
        margin: 0 auto;
        background-color: #f5f5f5;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
        display: block;
        margin-bottom: 10px;
        font-size: 15px;
        font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        textarea {
        width: 100%;
        padding: 10px 0px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        }

        textarea {
        resize: vertical; /* Allows vertical resizing for the textarea */
        }

        button {
        background-color: #3c4e77;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        }

        button:hover {
        background-color: #2a3757; /* Darker shade on hover */
        }

    </style>
    <script src="https://cdn.jsdelivr.net/npm/hamburgers@1.2.1/index.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/hamburgers@1.2.1/dist/hamburgers.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
    
</head>
<body>
    <div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="header">
        <img class="logo" src="images/logo.png" alt="Logo">
        <h2>SmartDilig</h2>
    </div>


    <?php
    if (isset($_SESSION['id'])) {
        // The user is logged in, so display these links
        echo '<a href="/"><img src="images/dashboard.png" class="navicon">Dashboard</a>';
        echo '<a href="SensorData.php" id="sensorDataLink"><img src="images/sensor.png" class="navicon">Sensor Data</a>';
    }
    ?>

    <a href="aboutus.php"><img src="images/aboutus.png" class="navicon">About Us</a>
    <a href="contactus.php"><img src="images/contactus.png" class="navicon">Contact Us</a>

    <?php
    if (!isset($_SESSION['id'])) {
        // The user is logged in, so display the "Logout" link
        echo '<a href="login.php"><img src="images/login.png" class="navicon">Login</a>';
    }
    ?>
    <?php
    if (isset($_SESSION['id'])) {
        // The user is logged in, so display the "Logout" link
        echo '<a href="logout.php"><img src="images/logout.png" class="navicon">Logout</a>';
    }
    ?>


    <button class="hamburger" type="button">
    <span class="hamburger-box">
    <span class="hamburger-inner"></span>
    </span>
    </button> 
    <script>
        const hamburgerButton = document.querySelector('.hamburger');
      const sidebar = document.querySelector('.sidebar');
  
      hamburgerButton.addEventListener('click', function() {
        sidebar.classList.toggle('open');
        hamburgerButton.classList.toggle('hamburger--collapse');
        hamburgerButton.classList.toggle('is-active');
      });
      </script>
</div>
<!-- Sidebar -->
    <div class="content" id="contentContainer">

        <main>
            <h1>Contact Us</h1>
            <p>If you have any questions, feedback, or need assistance, please don't hesitate to get in touch with us. You can reach out to us through the following methods:</p>
        
            <h2>Contact Information</h2>
            <ul class="team-list">
                <li>
                    <img src="images/erick.png" class="aboutusimage">
                    <br>Erick Ferdinand Tolentino
                    <span class="contact-info">
                        <br><span class="small-text">tolentinoem@students.nu-fairview.edu.ph</span>
                        <br><span class="small-text">09157736164</span>
                    </span>
                </li>
                <li>
                    <img src="images/prinze.png" class="aboutusimage">
                    <br>Prinze Paler
                    <span class="contact-info">
                        <br><span class="small-text">palerpv@students.nu-fairview.edu.ph</span>
                        <br><span class="small-text">09564352623</span>
                    </span>
                </li>
                <li>
                    <img src="images/sosa.png" class="aboutusimage">
                    <br>Angelo Sosa
                    <span class="contact-info">
                        <br><span class="small-text">sosaabc@students.nu-fairview.edu.ph</span>
                        <br><span class="small-text">09299782069</span>
                    </span>
                </li>
            </ul>
            
        
            <h3>Contact Form</h3>
            <form action="process_contact_form.php" method="POST">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="4" required></textarea>
                
                <button type="submit">Send Message</button>
            </form>

            <footer>
                &copy; 2023 SmartDilig. All rights reserved.
            </footer>
        </main>
        
    </div>

</div>
</body>
</html>