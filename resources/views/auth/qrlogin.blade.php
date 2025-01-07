<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login dengan QR Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #reader {
            width: 300px;
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5 text-center">
        <h1>Login dengan QR Code</h1>
        <div id="reader"></div>
        <p id="result"></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function onScanSuccess(decodedText, decodedResult) {
                window.location.href = decodedText; // Redirect to the URL scanned from QR code
            }

            function onScanFailure(error) {
                console.warn(`Code scan error = ${error}`);
            }

            let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 200 });
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });
    </script>
</body>
</html>
