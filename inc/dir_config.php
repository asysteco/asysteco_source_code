<?php

// Configuración de variables de entorno de trabajo


$basedir = dirname($_SERVER['DOCUMENT_ROOT']);
$Instituto = preg_split('/\//', $_SERVER['REQUEST_URI']);
$subdir = '/' . $Instituto[1];
preg_match('/^\/[A-Z]+$/i', $subdir) ? $subdir = $subdir : $subdir = '' ;
$dirs = [
    'public' => '',
    'inc' => $basedir . $subdir . '/inc/',
    'class' => $basedir . $subdir . '/class/',
];

$act_home = '';
$act_horario = '';
$act_profesores = '';
$act_cal_escolar = '';
$act_usuario = '';