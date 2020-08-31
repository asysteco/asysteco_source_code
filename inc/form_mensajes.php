<?php
?>
<div class="container-fluid" style="margin-top:50px">
    <div class="row">
        <div class="col-xs-12 col-md-4">
        <a id="show">
            <div class="new_mess_container">
                <span class="new_mess"></span>
                <span class="new_mess"></span>
                <span id="message_plus">Nuevo mensaje</span>
            </div>
        </a>
            <div id="formContent">
                <!-- Tabs Titles -->

                <!-- Icon -->
                <div class="fadeIn first" id ="mostrar">
                    <h2>Enviar a: </h2>
                

                <!-- Login Form -->
                <form class="login-form" action="index.php?ACTION=mensajes&OPT=add" method="post">
                    <input type="text" name="ID" class="hidden" value="<?php echo $_SESSION['ID']; ?>">
                    <?php
                        include_once($dirs['inc'] . 'select_profesores.php');
                    ?>
                    </br></br>
                    <label for="Asunto">Asunto</label></br>
                    <input id="Asunto" type="text" minlength="2" maxlength="50" name="Asunto" class="fadeIn first" placeholder="Asunto" required autocomplete="off"></br>
                    <label for="Mensaje">Mensaje</label></br>
                    <textarea id="Mensaje" minlength="4" type="text" name="Mensaje" class="fadeIn second" placeholder="Escriba aquí su mensaje..." required autocomplete="off"></textarea></br>
                    <input type="submit" name="enviar_mensaje" value="Enviar" class="fadeIn third">
                </form>
                </div>
            </div>
        </div>
        


