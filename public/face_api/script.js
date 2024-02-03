let video = document.getElementById("video");
let canvas = document.body.appendChild(document.createElement("canvas"));
let ctx = canvas.getContext("2d");
let displaySize;

// let width = 1280;
// let height = 720;
let width = 600;
let height = 420;

var base_url = window.location.origin;

var host = window.location.host;

const input1 = document.getElementById('idCardRef');
const input2 = document.getElementById('selfieRef');

// const startSteam = () => {
//     console.log("----- START STEAM ------");
//     navigator.mediaDevices.getUserMedia({
//         video: {width, height},
//         audio : false
//     }).then((steam) => {video.srcObject = steam});
// }

// console.log(faceapi.nets);
// // console.log(base_url);

// console.log("----- START LOAD MODEL ------");
// Promise.all([
//     faceapi.nets.ageGenderNet.loadFromUri('face_api/models'),
//     faceapi.nets.ssdMobilenetv1.loadFromUri('face_api/models'),
//     faceapi.nets.tinyFaceDetector.loadFromUri('face_api/models'),
//     faceapi.nets.faceLandmark68Net.loadFromUri('face_api/models'),
//     faceapi.nets.faceRecognitionNet.loadFromUri('face_api/models'),
//     faceapi.nets.faceExpressionNet.loadFromUri('face_api/models')
// ]).then(startSteam);


// async function detect() {
//     const detections = await faceapi.detectAllFaces(video)
//                                 .withFaceLandmarks()
//                                 .withFaceExpressions()
//                                 .withAgeAndGender();
//     //console.log(detections);
    
//     ctx.clearRect(0,0, width, height);
//     const resizedDetections = faceapi.resizeResults(detections, displaySize)
//     faceapi.draw.drawDetections(canvas, resizedDetections);
//     // faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
//     faceapi.draw.drawFaceExpressions(canvas, resizedDetections);

//     console.log(resizedDetections);
//     resizedDetections.forEach(result => {
//         const {age, gender, genderProbability} = result;
//         new faceapi.draw.DrawTextField ([
//             `${Math.round(age,0)} Tahun`,
//             `${gender} ${Math.round(genderProbability)}`
//         ],
//         result.detection.box.bottomRight
//         ).draw(canvas);
//     });
// }

// video.addEventListener('play', ()=> {
//     displaySize = {width, height};
//     faceapi.matchDimensions(canvas, displaySize);

//     setInterval(detect, 100);
// })

setInterval(detect, 100);
async function detect() {

    await faceapi.nets.ssdMobilenetv1.loadFromUri('face_api/models');
    await faceapi.nets.tinyFaceDetector.loadFromUri('face_api/models');
    await faceapi.nets.faceLandmark68Net.loadFromUri('face_api/models');
    await faceapi.nets.faceRecognitionNet.loadFromUri('face_api/models');
    await faceapi.nets.faceExpressionNet.loadFromUri('face_api/models');

    // detect a single face from the ID card image
    const idCardFacedetection = await faceapi.detectSingleFace(input1,
        new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks().withFaceDescriptor();

    // detect a single face from the selfie image
    const selfieFacedetection = await faceapi.detectSingleFace(input2,
        new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks().withFaceDescriptor();
    
    // console.log(idCardFacedetection);
    if(idCardFacedetection && selfieFacedetection){
        // Using Euclidean distance to comapare face descriptions
        const distance = faceapi.euclideanDistance(idCardFacedetection, selfieFacedetection);
        console.log(distance);
    }
    // const distance = faceapi.euclideanDistance(detection1, detection2);
    // console.log(distance);

    faceapi.matchDimensions(canvas, input1)

    fullFaceDescriptions = faceapi.resizeResults(idCardFacedetection, input1)
    faceapi.draw.drawDetections(canvas, fullFaceDescriptions)
    faceapi.draw.drawFaceLandmarks(canvas, fullFaceDescriptions)
}