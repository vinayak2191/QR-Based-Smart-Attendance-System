<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <style>
       body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    background:linear-gradient(135deg,#1d3557,#457b9d) ;
    color: white;
    transition: background-color 0.3s, color 0.3s;
    position: relative;
}

header {
    background:linear-gradient(135deg,#1d3557,#457b9d) ;

    padding: 1.5rem;
    text-align: center;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
}

header h1 {
    margin: 0;
    font-size: 2rem;
    color: #ffffff;
}

nav {
    background:linear-gradient(135deg,#1d3557,#457b9d) ;

    display: flex;
    justify-content: center;
    gap: 2rem;
    padding: 1rem 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

nav a {
    color: white;
    text-decoration: none;
    font-size: 1.1rem;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease-in-out;
    border-radius: 5px;
    position: relative;
}

nav a::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: #ffd700;
    transition: width 0.3s;
}

nav a:hover::after {
    width: 100%;
}

nav a:hover {
    color: #ffd700;
    background-color: rgba(255, 255, 255, 0.1);
}

nav a:active {
    transform: scale(0.95);
}

.main-wrapper {
    display: flex;
    justify-content: space-between;
    margin: 20px;
}

/* Filters Section */
.filters {
    width: 45%;
    display: flex;
    flex-direction: column;
    gap: 15px;
    background-color: rgba(255, 255, 255, 0.2);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 0px 30px rgba(227, 228, 237, 0.37);
}

.filters label {
    font-weight: bold;
}

.filters select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%;
}

.filters button {
    padding: 10px 20px;
    background-color: rgba(255, 255, 255, 0.2);
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

.filters button:hover {
    background-color: rgba(255, 255, 255, 0.06);
}

/* QR Code Display Section */
.qr-display {
    width: 45%;
    display: flex;
    flex-direction: column;
    gap: 15px;
    background-color: rgba(255, 255, 255, 0.2);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 0px 30px rgba(227, 228, 237, 0.37);
    justify-content: center;
    align-items: center;
}

#qrCodeCanvas {
    border: 1px solid #ccc;
    display: block;
    width: 200;
    height: 200;
}

@media (max-width: 768px) {
    .main-wrapper {
        flex-direction: column; /* Stack sections vertically */
        align-items: center;   /* Center items horizontally */
        margin: 10px;         /* Reduce margin for smaller screens */
    }

    .filters,
    .qr-display {
        width: 90%;  /* Full width for smaller screens */
         margin-bottom: 20px; /* Add margin between the sections */
    }
    nav{
         flex-direction: column; /* Stack navigation links */
         gap:10px
     }
    nav a{
        width: fit-content; /* Set navigation link width to fit content */
    }
    #qrCodeCanvas{
        width: 150px;
         height: 150px;
    }
}
    </style>
</head>

<body>
    <header>
        <h1>Welcome to Attendance System</h1>
    </header>
    <nav>
        <a href="attendance.php">Attendance</a>
        <a href="teacherDashBoard.php">Dashboard</a>
        <a href="present.php">Attendance Records</a>
        <a href="teacherLogin.php" onclick="logout()">Logout</a>
    </nav>

    <div class="main-wrapper">
        <!-- Filters Section -->
        <div class="container filters">
            <h2>Generate Attendance QR Code</h2>
            <label for="departmentSelect">Select Department:</label>
            <select id="departmentSelect">
                <option value="">Select Department</option>
                <option value="BSc(C.S)">BSc(C.S)</option>
                <option value="BBA">BBA</option>
                <option value="BBA(C.A)">BBA(C.A)</option>
                <option value="MSc(C.S)">MSc(C.S)</option>
                <option value="MSc(C.A)">MSc(C.A)</option>
            </select>

            <label for="yearSelect">Select Year:</label>
            <select id="yearSelect">
                <option value="">Select Year</option>
                <option value="first">First Year</option>
                <option value="second">Second Year</option>
                <option value="third">Third Year</option>
                <option value="last">Fourth Year</option>
            </select>

            <label for="subjectSelect">Select Subject:</label>
            <select id="subjectSelect">
                <option value="">Select Subject</option>
                <option value="A">A Subject</option>
                <option value="B">B Subject</option>
                <option value="C">C Subject</option>
                <option value="D">D Subject</option>
            </select>

            <label for="teacherSelect">Select Teacher:</label>
            <select id="teacherSelect">
                <option value="">Select Teacher</option>
                <option value="X">X Teacher</option>
                <option value="Y">Y Teacher</option>
                <option value="Z">Z Teacher</option>
            </select>

            <button onclick="generateQRCode()">Generate QR Code</button>
        </div>

        <!-- QR Code Display Section -->
        <div class="container qr-display">
            <canvas id="qrCodeCanvas"></canvas>
        </div>
    <!-- <script>
        // Generate QR Code
        function generateQRCode() {
            const department = document.getElementById("departmentSelect").value;
            const year = document.getElementById("yearSelect").value;
            const subject = document.getElementById("subjectSelect").value;
            const teacher = document.getElementById("teacherSelect").value;

            if (department && year && subject && teacher) {
                const qrData = `Department: ${department}, Year: ${year}, Subject: ${subject}, Teacher: ${teacher}`;
                QRCode.toCanvas(document.getElementById('qrCodeCanvas'), qrData, function(error) {
                    if (error) console.error(error);
                });
            } else {
                alert("Please select all fields!");
            }
        }
    </script> -->
    <script>
        // Generate QR Code
        function generateQRCode() {
            const department = document.getElementById("departmentSelect").value;
            const year = document.getElementById("yearSelect").value;
            const subject = document.getElementById("subjectSelect").value;
            const teacher = document.getElementById("teacherSelect").value;
            const timestamp = Date.now(); // Current timestamp in milliseconds

            if (department && year && subject && teacher) {
                const qrData = `Department: ${department}, Year: ${year}, Subject: ${subject}, Teacher: ${teacher}, timestamp: ${timestamp}`;

                QRCode.toCanvas(document.getElementById('qrCodeCanvas'), qrData, function(error) {
                    if (error) console.error(error);
                });

                alert("QR Code generated! Valid for 05 minutes.");
            } else {
                alert("Please select all fields!");
            }
        }

        // Validate QR Code
        function validateQRCode(scannedData) {
            try {
                const currentTime = Date.now();
                const qrData = JSON.parse(scannedData); // Parse the scanned QR code data
                const timeDifference = (currentTime - qrData.timestamp) / 1000 / 60; // Difference in minutes

                if (timeDifference <= 10) {
                    alert("QR Code is VALID!");
                } else {
                    alert("QR Code has EXPIRED!");
                }
            } catch (error) {
                console.error("Invalid QR Code Data:", error);
                alert("Invalid QR Code!");
            }
        }
    </script>
</body>

</html>