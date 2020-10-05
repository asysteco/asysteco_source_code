<?php

echo '
    <h2>Respuesta de escaner:</h2>
    <div id="output" class="respuesta"><span id="empty"><h3>Acerque el código QR al lector...</h3></span></div>
    <form id="QR-form">
        <input type="text" id="QR-lector" style="color: white; position: absolute; left: -500px;">
    </form>
';

include_once "js/qr-reader.js";
?>