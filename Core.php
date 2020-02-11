<?php
  
namespace DSimageStorage;
require __DIR__ . '/Config.php';



function consoleDump($arg)
{
    error_log(print_r($arg, true));       
}

function consoleDumpSession()
{
    error_log(print_r($_SESSION, true));       
}


function consoleLog($arg)
{
    error_log($arg);       
}

function consoleBool($arg)
{
    ($arg) ? error_log('consoleBool: true') : error_log('consoleBool: false');       
}

function stopScript($arg = null)
{       
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    
    $nomsg = '<stopScript> '. basename($bt[0]['file'])  .'/'. $bt[0]['line'] . ':'. $bt[0]['function'] .'()';
    $msg = '<stopScript> '. basename($bt[0]['file'])  .'/'. $bt[0]['line'] . ':'. $bt[0]['function'] .'() => ' . $arg;
    
    error_log( ($arg) ? $msg : $nomsg );  
    die();    
}



class Core extends Config
{
    // SQL CONNECTION OBJECT
    static $pdo; 
    
    // CONFIG    
    public function getConfigValue($key)
    {
        return $this->config[$key];
    }
    
    // SESSION
    public function isSessionKeySet($key)
    {
        return isset($_SESSION[$this->projectCodeName] [$key]);
    }
    
    public function getSessionConfig($key)
    {
        return $_SESSION[$this->projectCodeName] [$key];
    }
    
    public function removeSessionConfig($key)
    {
        unset($_SESSION[$this->projectCodeName] [$key]);
    }
    
    public function setSessionConfig($key, $value)
    {    
        return $_SESSION[$this->projectCodeName] [$key] = $value;
    }
    
    public function setError($value)
    {    
        return $_SESSION[$this->projectCodeName] ['errorMsg'] = $value;
    }
    
    public function getError()
    {    
        return $_SESSION[$this->projectCodeName] ['errorMsg'];
    }
    
    public function haveError()
    {    
        if (isset( $_SESSION[$this->projectCodeName] ['errorMsg']))
        {
            return true;
        }
        return false;
    }
    
    public function resetErrorMsg()
    {    
        unset ($_SESSION[$this->projectCodeName]['errorMsg']);
    }
        
    public function clearSession(){
        unset($_SESSION[$this->projectCodeName]);
    }
    
    
};
