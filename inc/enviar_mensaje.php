<?php

if($_POST['ID'] != '')
{
    if($class->query("INSERT INTO $class->mensajes(ID_PROFESOR, ID_DESTINATARIO, Asunto, Mensaje) 
    VALUES ('$_POST[ID]', '$_POST[Profesor]', '$_POST[Asunto]', '$_POST[Mensaje]')"))
    {
        $MSG = "Mensaje enviado correctamente.";
        header('Location:index.php?ACTION=mensajes');
    }
    else
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}

?>