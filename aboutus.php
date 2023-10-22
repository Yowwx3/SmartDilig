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
        flex: 1; /* Equal distribution of space among list items */
        padding: 0 10px; /* Add padding for spacing */
        max-width: 300px; /* Set a maximum width for each item if needed */
        font-size: 18px;
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
        /* Default styles for large screens */
        .img-div {
            text-align: center;
        }

        .aboutus-header {
            width: 400px;
            height: 200px;
            text-align: center;
        }

        .featuresimg {
            width: 800px;
            height: 520px;
            text-align: center;
        }
        .mascot {
            width: 600px;
            height: 300px;
            text-align: center;
        }

        /* Responsive styles for smaller screens */
        @media screen and (max-width: 890px) {
            p{
            font-size: 15px;
            text-align: justify;
        }
            .aboutus-header {
                width: 100%; /* 100% width for smaller screens */
                height: auto; /* Allow height to adjust automatically */
            }

            .featuresimg {
                width: 100%; /* 100% width for smaller screens */
                height: auto; /* Allow height to adjust automatically */
            }
            .mascot {
                width: 100%; /* 100% width for smaller screens */
                height: auto; /* Allow height to adjust automatically */
            }
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
        <h1>About Us</h1>
        <div class="img-div"> <img class="aboutus-header" src="images/aboutus-header.png" ></div>


        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Welcome to SmartDilig: Sustainable Agriculture Smart Irrigation, where innovation meets agriculture. 
            Our mission is to provide a sustainable and efficient irrigation system to farms using state-of-the-art IoT technology.
            We're dedicated to making a positive impact by helping achieve SDGs 2 (Zero Hunger) and 6 (Clean Water and Sanitation)
            while conserving water, reducing costs, and increasing crop yields.</p>
        <h2>Our Team</h2>
        <ul class="team-list">
            <li>
                <img src="images/erick.png" class="aboutusimage">
                <br>Erick Ferdinand Tolentino<br>CEO
            </li>
            <li>
                <img src="images/prinze.png" class="aboutusimage">
                <br>Prinze Paler<br>Marketing Manager
            </li>
            <li>
                <img src="images/sosa.png" class="aboutusimage">
                <br>Angelo Sosa<br>Lead Developer
            </li>
        </ul>
        
        <h2 class="history">Innovation Profile</h2>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The proposed innovation aims to provide a sustainable and efficient irrigation system to the farm with an IoT-based device.
             The project's major goal is to integrate IoT technology to the farm while trying to help achieve SDGs 2 zero hunger and 
             6 clean water and sanitation—while conserving water, reducing costs, and increasing crop yields. 

        </p>
        <h2 class="history">Why choose us?</h2>
        <div class="img-div"> <img class="mascot" src="images/mascot.png" ></div>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sustainable Agriculture Smart Irrigation with Soil Quality Monitoring System offers a comprehensive and forward-thinking approach 
            that tackles the problems with conventional agricultural methods while fostering sustainability, effectiveness, and environmental
             responsibility.
        </p>
        <h2 class="history">Features to expect</h2>
        <div class="img-div"> <img class="featuresimg" src="images/features.png" ></div>

        <footer>
            &copy; 2023 SmartDilig. All rights reserved.
            </footer>
    </main>

    </div>

</div>
</body>
</html>