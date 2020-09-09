<?php

// Establecemos UTF8 como cotejamiento de caracteres
if (!$class->conex->set_charset("latin1")) {
    printf("Error cargando el conjunto de caracteres utf8: %s\n", $class->conex->error);
    exit();
}

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
                if (isset($column[0])) {
                    $iniciales = mysqli_real_escape_string($class->conex, $column[0]);
                }
                $nombre = "";
                if (isset($column[1])) {
                    $nombre = mysqli_real_escape_string($class->conex, "$column[1]");
                }
                $tutor = "";
                if (isset($column[2])) {
                    $tutor = mysqli_real_escape_string($class->conex, $column[2]);
                }
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
            else
            {
                $ERR_MSG = "<br>Error en Fichero, no es el esperado.<br>";
            }
        }
    }
}
?>