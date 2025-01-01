<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
       body {
    margin: 0;
    padding: 0;
    font-family: 'Arial', sans-serif;
    background: linear-gradient(135deg, #1d3557, #457b9d);
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
}

/* Header Styles */
header {
    width: 100%;
    padding: 20px 0;
    text-align: center;
    margin-bottom: 20px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

header h1 {
    font-size: 2rem;
    font-weight: bold;
}

/* Navigation Styles */
nav {
    width: 100%;
    background: rgba(255, 255, 255, 0.1);
    padding: 10px 0;
    margin-bottom: 20px;
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: center;
    align-items: center;
}

nav a {
    color: #fff;
    text-decoration: none;
    padding: 10px 20px;
    margin: 0 10px;
    border-radius: 5px;
    transition: background-color 0.3s ease, transform 0.2s ease;
    position: relative;
    overflow: hidden;
}

nav a:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, #00b4d8, #0077b6);
    z-index: -1;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
    border-radius: 5px;
}

nav a:hover:before {
    transform: scaleX(1);
}

nav a:hover {
    transform: translateY(-2px);
}

/* Container Styles */
.container {
    width: 80%;
    max-width: 1200px;
    background-color: rgba(255, 255, 255, 0.1);
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    text-align: center;
    backdrop-filter: blur(10px);
}

.container h1,
.container h4 {
    color: #f1faee;
    margin-bottom: 15px;
}

.container hr {
    border: 0;
    height: 1px;
    background: #fff;
    margin: 15px 0;
}

/* QR Code Scanner Styles */
#reader {
    width: 300px;
    height: 300px;
    border: 2px solid #fff;
}

.result {
    background-color: rgba(255, 255, 255, 0);
    color: #fff;
    padding: 20px;
    text-align: center;
    border-radius: 5px;
    margin-top: 10px;
}

/* Form Styles */
#attendanceForm {
    margin-top: 20px;
}

/* Button styling */
.button {
    width: 100%;
    padding: 12px;
    font-size: 1.2rem;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.button:hover {
    background-color: #0056b3;
}
.center {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 60vh;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container {
        width: 90%;
        padding: 15px;
    }

    header h1 {
        font-size: 1.8rem;
    }

    nav a {
        padding: 8px 15px;
        margin: 0 5px;
    }

    #reader {
        width: 250px;
        height: 250px;
    }
    
}
    </style>
</head>

<body class="dark-mode">
    <header>
        <div>
            <h1 >Attendance</h1>
        </div>
       
    </header>

    <div class="container">
        <h2 style="text-align: center;">Login</h2>
        <div style="justify-content: center;display: flex;gap:20px">
            <button class="button" onclick="redirectToLogin('student')">Student Login</button>
            <button class="button" onclick="redirectToLogin('teacher')">Teacher Login</button>
        </div>
    </div>

    <script>
        function redirectToLogin(userType) {
            if (userType === 'student') {
                window.location.href = 'login.php';
            } else if (userType === 'teacher') {
                window.location.href = 'teacherLogin.php';
            }
        }

        // Toggle between light and dark mode
        function toggleTheme() {
            var body = document.body;
            var themeIcon = document.querySelector(".icon-sun-moon");

            // Toggle the classes for light and dark mode
            if (body.classList.contains("dark-mode")) {
                body.classList.remove("dark-mode");
                body.classList.add("light-mode");
                themeIcon.textContent = "🌞"; // Sun icon for light mode
            } else {
                body.classList.remove("light-mode");
                body.classList.add("dark-mode");
                themeIcon.textContent = "🌙"; // Moon icon for dark mode
            }
        }
    </script>

    <footer>
        <div>
            <p>&copy; <?php echo date("Y"); ?> TECHACE. All Rights Reserved.</p>
            <div style="margin: 10px 0;">
                <a href="https://www.linkedin.com" target="_blank" style="color:black">LinkedIn</a> |
                <a href="https://github.com" target="_blank"style="color:black">GitHub</a> |
                <a href="https://www.instagram.com" target="_blank"style="color:black">Instagram</a>
            </div>
            <p><a href="contact.php"style="color:black">Contact Us</a> | <a href="#"style="color:black">Terms of Service</a></p>
        </div>
    </footer>

    <!-- FontAwesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>
