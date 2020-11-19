<?php

if(isset($_GET['profesor']) && $_GET['profesor'] != '')
{
    $sql = "SELECT Horarios.*, Profesores.Nombre, Profesores.Iniciales, Diasemana.Diasemana, Aulas.Nombre as Aula, Cursos.Nombre as Grupo FROM
    Horarios INNER JOIN Profesores ON Horarios.ID_PROFESOR=Profesores.ID
    INNER JOIN Diasemana ON Diasemana.ID=Horarios.Dia 
    INNER JOIN Aulas ON Aulas.ID=Horarios.Aula 
    INNER JOIN Cursos ON Cursos.ID=Horarios.Grupo
    WHERE ID_PROFESOR = '$_GET[profesor]'
    ORDER BY ID_PROFESOR, Dia, Hora";
}
else
{
    $sql = "SELECT Horarios.*, Profesores.Nombre, Profesores.Iniciales, Diasemana.Diasemana, Aulas.Nombre as Aula, Cursos.Nombre as Grupo
    FROM Horarios INNER JOIN Profesores ON Horarios.ID_PROFESOR=Profesores.ID
    INNER JOIN Diasemana ON Diasemana.ID=Horarios.Dia
    INNER JOIN Aulas ON Aulas.ID=Horarios.Aula 
    INNER JOIN Cursos ON Cursos.ID=Horarios.Grupo
    ORDER BY ID_PROFESOR, Dia, Hora";
}
if($response = $class->query($sql))
{
    //echo "<h2>Registros de Horarios</h2>"; 
    if ($response->num_rows > 0) 
    {
        $ff = "tmp/";
        $fn = "Listado_Horarios.csv";
        chdir($ff);
        if(is_file($fn))
        {
            unlink($fn);
        }
        $fp = fopen($fn, 'w');
        $delimitador = ";";
        $titulo = [
            'GRUPO',
            'INICIALES',
            'AULA',
            'DIA',
            'HORA',
            'EDIFICIO'
        ];
        // Escribimos los títulos para los campos
        fputcsv($fp, $titulo, $delimitador);
        while($datos = $response->fetch_assoc()) 
        {
            if($nombre = $class->query("SELECT Nombre, Iniciales FROM Profesores WHERE ID='$datos[ID_PROFESOR]'"))
            {
                $prof = $nombre->fetch_assoc();
                $profesor = $prof['Nombre'];
                $iniciales = $prof['Iniciales'];
            }
            else
            {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
            $campos = [
                utf8_decode($datos['Grupo']),
                utf8_decode($iniciales),
                utf8_decode($datos['Aula']),
                $datos['Dia'],
                $datos['Hora'],
                $datos['Edificio'],
            ];
            
            // Escibimos una línea por cada $datos
            fputcsv($fp, $campos, $delimitador);
        }
            
        //cabeceras para descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fn . '";');
                    
        ob_end_clean();
            
        readfile($fn);
            
        if(is_file($fn))
        {
            unlink($fn);
        }
    } else {
        echo "<div style='width: 100%; height: 100vh; text-align: center;'>";
            echo "<div style='box-shadow: 4px 4px 16px 16px grey; width: 50%; margin-left: auto; margin-right: auto; border-radius: 10px;'>";
                echo "<h1 style='color: red; margin-top: 40vh; vartical-align: middle; padding: 25px;'>No existen horarios para exportar...</h1>";
            echo "</div>";
        echo "</div>";
        echo "<script>setTimeout(function(){window.close()}, 1500)</script>";
    }
    exit;
}