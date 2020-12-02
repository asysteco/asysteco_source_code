<?php

$ff = "tmp/";
$pf = "Profesores.csv";
$ho = "Horarios.csv";
$ma = "Marcajes.csv";
$fi = "Fichajes.csv";
chdir($ff);

// PROFESORES

// Escribimos los títulos para los campos
if ($respuesta = $class->query("SELECT Iniciales, Nombre, Tutor FROM Profesores WHERE TIPO <> 1 ORDER BY Profesores.Nombre ASC")) {
    if ($respuesta->num_rows > 0) {
        $fp = fopen($pf, 'w');
        $delimitador = ";";
        $titulo = [
            'INICIALES',
            'NOMBRE',
            'TUTOR'
        ];

        fputcsv($fp, $titulo, $delimitador);

        while ($datos = $respuesta->fetch_assoc()) {
            $campos = [
                utf8_decode($datos['Iniciales']),
                utf8_decode($datos['Nombre']),
                utf8_decode($datos['Tutor'])
            ];

            // Escibimos una línea por cada $datos
            fputcsv($fp, $campos, $delimitador);
        }
    } else {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}

// HORARIOS
$sql = "SELECT Horarios.*, Profesores.Nombre, Profesores.Iniciales, Diasemana.Diasemana, Aulas.Nombre as Aula, Cursos.Nombre as Grupo
    FROM Horarios INNER JOIN Profesores ON Horarios.ID_PROFESOR=Profesores.ID
    INNER JOIN Diasemana ON Diasemana.ID=Horarios.Dia
    INNER JOIN Aulas ON Aulas.ID=Horarios.Aula 
    INNER JOIN Cursos ON Cursos.ID=Horarios.Grupo
    ORDER BY ID_PROFESOR, Dia, Hora";

if ($response = $class->query($sql)) {
    if ($response->num_rows > 0) {
        $titulo = '';
        $delimitador = '';
        $fp = fopen($ho, 'w');
        $delimitador = ";";

        if (isset($options['edificios']) && $options['edificios'] > 1) {
            $titulo = [
                'GRUPO',
                'INICIALES',
                'AULA',
                'DIA',
                'HORA',
                'EDIFICIO'
            ];
        } else {
            $titulo = [
                'GRUPO',
                'INICIALES',
                'AULA',
                'DIA',
                'HORA'
            ];
        }

        fputcsv($fp, $titulo, $delimitador);

        while ($datos = $response->fetch_assoc()) {
            if ($nombre = $class->query("SELECT Nombre, Iniciales FROM Profesores WHERE ID='$datos[ID_PROFESOR]'")) {
                $prof = $nombre->fetch_assoc();
                $profesor = $prof['Nombre'];
                $iniciales = $prof['Iniciales'];
            } else {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
            if (isset($options['edificios']) && $options['edificios'] > 1) {
                $campos = [
                    utf8_decode($datos['Grupo']),
                    utf8_decode($iniciales),
                    utf8_decode($datos['Aula']),
                    $datos['Dia'],
                    $datos['Hora'],
                    $datos['Edificio'],
                ];
            } else {
                $campos = [
                    utf8_decode($datos['Grupo']),
                    utf8_decode($iniciales),
                    utf8_decode($datos['Aula']),
                    $datos['Dia'],
                    $datos['Hora'],
                ];
            }

            // Escibimos una línea por cada $datos
            fputcsv($fp, $campos, $delimitador);
        }
    } else {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
} else {
    $ERR_MSG = $class->ERR_ASYSTECO;
}


// MARCAJES

// Escribimos los títulos para los campos

$sql = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
    INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
WHERE Profesores.Activo=1 AND Marcajes.Fecha<=NOW()
ORDER BY Profesores.Nombre ASC";

if ($response = $class->query($sql)) {

    $page_size = 15000;
    $total_records = $response->num_rows;
    $count = ceil($total_records / $page_size);

    if ($response->num_rows > 0) {
        $titulo = '';
        $delimitador = '';
        $fp = fopen($ma, 'w');
        $delimitador = ";";
        $titulo = [
            'INICIALES',
            'PROFESOR',
            'FECHA',
            'HORA',
            'DIA',
            'DIA SEMANA',
            'ASISTENCIA',
            'ACTIVIDAD EXTRAESCOLAR'
        ];

        fputcsv($fp, $titulo, $delimitador);

        for ($i = 0; $i <= $count; $i++) {
            $offset_var = $i * $page_size;
            $query = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
                        FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
                            INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
                        WHERE Profesores.Activo=1 AND Marcajes.Fecha<=NOW()
                        ORDER BY Marcajes.Fecha, Profesores.Nombre ASC 
                        LIMIT $page_size OFFSET $offset_var";
            $result =  $class->query($query);

            while ($datos = $result->fetch_assoc()) {
                $sep = preg_split('/[ -]/', $datos['Fecha']);
                $dia = $sep[2];
                $m = $sep[1];
                $Y = $sep[0];

                if ($datos['Asiste'] == 0) {
                    $asist = "NO";
                    $extra = "NO";
                } elseif ($datos['Asiste'] == 1) {
                    $asist = "SI";
                    $extra = "NO";
                } elseif ($datos['Asiste'] == 2) {
                    $asist = "SI";
                    $extra = "SI";
                }

                $campos = [
                    utf8_decode($datos['Iniciales']),
                    utf8_decode($datos['Nombre']),
                    "$dia/$m/$Y",
                    $datos['Hora'],
                    $datos['Dia'],
                    utf8_decode($datos['Diasemana']),
                    $asist,
                    $extra
                ];

                // Escibimos una línea por cada $datos
                fputcsv($fp, $campos, $delimitador);
            }
        }
    } else {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}

// FICHAJE

// Escribimos los títulos para los campos

$sql = "SELECT ID_PROFESOR, Nombre, F_entrada, F_Salida, DIA_SEMANA, Fecha
FROM (Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID) 
ORDER BY Profesores.Nombre ASC";

if ($response = $class->query($sql)) {
    $page_size = 15000;
    $total_records = $response->num_rows;
    $count = ceil($total_records / $page_size);

    if ($response->num_rows > 0) {
        $titulo = '';
        $delimitador = '';
        $fp = fopen($fi, 'w');
        $delimitador = ";";
        $titulo = [
            'PROFESOR',
            'FECHA',
            'HORA FICHAJE',
            'HORA SALIDA',
            'DIA SEMANA'
        ];

        fputcsv($fp, $titulo, $delimitador);

        for ($i = 0; $i <= $count; $i++) {
            $offset_var = $i * $page_size;

            $query = "SELECT ID_PROFESOR, Nombre, F_entrada, F_Salida, DIA_SEMANA, Fecha
            FROM (Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID)
            ORDER BY Profesores.Nombre ASC 
            LIMIT $page_size OFFSET $offset_var"; # "select id from shipment Limit ".$page_size." OFFSET ".$offset_var;

            $result =  $class->query($query);

            while ($datos = $result->fetch_assoc()) {
                $sep = preg_split('/[ -]/', $datos['Fecha']);
                $dia = $sep[2];
                $m = $sep[1];
                $Y = $sep[0];

                $campos = [
                    utf8_decode($datos['Nombre']),
                    "$dia/$m/$Y",
                    $datos['F_entrada'],
                    $datos['F_Salida'],
                    utf8_decode($datos['DIA_SEMANA'])
                ];

                // Escibimos una línea por cada $datos
                fputcsv($fp, $campos, $delimitador);
            }
        }
    } else {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}



$zip =  new ZipArchive();

$filename = 'Copia_Seguridad_' . $options['Centro'] . '.zip';

if ($zip->open($filename, ZIPARCHIVE::CREATE) === true) {

    is_file($pf) ? $zip->addFile($pf) : '';
    is_file($ho) ? $zip->addFile($ho) : '';
    is_file($ma) ? $zip->addFile($ma) : '';
    is_file($fi) ? $zip->addFile($fi) : '';
    $zip->close();

    if (is_file($filename)) {
        header("location: $ff$filename");
    } else {
        echo "<div style='width: 100%; height: 100vh; text-align: center;'>";
        echo "<div style='box-shadow: 4px 4px 16px 16px grey; width: 50%; margin-left: auto; margin-right: auto; border-radius: 10px;'>";
        echo "<h1 style='color: green; margin-top: 40vh; vartical-align: middle; padding: 25px;'>No hay datos para realizar copia.</h1>";
        echo "</div>";
        echo "</div>";
        echo "<script>setTimeout(function(){window.location.href = '$_SERVER[HTTP_REFERER]'}, 1500)</script>";
    }
} else {
    echo "<div style='width: 100%; height: 100vh; text-align: center;'>";
    echo "<div style='box-shadow: 4px 4px 16px 16px grey; width: 50%; margin-left: auto; margin-right: auto; border-radius: 10px;'>";
    echo "<h1 style='color: red; margin-top: 40vh; vartical-align: middle; padding: 25px;'>Error creando archivo $filename</h1>";
    echo "</div>";
    echo "</div>";
    echo "<script>setTimeout(function(){window.location.href = '$_SERVER[HTTP_REFERER]'}, 1500)</script>";
}

is_file($pf) ? unlink($pf) : '';
is_file($ho) ? unlink($ho) : '';
is_file($ma) ? unlink($ma) : '';
is_file($fi) ? unlink($fi) : '';
