<?php

echo '
    <video id="player" controls autoplay hidden></video>
    <div hidden>
        <button id="captureSnapshotButton">Capture Snapshot</button>
        <button id="attemptDecodeButton" disabled>Attempt Decode</button>
        <button id="startAutoCaptureButton">Start Auto-Capture</button>
        <button id="stopAutoCaptureButton">Stop Auto-Capture</button>
        <button id="stopCameraButton">Stop Camera</button>
    </div>
        <canvas id="qr-canvas" width=240 height=240></canvas>
        <h2>Respuesta de escaner:</h2>
    <div id="output" class="respuesta"></div>
';

include_once "js/qr-reader.js";
?>