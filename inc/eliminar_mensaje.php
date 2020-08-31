<?php
if($res = $class->query("SELECT ID_PROFESOR FROM Mensajes WHERE ID='$_GET[ID]' AND ID_PROFESOR = '$_SESSION[ID]' AND Borrado_Destinatario=1"))
{
    if($res->num_rows == 1)
    {
        if($response = $class->query("DELETE FROM $class->mensajes  WHERE $class->mensajes.ID='$_GET[ID]'"))
        {
            header('Location:index.php?ACTION=mensajes');
        }
        elseif($res = $class->query("SELECT ID_PROFESOR FROM Mensajes WHERE ID='$_GET[ID]' AND ID_PROFESOR = '$_SESSION[ID]' AND Borrado_Profesor=1"))
        {
            if($res->num_rows == 1)
            {
                if($response = $class->query("DELETE FROM $class->mensajes  WHERE $class->mensajes.ID='$_GET[ID]'"))
                {
                header('Location:index.php?ACTION=mensajes');
                }
                else
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
            }
            else
            {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
        }
        else
        {
            $ERR_MSG = $class->ERR_ASYSTECO;
            
        }
    }   
    else
    {
        if($res = $class->query("SELECT ID_PROFESOR FROM Mensajes WHERE ID='$_GET[ID]' AND ID_PROFESOR = '$_SESSION[ID]'"))
            {
                if($res->num_rows == 1)
                {
                    if($response = $class->query("UPDATE $class->mensajes SET Borrado_Profesor=1 WHERE $class->mensajes.ID='$_GET[ID]'"))
                    {
                        header('Location:index.php?ACTION=mensajes');
                    }
                    else
                    {
                        $ERR_MSG = $class->ERR_ASYSTECO;
                    }
                }
                else
                {
                    if($response = $class->query("UPDATE $class->mensajes SET Borrado_Destinatario=1 WHERE $class->mensajes.ID='$_GET[ID]'"))
                    {
                        header('Location:index.php?ACTION=mensajes');
                    }
                    else
                    {
                        $ERR_MSG = $class->ERR_ASYSTECO;
                    }
                }
            }
    }
}
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}

