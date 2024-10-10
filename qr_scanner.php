<?php
session_start();
include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EastInsight</title>
    <link rel="stylesheet" href="style_qr.css">
</head>
<body>
    <div class="scanner-wrapper">
        <div class="scanner-header">
            <h1>EastInsight</h1>
        </div>
        <div class="scanner-container">
            <video id="video" class="video-container"></video>
        </div>
        <div id="output" class="output">
            <p>Scan QR Code</p>
        </div>
    </div>
    <!-- Include jsQR library -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.3.1/dist/jsQR.js"></script>
    <!-- QR code scanner script -->
    <script>
        const video = document.getElementById('video');

        // Access camera stream
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then(stream => {
                video.srcObject = stream;
                video.setAttribute("playsinline", true); // For iOS compatibility
                video.play();
                requestAnimationFrame(tick); // Start the QR code scanning loop
            })
            .catch(err => {
                console.error("Error accessing the camera: ", err);
                document.getElementById('output').innerHTML = "<p>Unable to access the camera. Please try again.</p>";
            });

        function tick() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                const canvas = document.createElement('canvas');
                const canvasContext = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvasContext.drawImage(video, 0, 0, canvas.width, canvas.height);

                const imageData = canvasContext.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, canvas.width, canvas.height, {
                    inversionAttempts: "dontInvert",
                });

                if (code) {
                    document.getElementById('output').innerHTML = `<p>QR Code detected: ${code.data}</p>`;
                    window.location.href = code.data; // Redirect in the same window
                    return; // Stop further scanning after a successful read
                }
            }
            requestAnimationFrame(tick); // Continue scanning if no code is found
        }
    </script>
</body>
</html>
