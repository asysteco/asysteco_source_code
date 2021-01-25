<?php

class Mysql
{
    private const DEFAULT_CONEX_ERROR = 'Error al conectar con el servicio, inténtelo más tarde o contacte con los administradores.';
    private const DEFAULT_SQL_ERROR = 'Ha ocurrido un error inesperado, pruebe más tarde o contacte con los administradores.';
    private const ADMIN_MODE = 'Testing';
    private const COOKIE_DEBUG_VALUE = 1;

    private $conex;
    private array $arrayResult;
    private object $objectResult;
    private string $errorMessage;

    private string $sessionLID;
    private int $cookieDebug;

    public function bdConex($host, $user, $pass, $db): bool
    {
        $this->sessionLID = $_SESSION['LID'];
        $this->cookieDebug = (int)$_COOKIE['debug'];

        if (!$this->conex = new mysqli($host, $user, $pass, $db)) {
            error_log("CONEX-ERROR-" . $this->sessionLID . ": NUMBER: " . $this->conex()->connect_errno . " MESSAGE: " . $this->conex()->connect_error);

            if($this->sessionLID === self::ADMIN_MODE || $this->cookieDebug === self::COOKIE_DEBUG_VALUE) {
                $this->errorMessage = "CONEX-ERROR-" . $this->sessionLID . ": NUMBER: " . $this->conex()->connect_errno . " MESSAGE: " . $this->conex()->connect_error;
            } else {
                $this->errorMessage = self::DEFAULT_CONEX_ERROR;
            }
            return false;
        }

        return true;
    }

    public function conex(): mysqli
    {
        return $this->conex;
    }

    public function errorNumber(): string
    {
        return $this->conex->errno;
    }

    public function error(): string
    {
        return $this->conex->error;
    }

    public function lastError(): string
    {
        if($this->sessionLID === self::ADMIN_MODE || $this->cookieDebug === self::COOKIE_DEBUG_VALUE) {
            return $this->errorMessage;
        } 

        return self::DEFAULT_SQL_ERROR;
    }

    public function query(string $sql): ?array
    {
        if (!$queryResult = $this->conex()->query($sql)) {
            error_log("ERR_CODE: " . $this->errorNumber() .
                "\nERROR-" . $this->sessionLID . ": " . $this->error() .
                "\nSQL: " . $sql, './../error.log');

            $this->errorMessage = self::DEFAULT_SQL_ERROR;

            return false;
        }

        return $queryResult ?? null;
    }
}