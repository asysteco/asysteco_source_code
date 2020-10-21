<?php

class Asysteco
{

    public $fichar = 'Fichar';
    public $horarios = 'Horarios';
    public $profesores = 'Profesores';
    public $horas = 'Horas';
    public $perfiles = 'Perfiles';
    public $lectivos = 'Lectivos';
    public $diasemana = 'Diasemana';
    public $marcajes = 'Marcajes';
    public $mensajes = 'Mensajes';

    public $conex;
    public $ERR_ASYSTECO;
    public $MSG;


    function bdConex($host, $user, $pass, $db)
    {
        $this->conex = new mysqli($host, $user, $pass, $db);
        if (!$this->conex->connect_errno) {
            return $this->conex;
        } else {
            $this->ERR_ASYSTECO = "Fallo al conectar a MySQL: (" . $this->conex->connect_errno . ") " . $this->conex->connect_error;
            return false;
        }
    }

    function getConsulta($sql)
    {
        echo $sql;
    }

    function query($sql)
    {
        if (!$this->conex) {
            return false;
        }
        if ($response = $this->conex->query($sql)) {
            return $response;
        } else {
            $this->ERR_ASYSTECO = "ERR_CODE: " . $this->conex->errno . "<br>ERROR: " . $this->conex->error;
            return false;
        }
    }

    function isLogged($Titulo)
    {
        if ($_SESSION['logged'] === true && $_SESSION['LID'] === "$Titulo" && isset($_SESSION['Nombre']) && isset($_SESSION['Iniciales']) && $_SESSION['Nombre'] != '') {
            return true;
        } else {
            $this->ERR_ASYSTECO = "Debe iniciar sesión.";
            return false;
        }
    }

    function compruebaCambioPass()
    {
        $pass = $this->encryptPassword($_SESSION['Iniciales'] . '12345');
        if ($response = $this->query("SELECT ID FROM $this->profesores WHERE Password='$pass' AND ID='$_SESSION[ID]'")) {
            if ($response->num_rows == 0) {
                return true;
            } else {
                $this->ERR_ASYSTECO = "Debes cambiar la contraseña.";
                return false;
            }
        } else {
            $ERR_MSG = $this->ERR_ASYSTECO;
            return false;
        }
    }

    function Logout()
    {
        $_SESSION['logged'] = false;
        unset($_SESSION['LID']);
        unset($_SESSION['Nombre']);
        unset($_SESSION['Tipo']);
        session_destroy();
        session_abort();
        header("Refresh: 1; https://asysteco.com/Bezmiliana/index.php");
    }

    function validFormName($registername)
    {
        if (preg_match('/^[ a-zäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ.-]{6,60}$/i', $registername)) {
            return true;
        } else {
            $this->ERR_ASYSTECO = "Nombre no válido <br>";
            return false;
        }
    }

    function validFormDni($registerdni)
    {
        $registerdni = strtoupper($registerdni);

        if (preg_match('/(^[XYZ][0-9]{7}[A-Z]$)|(^[0-9]{8}[A-Z]$)/i', $registerdni)) {
            return true;
        } else {
            $this->ERR_ASYSTECO = "DNI no válido <br>";
            return false;
        }
    }

    function validFormDate($date)
    {
        if (preg_match('/^(0[1-9]|1[0-9]|2[0-9]|3[01])\/(0[1-9]|1[0-2])\/(19[0-9]{2}|20[0-9]{2})$/', $date)) {
            return true;
        } else {
            $this->ERR_ASYSTECO = "Formato de fecha no válido. 
            <br>
            Formato válido: dd/mm/AAAA";
            return false;
        }
    }

    function validFormSQLDate($date)
    {
        if (preg_match('/^(19[0-9]{2}|20[0-9]{2})-(0[1-9]|1[0-2])-(0[1-9]|1[0-9]|2[0-9]|3[01])$/', $date)) {
            return true;
        } else {
            $this->ERR_ASYSTECO = "Formato de fecha no válido. 
            <br>
            Formato válido: AAAA-mm-dd";
            return false;
        }
    }

    function validFormIni($registerini)
    {
        $registerini = strtoupper($registerini);

        if (preg_match('/^[A-Z]{2,5}$/i', $registerini)) {
            return true;
        } else {
            $this->ERR_ASYSTECO = "Iniciales no válidas <br>";
            return false;
        }
    }

    function encryptPassword($pass)
    {
        $pass = sha1($pass);
        return $pass;
    }

    function Login($username, $password, $Titulo)
    {
        if ($this->conex) {
            $password = $this->encryptPassword($password);
            if ($response = $this->query("SELECT ID FROM $this->profesores WHERE Iniciales='$username' AND Password='$password' AND Activo='1'")) {
                if ($response->num_rows == 1) {
                    if ($response = $this->query("SELECT $this->profesores.ID, $this->profesores.Nombre, $this->profesores.Iniciales, $this->perfiles.Tipo 
                                                    FROM $this->profesores INNER JOIN $this->perfiles ON $this->profesores.TIPO=$this->perfiles.ID 
                                                    WHERE Iniciales='$username' AND Password='$password'")) {
                        $fila = $response->fetch_assoc();

                        $_SESSION['logged'] = true;
                        $_SESSION['LID'] = $Titulo;
                        $_SESSION['Iniciales'] = $fila['Iniciales'];
                        $_SESSION['ID'] = $fila['ID'];
                        $_SESSION['Nombre'] = $fila['Nombre'];
                        $_SESSION['Perfil'] = $fila['Tipo'];
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    $this->ERR_ASYSTECO = "Usuario o contraseña no válidos.";
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function LoginAdminQR($id, $Titulo)
    {
        if ($this->conex) {
            if ($response = $this->query("SELECT Profesores.ID, Nombre, Iniciales, Perfiles.Tipo FROM Profesores INNER JOIN Perfiles ON Profesores.Tipo=Perfiles.ID WHERE Profesores.ID='$id' AND Activo = 1 AND Profesores.Tipo = 1")) {
                if ($response->num_rows == 1) {
                        $fila = $response->fetch_assoc();

                        $_SESSION['logged'] = true;
                        $_SESSION['LID'] = $Titulo;
                        $_SESSION['Iniciales'] = $fila['Iniciales'];
                        $_SESSION['ID'] = $fila['ID'];
                        $_SESSION['Nombre'] = $fila['Nombre'];
                        $_SESSION['Perfil'] = $fila['Tipo'];
                        return true;
                } else {
                    $this->ERR_ASYSTECO = "Código QR incorrecto, solo un administrador puede activar el lector.";
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function getDate($timestamp = null)
    {
        $fecha = getdate();

        if ($timestamp !== null) {
            $fecha = getdate($timestamp);
        }

        if ($fecha) {
            if ($fecha['weekday'] === 'Monday') {
                $fecha['weekday'] = "Lunes";
            } elseif ($fecha['weekday'] === 'Tuesday') {
                $fecha['weekday'] = "Martes";
            } elseif ($fecha['weekday'] === 'Wednesday') {
                $fecha['weekday'] = "Miercoles";
            } elseif ($fecha['weekday'] === 'Thursday') {
                $fecha['weekday'] = "Jueves";
            } elseif ($fecha['weekday'] === 'Friday') {
                $fecha['weekday'] = "Viernes";
            } elseif ($fecha['weekday'] === 'Saturday') {
                $fecha['weekday'] = "Sabado";
            } elseif ($fecha['weekday'] === 'Sunday') {
                $fecha['weekday'] = "Domingo";
            }

            if ($fecha['month'] === 'January') {
                $fecha['month'] = "Enero";
            } elseif ($fecha['month'] === 'February') {
                $fecha['month'] = "Febrero";
            } elseif ($fecha['month'] === 'March') {
                $fecha['month'] = "Marzo";
            } elseif ($fecha['month'] === 'April') {
                $fecha['month'] = "Abril";
            } elseif ($fecha['month'] === 'May') {
                $fecha['month'] = "Mayo";
            } elseif ($fecha['month'] === 'June') {
                $fecha['month'] = "Junio";
            } elseif ($fecha['month'] === 'July') {
                $fecha['month'] = "Julio";
            } elseif ($fecha['month'] === 'August') {
                $fecha['month'] = "Agosto";
            } elseif ($fecha['month'] === 'September') {
                $fecha['month'] = "Septiembre";
            } elseif ($fecha['month'] === 'October') {
                $fecha['month'] = "Octubre";
            } elseif ($fecha['month'] === 'November') {
                $fecha['month'] = "Noviembre";
            } elseif ($fecha['month'] === 'December') {
                $fecha['month'] = "Diciembre";
            }
            return $fecha;
        } else {
            $this->ERR_ASYSTECO = "Error al obtener fecha.";
            return false;
        }
    }

    function getLastIDFichaje()
    {
        $id = $_SESSION['ID'];
        $sql = "SELECT ID FROM $this->fichaje WHERE ID_PROFESOR='$id' ORDER BY ID DESC LIMIT 1";
        if ($lastID = $this->query($sql)) {
            $lastID = $lastID->fetch_assoc();
            return $lastID['ID'];
        } else {
            $this->ERR_ASYSTECO = "ERR_CODE: " . $this->conex->errno . "<br>ERROR: " . $this->conex->error;
            return false;
        }
    }

    function horarioTemporalAHorarioReal($date = null)
    {
        $time = "07:45:00"; // Hora límite para comprobar horarios
        $horaactual = date("H:i:s"); // Hora actual a comparar
        $fechaactual = date("Y-m-d");

        if (isset($date) && $date != null && $this->validFormSQLDate($date)) {
            $fechaactual = $date;
            $time = "23:55:00";
            unset($_SESSION['fecha']);
        }

        //$horaactual = "07:40:00"; 
        //$fechaactual = "2020-09-07";
        if (isset($_SESSION['fecha']) && $_SESSION['fecha'] == $fechaactual) {
            return true;
        } else {
            if (strtotime($horaactual) <= strtotime($time)) {
                if ($response = $this->query("SELECT DISTINCT ID_PROFESOR FROM T_horarios WHERE Fecha_incorpora = '$fechaactual'")) {
                    while ($fila = $response->fetch_assoc()) {
                        $id = $fila['ID_PROFESOR'];
                        if (!$this->query("DELETE FROM Horarios WHERE ID_PROFESOR = '$id'")) {
                            return false;
                        }
                        $this->marcajes($id, 'remove');
                        $insertHorario = "INSERT INTO Horarios (ID_PROFESOR, Dia, HORA_TIPO, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida) SELECT ID_PROFESOR, Dia, HORA_TIPO, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida
                        FROM T_horarios WHERE ID_PROFESOR='$id' AND Fecha_incorpora='$fechaactual'";

                        if (!$this->query($insertHorario)) {
                            return false;
                        }
                        $this->updateHoras($id);
                        $this->marcajes($id, 'add');
                    }
                    return $_SESSION['fecha'] = $fechaactual;
                } else {
                    return false;
                }
            } else {
                return $_SESSION['fecha'] = $fechaactual;
            }
        }
    }

    function getGuardias()
    {
        $dia = date('Y-m-d');
        $horasistema = date('H:i:s');

        // Línea de comprobación para que muestre todas las horas a partir de $horasistema el día $dia

        // $dia = '2020-9-23';
        // $horasistema = '11:30:00';

        $sql = "SELECT DISTINCT Profesores.Nombre, Horarios.Aula, Horarios.Grupo, Horarios.Edificio, Horarios.Hora, Horarios.Tipo
        FROM ((Marcajes INNER JOIN Horarios ON Marcajes.ID_PROFESOR=Horarios.ID_PROFESOR AND Marcajes.Dia=Horarios.Dia AND Marcajes.Hora=Horarios.Hora)
        INNER JOIN Profesores ON Horarios.ID_PROFESOR=Profesores.ID)
        INNER JOIN Horas ON Marcajes.Hora=Horas.Hora AND Marcajes.Tipo=Horas.Tipo
        WHERE NOT EXISTS (SELECT * FROM Fichar WHERE Fichar.ID_PROFESOR=Profesores.ID AND Fichar.Fecha='$dia') 
        AND Marcajes.Fecha='$dia'
        AND (Marcajes.Asiste=0 OR Marcajes.Asiste=2)
        AND Profesores.Activo=1
        AND Profesores.Sustituido=0
        AND Horas.Fin > '$horasistema'
        ORDER BY Marcajes.Hora ASC, Horarios.Edificio ASC, Profesores.Nombre ASC";

        if ($exec = $this->query($sql)) {
            if ($exec->num_rows > 0) {
                return $exec;
            } else {
                $this->MSG = "No hay Aulas sin Profesor.";
                return false;
            }
        } else {
            return false;
        }
    }

    function notificar($idprof, $msg)
    {
        $notificacion = "INSERT INTO Notificaciones (ID_PROFESOR, Modificacion) VALUES ('$idprof', '$msg')";
        $this->query($notificacion);
        return true;
    }

    function FicharWeb($activeFicharSalida = 0)
    {
        if ($this->conex) {
            if ($response = $this->query("SELECT ID, Activo FROM Profesores WHERE ID='$_GET[ID]' AND TIPO<>1")) {
                if ($response->num_rows == 1) {
                    $datosProfesor = $response->fetch_assoc();
                    $id = $datosProfesor['ID'];
                    $activo =  $datosProfesor['Activo'];
                } else {
                    $this->ERR_ASYSTECO = "<span id='noqr' style='color: white; font-weight: bolder; background-color: red;'><h3>Código QR incorrecto.</h3></span>";
                    return false;
                }
            } else {
                return false;
            }

                $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $dia = $this->getDate();
            $horaSalida = $this->getHoraSalida($id);

            if ($activo != 1) {
                $msg = "Ha intentado Fichar estando desactivado.";
                $this->notificar($id, $msg);
                $this->ERR_ASYSTECO = "<span id='noqr' style='color: black; font-weight: bolder; background-color: red;'><h3>Su usuario está desactivado.</h3></span>";
                return false;
            }

            $sql = "SELECT DISTINCT ID, F_Salida FROM Fichar WHERE Fecha='$fecha' AND ID_PROFESOR='$id'";
            if ($response = $this->query($sql)) {
                if ($response->num_rows == 0) {
                    $fichar = "INSERT INTO Fichar (ID_PROFESOR, F_entrada, F_Salida, DIA_SEMANA, Fecha) 
                                VALUES ($id, '$hora', '$horaSalida', '$dia[weekday]', '$fecha')";

                    $this->query($fichar);

                    $marcajes = "UPDATE Marcajes SET Asiste='1' WHERE Fecha='$fecha' AND ID_PROFESOR='$id'";

                    $this->query($marcajes);
                    return true;
                } else {
                    if ($activeFicharSalida != 1) {
                        $this->ERR_ASYSTECO = "<span id='noqr' style='color: black; font-weight: bolder; background-color: orange;'><h3>Ya has fichado hoy.</h3></span>";
                        return false;
                    } else {
                        $datosRegistro = $response->fetch_assoc();
                        if($datosRegistro == $horaSalida) {
                            $ficharSalida = "UPDATE Fichar SET F_Salida='$hora' WHERE ID='$datosRegistro[ID]'";
                            $this->query($ficharSalida);
                            $this->ERR_ASYSTECO = "<span id='okqr' style='color: white; font-weight: bolder; background-color: green;'><h3>Fichaje de salida correcto.</h3></span>";
                            return false;
                        } else {
                            $this->ERR_ASYSTECO = "<span id='noqr' style='color: black; font-weight: bolder; background-color: orange;'><h3>Ya has fichado salida hoy.</h3></span>";
                            return false;
                        }
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $profesor
     * @param $fecha
     * @return bool
     */
    function delHorarioTemporal($profesor, $fecha)
    {
        if (isset($profesor) && isset($fecha) && $profesor != '' && $this->validFormSQLDate($fecha)) {
            if ($this->query("DELETE FROM T_horarios WHERE ID_PROFESOR='$profesor' AND Fecha_incorpora='$fecha'")) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    function marcajes()
    {
        $compruebalectivos = "SELECT $this->lectivos.Fecha FROM $this->lectivos";
        $fechaactual = date('Y-m-d');

        if ($response = $this->query($compruebalectivos)) {
            if ($response->num_rows > 0) {
                if (func_num_args() == 0) {
                    $lectivos = "SELECT $this->lectivos.Fecha FROM $this->lectivos WHERE $this->lectivos.Festivo='no'";
                    $resp = $this->query($lectivos);

                    while ($lectivo = $resp->fetch_assoc()) {
                        $ejec = "INSERT INTO Marcajes (ID_PROFESOR, Fecha, Hora, Tipo, Dia, Asiste) SELECT DISTINCT ID_PROFESOR, '$lectivo[Fecha]' as Fecha, Hora, Tipo, Dia, 0
                        FROM Horarios INNER JOIN Diasemana ON Horarios.Dia=Diasemana.ID
                        WHERE Dia = WEEKDAY('$lectivo[Fecha]')+1";
                        $this->query($ejec);
                    }
                } elseif (func_num_args() == 2) {
                    $args = func_get_args();
                    $profesor = $args[0];
                    $subopt = $args[1];

                    if ($subopt == 'add') {
                        $lectivos = "SELECT $this->lectivos.Fecha FROM $this->lectivos WHERE $this->lectivos.Festivo='no' AND $this->lectivos.Fecha>='$fechaactual' ORDER BY Fecha";
                        $resp = $this->query($lectivos);

                        while ($lectivo = $resp->fetch_assoc()) {
                            if ($this->asistidoHoy($profesor)) {
                                $ejec = "INSERT INTO Marcajes (ID_PROFESOR, Fecha, Hora, Tipo, Dia, Asiste) SELECT DISTINCT ID_PROFESOR, '$lectivo[Fecha]' as Fecha, Hora, Tipo, Dia, 1
                                FROM Horarios INNER JOIN Diasemana ON Horarios.Dia=Diasemana.ID
                                WHERE ID_PROFESOR='$profesor' AND Dia=WEEKDAY('$lectivo[Fecha]')+1";
                            } else {
                                $ejec = "INSERT INTO Marcajes (ID_PROFESOR, Fecha, Hora, Tipo, Dia, Asiste) SELECT DISTINCT ID_PROFESOR, '$lectivo[Fecha]' as Fecha, Hora, Tipo, Dia, 0
                                FROM Horarios INNER JOIN Diasemana ON Horarios.Dia=Diasemana.ID
                                WHERE ID_PROFESOR='$profesor' AND Dia=WEEKDAY('$lectivo[Fecha]')+1";
                            }
                            $this->query($ejec);
                        }
                    } elseif ($subopt == 'remove') {
                        $lectivos = "SELECT $this->lectivos.Fecha FROM $this->lectivos WHERE $this->lectivos.Festivo='no' AND $this->lectivos.Fecha>='$fechaactual' ORDER BY Fecha";
                        $resp = $this->query($lectivos);
                        $lectivo = $resp->fetch_assoc();

                        $ejec = "DELETE FROM Marcajes WHERE ID_PROFESOR='$profesor' AND Fecha>='$lectivo[Fecha]'";
                        $this->query($ejec);
                    } else {
                        $this->ERR_ASYSTECO = "No se puede realizar esta acción.";
                        return false;
                    }
                } elseif (func_num_args() == 4) {
                    $args = func_get_args();
                    $profesor = $args[0];
                    $dia = $args[1];
                    $hora = $args[2];
                    $subopt = $args[3];

                    if ($subopt == 'add') {
                        $lectivos = "SELECT $this->lectivos.Fecha FROM $this->lectivos WHERE $this->lectivos.Festivo='no' AND $this->lectivos.Fecha >= '$fechaactual' ORDER BY Fecha";
                        $resp = $this->query($lectivos);

                        while ($lectivo = $resp->fetch_assoc()) {
                            if ($this->asistidoHoy($profesor) && $fechaactual == $lectivo['Fecha']) {
                                $ejec = "INSERT INTO Marcajes (ID_PROFESOR, Fecha, Hora, Tipo, Dia, Asiste) SELECT DISTINCT ID_PROFESOR, '$lectivo[Fecha]' as Fecha, Hora, Tipo, Dia, 1
                                FROM Horarios INNER JOIN Diasemana ON Horarios.Dia=Diasemana.ID
                                WHERE ID_PROFESOR='$profesor' AND Dia='$dia' AND $dia=WEEKDAY('$lectivo[Fecha]')+1 AND Hora='$hora'";
                            } else {
                                $ejec = "INSERT INTO Marcajes (ID_PROFESOR, Fecha, Hora, Tipo, Dia, Asiste) SELECT DISTINCT ID_PROFESOR, '$lectivo[Fecha]' as Fecha, Hora, Tipo, Dia, 0
                                FROM Horarios INNER JOIN Diasemana ON Horarios.Dia=Diasemana.ID
                                WHERE ID_PROFESOR='$profesor' AND Dia='$dia' AND $dia=WEEKDAY('$lectivo[Fecha]')+1 AND Hora='$hora'";
                            }
                            $this->query($ejec);
                        }
                    } elseif ($subopt == 'remove') {

                        $lectivos = "SELECT $this->lectivos.Fecha FROM $this->lectivos WHERE $this->lectivos.Festivo='no' AND $this->lectivos.Fecha >= '$fechaactual' ORDER BY Fecha";
                        $resp = $this->query($lectivos);
                        $lectivo = $resp->fetch_assoc();

                        $ejec = "DELETE FROM Marcajes WHERE ID_PROFESOR='$profesor' AND Dia='$dia' AND Hora='$hora' AND Fecha>='$lectivo[Fecha]'";
                        $this->query($ejec);
                    } else {
                        $this->ERR_ASYSTECO = "No se puede realizar esta acción.";
                        return false;
                    }
                } else {
                    $this->ERR_ASYSTECO = "Número de argumentos incorrecto.";
                    return false;
                }
            } else {
                $this->ERR_ASYSTECO = "Debe fijar las fechas lectivas.";
                return false;
            }

            // Si falla algún paso anterior, el próximo código no se ejecutará
            // Comenzamos a ejecutar query

        } else {
            return false;
        }
    }

    function asistidoHoy($idprofesor, $fecha = null)
    {
        if ($fecha == null) {
            $fecha = date('Y-m-d');
        } else {
            if (!$this->validFormSQLDate($fecha)) {
                return false;
            }
        }

        if ($response = $this->query("SELECT ID FROM $this->fichar WHERE ID_PROFESOR='$idprofesor' AND Fecha='$fecha'")) {
            if ($response->num_rows == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function updateHoras()
    {
        if (func_num_args() == 0) {
            if ($response = $this->query("SELECT DISTINCT ID_PROFESOR FROM $this->horarios")) {
                $id = [];
                while ($row = $response->fetch_assoc()) {
                    $id[] = $row['ID_PROFESOR'];
                }
                foreach ($id as $profe) {
                    // Por cada Día, comprobamos y actualizamos sus Hora_entrada y Hora_salida
                    for ($i = 1; $i <= 5; $i++) {
                        // Obtenemos su primera Hora
                        if ($res = $this->query("SELECT Hora, Tipo FROM $this->horarios WHERE ID_PROFESOR='$profe' AND Dia='$i' ORDER BY Hora ASC LIMIT 1")) {
                            if ($res->num_rows > 0) {
                                $primera = $res->fetch_assoc();
                                if (!$p = $this->query("SELECT Inicio FROM Horas WHERE Hora='$primera[Hora]' AND Tipo='$primera[Tipo]'")->fetch_assoc()) {
                                    return false;
                                }
                            } else {
                                continue;
                            }
                        } else {
                            return false;
                        }

                        // Obtenemos su ultima Hora
                        if ($res = $this->query("SELECT Hora, Tipo FROM $this->horarios WHERE ID_PROFESOR='$profe' AND Dia='$i' ORDER BY Hora DESC LIMIT 1")) {
                            if ($res->num_rows > 0) {
                                $ultima = $res->fetch_assoc();
                                if (!$u = $this->query("SELECT Fin FROM Horas WHERE Hora='$ultima[Hora]' AND Tipo='$ultima[Tipo]'")->fetch_assoc()) {
                                    return false;
                                }
                            } else {
                                continue;
                            }
                        } else {
                            return false;
                        }
                        // Modificamos Hora_entrada y Hora_salida de cada Horario
                        if (!$this->query("UPDATE $this->horarios SET Hora_entrada='$p[Inicio]', Hora_salida='$u[Fin]' WHERE ID_PROFESOR='$profe' AND Dia='$i'")) {
                            return false;
                        }
                    }
                }
            } else {
                return false;
            }
        } elseif (func_num_args() == 1) {
            foreach (func_get_args() as $arg) {
                if ($this->validFormDate($arg)) {
                    if ($response = $this->query("SELECT DISTINCT ID_PROFESOR FROM T_horarios")) {
                        $id = [];
                        while ($row = $response->fetch_assoc()) {
                            $id[] = $row['ID_PROFESOR'];
                        }
                        foreach ($id as $profe) {
                            // Por cada Día, comprobamos y actualizamos sus Hora_entrada y Hora_salida
                            for ($i = 1; $i <= 5; $i++) {
                                // Conseguimos su primera Hora
                                if ($res = $this->query("SELECT Hora, Tipo FROM T_horarios WHERE ID_PROFESOR='$profe' AND Dia='$i' ORDER BY Hora ASC LIMIT 1")) {
                                    if ($res->num_rows > 0) {
                                        $primera = $res->fetch_assoc();
                                        if (!$p = $this->query("SELECT Inicio FROM Horas WHERE Hora='$primera[Hora]' AND Tipo='$primera[Tipo]'")->fetch_assoc()) {
                                            return false;
                                        }
                                    } else {
                                        continue;
                                    }
                                } else {
                                    return false;
                                }

                                // Conseguimos su ultima Hora
                                if ($res = $this->query("SELECT Hora, Tipo FROM T_horarios WHERE ID_PROFESOR='$profe' AND Dia='$i' ORDER BY Hora DESC LIMIT 1")) {
                                    if ($res->num_rows > 0) {
                                        $ultima = $res->fetch_assoc();
                                        if (!$u = $this->query("SELECT Fin FROM Horas WHERE Hora='$ultima[Hora]' AND Tipo='$ultima[Tipo]'")->fetch_assoc()) {
                                            return false;
                                        }
                                    } else {
                                        continue;
                                    }
                                } else {
                                    return false;
                                }
                                return true;
                                // Modificamos Hora_entrada y Hora_salida de cada Horario
                                if (!$this->query("UPDATE T_horarios SET Hora_entrada='$p[Inicio]', Hora_salida='$u[Fin]' WHERE ID_PROFESOR='$profe' AND Dia='$i'")) {
                                    return false;
                                }
                            }
                        }
                    } else {
                        return false;
                    }
                } elseif (preg_match('/^[0-9]+$/', $arg)) {
                    unset($this->ERR_ASYSTECO);
                    if ($response = $this->query("SELECT DISTINCT ID_PROFESOR FROM $this->horarios WHERE ID_PROFESOR='$arg'")) {
                        $id = [];
                        while ($row = $response->fetch_assoc()) {
                            $id[] = $row['ID_PROFESOR'];
                        }
                        foreach ($id as $profe) {
                            // Por cada Día, comprobamos y actualizamos sus Hora_entrada y Hora_salida
                            for ($i = 1; $i <= 5; $i++) {
                                // Conseguimos su primera Hora
                                if ($res = $this->query("SELECT Hora, Tipo FROM $this->horarios WHERE ID_PROFESOR='$profe' AND Dia='$i' ORDER BY Hora ASC LIMIT 1")) {
                                    if ($res->num_rows > 0) {
                                        $primera = $res->fetch_assoc();
                                        if (!$p = $this->query("SELECT Inicio FROM Horas WHERE Hora='$primera[Hora]' AND Tipo='$primera[Tipo]'")->fetch_assoc()) {
                                            return false;
                                        }
                                    } else {
                                        continue;
                                    }
                                } else {
                                    return false;
                                }

                                // Conseguimos su ultima Hora
                                if ($res = $this->query("SELECT Hora, Tipo FROM $this->horarios WHERE ID_PROFESOR='$profe' AND Dia='$i' ORDER BY Hora DESC LIMIT 1")) {
                                    if ($res->num_rows > 0) {
                                        $ultima = $res->fetch_assoc();
                                        if (!$u = $this->query("SELECT Fin FROM Horas WHERE Hora='$ultima[Hora]' AND Tipo='$ultima[Tipo]'")->fetch_assoc()) {
                                            return false;
                                        }
                                    } else {
                                        continue;
                                    }
                                } else {
                                    return false;
                                }

                                // Modificamos Hora_entrada y Hora_salida de cada Horario
                                if (!$this->query("UPDATE $this->horarios SET Hora_entrada='$p[Inicio]', Hora_salida='$u[Fin]' WHERE ID_PROFESOR='$profe' AND Dia='$i'")) {
                                    return false;
                                }
                            }
                        }
                    } else {
                        return false;
                    }
                } else {
                    $this->ERR_ASYSTECO = "Argumento no válido.";
                    return false;
                }
            }
        } else {
            $this->ERR_ASYSTECO = "Número de argumentos incorrecto.";
            return false;
        }
    }

    function getHoraEntrada()
    {
        $dia = $this->getDate();
        if ($response = $this->query("SELECT $this->horarios.Hora_entrada FROM $this->horarios INNER JOIN $this->profesores ON $this->horarios.ID_PROFESOR=$this->profesores.ID WHERE $this->profesores.ID='$_SESSION[ID]' AND $this->profesores.Nombre='$_SESSION[Nombre]' AND $this->horarios.Dia='$dia[weekday]' LIMIT 1")) {
            if ($hora_entrada = $response->fetch_assoc()) {
                return $hora_entrada['Hora_entrada'];
            } else {
                $this->ERR_BD = "ERR_CODE: " . $this->conex->errno . "<br>ERROR: " . $this->conex->error;
                return false;
            }
        } else {
            return false;
        }
    }

    function getHoraSalida($id)
    {
        $datosHoy = $this->getDate();
        $diaSemana = $datosHoy['wday'];

        $sql = "SELECT Horarios.Hora_salida FROM Horarios INNER JOIN Profesores ON Horarios.ID_PROFESOR=Profesores.ID 
        WHERE Profesores.ID='$id' AND Horarios.Dia='$diaSemana'
        ORDER BY Hora_salida DESC
        LIMIT 1";
        $response = $this->query($sql);
        if ($horaSalida = $this->query($sql)->fetch_assoc()) {
            return $horaSalida['Hora_salida'];
        } else {
            return false;
        }
    }

    function searchDuplicateField($data, $field, $table)
    {
        if ($response = $this->query("SELECT $field FROM $table WHERE $field='$data'")) {
            if ($response->num_rows == 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function isTooLate($id, $horaactual, $diasemana)
    {
        if ($response = $this->query("SELECT DISTINCT $this->horarios.Hora_salida 
        FROM $this->horarios INNER JOIN Diasemana ON $this->horarios.Dia=$this->diasemana.ID WHERE ID_PROFESOR='$id' 
        AND $this->horarios.Hora_salida >= '$horaactual' AND $this->diasemana.Diasemana='$diasemana'")) {
            if ($response->num_rows == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function validRegisterProf()
    {
        if (!$this->validFormName($_POST['Nombre'])) {
            $this->ERR_ASYSTECO = "Formato de Nombre incorrecto.";
            return false;
        } elseif (!$this->validFormIni($_POST['Iniciales'])) {
            $this->ERR_ASYSTECO = "Formato de iniciales incorrecto.";
            return false;
        } else {
            if ($this->searchDuplicateField($_POST['Iniciales'], 'Iniciales', $this->profesores)) {
                $pass = $this->encryptPassword($_POST['Iniciales'] . '12345');
                if ($this->query("INSERT INTO $this->profesores (Nombre, Iniciales, Password, TIPO)
                VALUES ('$_POST[Nombre]', '$_POST[Iniciales]', '$pass', '2')")) {
                    return true;
                } else {
                    $this->ERR_ASYSTECO;
                    return false;
                }
            } else {
                $this->ERR_ASYSTECO = "No se pueden duplicar las iniciales.";
                return false;
            }
        }
    }

    function dateLoop($inicio, $fin)
    {
        while (strtotime($inicio) <= strtotime($fin)) {
            //Indicando la fecha
            $diasmes = $inicio;
            //Separa la fecha
            $sep = preg_split('/-/', $diasmes);
            $dia = $sep[2];
            $m = $sep[1];
            $Y = $sep[0];
            //Calcula los días que tiene el mes
            //Devuelve la fecha Unix en formato fecha juliana
            $start = unixtojd(mktime(0, 0, 0, $m, $dia, $Y));
            //Cambia la fecha juliana a un formato de calendario
            $array = cal_from_jd($start, CAL_GREGORIAN);
            if ($array['dayname'] == "Saturday" || $array['dayname'] == "Sunday") {
            } else {
                if (!$this->searchDuplicateField($diasmes, 'Fecha', $this->lectivos)) {
                    $this->query("UPDATE $this->lectivos SET $this->lectivos.Festivo='no' WHERE $this->lectivos.Fecha='$diasmes'");
                } else {
                    if ($this->query("INSERT INTO $this->lectivos (Fecha) VALUES ('$inicio')")) {
                    } else {
                        return false;
                    }
                }
            }
            $inicio = date("Y-m-d", strtotime("+1 day", strtotime($inicio)));
        }
        return true;
    }

    function updateDateLoop($inicio, $fin)
    {
        while (strtotime($inicio) <= strtotime($fin)) {
            $diasmes = $inicio;
            $sep = preg_split('/-/', $diasmes);
            $dia = $sep[2];
            $m = $sep[1];
            $Y = $sep[0];
            $start = unixtojd(mktime(0, 0, 0, $m, $dia, $Y));
            $array = cal_from_jd($start, CAL_GREGORIAN);
            if ($this->query("UPDATE $this->lectivos SET $this->lectivos.Festivo='si' WHERE $this->lectivos.Fecha='$inicio'")) {
            } else {
                return false;
            }
            $inicio = date("Y-m-d", strtotime("+1 day", strtotime($inicio)));
        }
    }
}
