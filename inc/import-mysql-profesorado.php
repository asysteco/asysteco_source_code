<?php

if (isset($_POST["import"])) {
    
    $fileName = $_FILES["file"]["tmp_name"];
    
    if ($_FILES["file"]["size"] > 0) {
        
        $file = fopen($fileName, "r");
        $row = 1;
        while (($column = fgetcsv($file, 10000, ";")) !== FALSE) 
        {
            $columnas = count($column);
            if($columnas == 3)
            {
                if($row == 1)
                {
                    foreach($column as $dato)
                    {
                        if(preg_match('/^[A-Z]+$/i', $dato))
                        {
                            $cabecera = true;
                        }
                        else
                        {
                            $cabecera = false;
                        }
                    }
                    if($cabecera)
                    {
                        $row++;
                        continue;
                    }
                }
                
                $iniciales = "";
                if (isset($column[0])) 
                {
                    $iniciales = mysqli_real_escape_string($class->conex, $column[0]);
                }
                $nombre = "";
                if (isset($column[1])) 
                {
                    $nombre = mysqli_real_escape_string($class->conex, "$column[1]");
                }
                $tutor = "";
                if (isset($column[2])) 
                {
                    $tutor = mysqli_real_escape_string($class->conex, $column[2]);
                }

                if ($response = $class->query("SELECT Iniciales, Nombre FROM Profesores WHERE Iniciales = '$iniciales'")) 
                {
                    if ($response->num_rows > 0)
                    {
                        if ($resp = $class->query("SELECT Iniciales FROM Profesores WHERE Iniciales = '$iniciales' AND Nombre = '$nombre'"))
                        {
                            if ($resp->num_rows > 0)
                            {
                                continue;
                            }
                            else
                            {
                                $profe = $response->fetch_assoc();
                                $msg .= "El Profesor <b>$nombre</b> repite Iniciales <b>$iniciales</b> con <b>$profe[Nombre]</b>,</br><b>$nombre</b> no será importado, debe cambiar sus iniciales en el fichero CSV.</br>";
                            }
                        }
                        else
                        {
                            $ERR_MSG = $class->ERR_ASYSTECO;
                        }
                    }
                    else
                    {
                        $password = $class->encryptPassword($iniciales . '12345');
                        $tipo = mysqli_real_escape_string($class->conex, 2);
                        $activo = mysqli_real_escape_string($class->conex, 1);
                        $sustituido = mysqli_real_escape_string($class->conex, 0);
                        if(! $class->query("INSERT INTO Profesores (Iniciales, Nombre, Password, TIPO, Tutor, Activo, Sustituido)
                        values (
                            '$iniciales',
                            '$nombre',
                            '$password',
                            '$tipo',
                            '$tutor',
                            $activo,
                            $sustituido)"))
                        {
                            $ERR_MSG = "<br>Error al importar datos desde CSV.<br>";
                            $ERR_MSG .= "<br>vuelta $row <br>";
                            $ERR_MSG .= $class->ERR_ASYSTECO;
                        }
                        else
                        {
                            $MSG = "Profesores importados correctamente.<br>";
                        }
                    }
                }
                else
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
            }
            else
            {
                $ERR_MSG = "<br>Error en Fichero, no es el esperado.<br>";
            }
        }
        if(isset($msg) && $msg != '')
        {
            $class->notificar($_SESSION['ID'], $msg);
            $ERR_MSG = "Revise Notificaciones ya que no ha sido posible importar todos los profesores.";
        }
        else
        {
            $MSG = "Profesores importados correctamente.";
        }
    }
}
?>