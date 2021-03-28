<?php

$profesor = $_GET['profesor'] ?? '';
$date = $_GET['date'] ?? '';
$hour = $_GET['hour'] ?? '';

$success = false;
$alertMessage = 'Error inesperado...';
$data = '';

if (!empty($profesor) && !empty($date) && !empty($hour)) {
    $sql = "SELECT m.*, d.Diasemana, DATE_FORMAT(h.Inicio, '%H:%i') Inicio, DATE_FORMAT(h.Fin, '%H:%i') Fin
            FROM Marcajes m
                INNER JOIN Diasemana d ON m.Dia = d.ID
                INNER JOIN Horas h ON m.Hora = h.Hora AND m.Tipo = h.Tipo
            WHERE ID_PROFESOR = $profesor
                AND m.Fecha = '$date'
                AND m.Hora = '$hour'
            ORDER BY m.Fecha DESC, m.Dia, m.Hora";
    if ($row = $class->query($sql)->fetch_object()) {
        $fechaFormateada = $class->formatSQLDateToEuropeanDate($date);

        $success = true;
        $alertMessage = 'Hora obtenida correctamente.';
        
        if($row->Asiste == 1) {
            $colorMarcaje = 'asistido';
        } elseif($row->Asiste == 2) {
            $colorMarcaje = 'extraescolar';
        } elseif($row->Asiste == 0 && $row->Justificada == 1) {
            $colorMarcaje = 'faltado-justificado';
        } else {
            $colorMarcaje = 'faltado';
        }
        
        $data = "<tr id='fila_" . "$profesor" . "_" . "$date" . "_" . "$hour' class='$colorMarcaje'>";
        $data .= "<td class='d-none'>$fechaFormateada</td>";
        $data .= "<td>$row->Diasemana</td>";
        $data .= "<td>$row->Inicio</td>";

    if($row->Asiste == 1) {
        $data .= "<td>
            <a title='Haz clic aquí si ha faltado esta hora.'
            class='actualiza asiste marcaje'
            data-id='$profesor'
            data-date='$date'
            data-hour='$hour'
            data-startHour='$row->Inicio'
            data-endHour='$row->Fin'
            data-type='$row->Tipo'
            data-action='Asiste'
            data-value='0'>
                <span style='font-size: 25px; vertical-align: middle;' class='fa fa-check add_icon'></span>
            </a>
        </td>";
        $data .= "<td>
            <a title='Has clic aqui si tiene Actividad Extraescolar.'
            class='actualiza extra marcaje'
            data-id='$profesor'
            data-date='$date'
            data-hour='$hour'
            data-startHour='$row->Inicio'
            data-endHour='$row->Fin'
            data-type='$row->Tipo'
            data-action='Asiste'
            data-value='2'>
                <span style='font-size: 25px; vertical-align: middle;'  class='fa fa-square-o'></span>
            </a>
        </td>";
        $data .= "<td></td>";
    } elseif($row->Asiste == 2) {
        $data .= "<td>
            <a title='Haz clic aquí si ha faltado esta hora.'
            class='actualiza asiste marcaje'
            data-id='$profesor'
            data-date='$date'
            data-hour='$hour'
            data-startHour='$row->Inicio'
            data-endHour='$row->Fin'
            data-type='$row->Tipo'
            data-action='Asiste'
            data-value='0'>
                <span style='font-size: 25px; vertical-align: middle;' class='fa fa-check'></span>
            </a>
        </td>";
        $data .= "<td>
            <a title='Has clic aqui si no tiene Actividad Extraescolar.'
            class='actualiza extra marcaje'
            data-id='$profesor'
            data-date='$date'
            data-hour='$hour'
            data-startHour='$row->Inicio'
            data-endHour='$row->Fin'
            data-type='$row->Tipo'
            data-action='Asiste'
            data-value='1'>
                <span style='font-size: 25px; vertical-align: middle;' class='fa fa-check-square-o'></span>
            </a>
        </td>";
        $data .= "<td></td>";
    } else {
        if($_SESSION['Perfil'] == 'Admin') {
            $data .= "<td>
                <a title='Haz clic aquí si ha asistido esta hora.'
                class='actualiza asiste marcaje'
                data-id='$profesor'
                data-date='$date'
                data-hour='$hour'
                data-startHour='$row->Inicio'
                data-endHour='$row->Fin'
                data-type='$row->Tipo'
                data-action='Asiste'
                data-value='1'>
                    <span style='font-size: 25px; vertical-align: middle;' class='fa fa-times'></span>
                </a>
            </td>";
            $data .= "<td class='extrabox'></td>";
            if($row->Justificada == 1) {
                $data .= "<td>
                    <a title='Haz clic aquí para retirar justificación.'
                    class='actualiza justifica marcaje'
                    data-id='$profesor'
                    data-date='$date'
                    data-hour='$hour'
                    data-startHour='$row->Inicio'
                    data-endHour='$row->Fin'
                    data-type='$row->Tipo'
                    data-action='Justificada'
                    data-value='0'>
                        <span style='font-size: 25px; vertical-align: middle;' class='fa fa-check'></span>
                    </a>
                </td>";
            } else {
                $data .= "<td>
                    <a title='Haz clic aquí para justificar.'
                    class='actualiza justifica marcaje'
                    data-id='$profesor'
                    data-date='$date'
                    data-hour='$hour'
                    data-startHour='$row->Inicio'
                    data-endHour='$row->Fin'
                    data-type='$row->Tipo'
                    data-action='Justificada'
                    data-value='1'>
                        <span style='font-size: 25px; vertical-align: middle;' class='fa fa-times'></span>
                    </a>
                </td>";
            }
        } else {
            $data .= "<td>
                <span style='font-size: 25px; vertical-align: middle;' class='fa fa-times' title='Para marcar esta hora como asistida, contacte con Jefatura.'></span>
            </td>";
            $data .= "<td></td>";
            if($row->Justificada == 1) {
                $data .= "<td><span style='font-size: 25px; vertical-align: middle;' class='fa fa-check'></span></td>";
            } else {
                $data .= "<td><span style='font-size: 25px; vertical-align: middle;' class='fa fa-times' title='Contacte con jefatura para justificar.'></span></td>";
            }
        }
    }
    $data .= "</tr>";
    }
}

$result = [
    'success' => $success,
    'msg' => $alertMessage,
    'data' => $data
];

echo json_encode($result);
exit;
