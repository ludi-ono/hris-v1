<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face API</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: darkslategray;
        }
        canvas {
            position: absolute;
        }
    </style>
</head>
<body>
    <video id="video" autoplay></video>

    <div className="gallery">
        <img id="idCardRef" src="{{ asset('/face_api/IMG1.jpg') }}" alt="ID card" height="500" />
    </div>

    <div className="gallery">
        <img id="selfieRef" src="{{ asset('/face_api/IMG2.jpg') }}" alt="Selfie" height="500" />
    </div>
    <!-- <script src="face-api.min.js"></script>
    <script src="script.js"></script> -->
    <script src="{{ asset('/face_api/face-api.min.js') }}"></script>
    <script src="{{ asset('/face_api/script.js') }}"></script>
</body>
</html>