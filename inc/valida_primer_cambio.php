<?php

if(isset($_POST['new_password']))
{
$_POST['act_pass'] = $class->encryptPassword($_SESSION['Iniciales'] . '12345');
$_POST['new_pass'] = $class->encryptPassword($_POST['new_pass']);
$_POST['new_pass_c'] = $class->encryptPassword($_POST['new_pass_c']);
    if($response = $class->query("SELECT ID FROM $class->profesores WHERE ID='$_SESSION[ID]' AND Password = '$_POST[act_pass]'"))
    {
        if($response->num_rows == 1)
        {
            if($_POST['new_pass'] === $_POST['new_pass_c'])
            {
                if($class->query("UPDATE $class->profesores SET $class->profesores.Password='$_POST[new_pass]' WHERE $class->profesores.ID='$_SESSION[ID]'"))
                {
                    $MSG = 'Contraseña cambiada satisfatoriamente.';
                    $cambiada = true;
                    header("Refresh:1; url=index.php?ACTION=logout");
                    include_once($dirs['inc'] . 'errors.php');
                }
                else
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
            }
            else
            {
                $ERR_MSG = 'Las nuevas contraseñas no coinciden.';
            }
        }
        else
        {
            $ERR_MSG = 'Esta no es tu contraseña actual.';
        }
        
    }
    else
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }

}
if(isset($cambiada))
{
    include_once($dirs['inc'] . 'top-nav.php');
    include_once($dirs['inc'] . 'errors.php');
}
?>