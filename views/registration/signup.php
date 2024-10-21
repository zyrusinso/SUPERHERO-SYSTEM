<?php
  session_start();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_SESSION['first_name'] = $_POST['first_name'];
      $_SESSION['last_name'] = $_POST['last_name'];
      $_SESSION['email'] = $_POST['email'];
      header('Location: proceed-continue.php');
      exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Registration - Brgy Sta. Lucia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../../dist/css/index.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <link rel="icon" href="../../dist/images/favicon.ico" type="image/x-icon">
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Portal Registration - Brgy Sta. Lucia">
    <meta property="og:description" content="Your description here for the portal.">
    <meta property="og:image" content="URL_to_your_image.jpg">
    <meta property="og:url" content="https://yourwebsite.com">
    <meta property="og:type" content="website">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Portal Registration - Brgy Sta. Lucia">
    <meta name="twitter:description" content="Your description here for the portal.">
    <meta name="twitter:image" content="URL_to_your_image.jpg">
    <meta name="twitter:url" content="https://yourwebsite.com">
</head>

<body>
    <div id="app">
        <section id="login-container" class="d-flex align-items-center justify-content-center vh-100">
            <div class="login-wrapper">
                <div class="login-header">
                    <h1 class="text-dark">Sign Up</h1>
                    <p class="text-muted">New User? Provide all input fields we need.</p>
                </div>
                <form id="signup-form">
                    <div class="mb-2">
                        <label for="name" class="form-label">First name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-2">
                        <label for="lastName" class="form-label">Last name</label>
                        <input type="text" class="form-control" id="lastName" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <button type="button" class="btn btn-danger w-100 mb-3" id="register-button">
                        Proceed
                    </button>
                    <a href="../../" class="btn btn-light w-100">Sign In</a>
                </form>
            </div>
        </section>

        <!-- This website is not mobile friendly ^^ -->
        <!-- <div class="d-md-none d-none vh-100 d-flex align-items-center justify-content-center" id="desktop-message">
            <span>This website is not yet mobile-friendly.</span>
        </div> -->

        <!-- The elligible rights to authorize software usage is limited -->
        <!-- <div class="principal-author">
            <span>This property are part of belongings to Brgy. Sta Lucia</span>
        </div> -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        document.getElementById('register-button').addEventListener('click', function () {
            const name = document.getElementById('name').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            const email = document.getElementById('email').value.trim();
            // const password = document.getElementById('password').value.trim();
            // const confirmPassword = document.getElementById('confirm-password').value.trim();

            // Simple regex for email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Check for empty fields
            if (name === '' || lastName === '' || email === '') {
                alert('Please fill out all input fields before proceeding.');
                return;
            }

            // Validate first name
            if (name.length < 2) {
                alert('First name must contain at least 2 letters.');
                return;
            }

            // Validate last name
            if (lastName.length < 2) {
                alert('Last name must contain at least 2 letters.');
                return;
            }

            // Validate email
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address.');
                return;
            }

            // Validate password matching
            // if (password !== confirmPassword) {
            //     alert('Passwords do not match.');
            //     return;
            // }
        });

        // Function to filter input for letters only (REALTIME HUHH)
        function filterInput(event) {
            const regex = /[^A-Za-z]/g;
            if (regex.test(event.target.value)) {
                event.target.value = event.target.value.replace(regex, '');
            }
        }

        document.getElementById('name').addEventListener('input', filterInput);
        document.getElementById('lastName').addEventListener('input', filterInput);
    </script>

</body>

</html>
