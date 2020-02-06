<?php
  
namespace DSimageStorage;
require __DIR__ . '/Core.php';

class DbConnection extends Core
{
    public function initGetPdo()
    {
        if (isset(self::$pdo))
        {            
            return self::$pdo;
        }
        else {        
            try 
            {
                $dsn = 'mysql:host=' . $this->config['dbHost'] . ';port=' . $this->config['dbPort'] . ';dbname=' . $this->config['dbName'];
                self::$pdo = new \PDO($dsn, $this->config['dbUserName'], $this->config['dbPass']);
                self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
                self::$pdo->exec('set session sql_mode = traditional');
                self::$pdo->exec('set session innodb_strict_mode = on');
                   
                return self::$pdo;
            }
            catch(\PDOException $e)
            {
                    $this->setError("SQL Connection Error: " . $e->getMessage());
                    
                    return null;
            }
        }
    }
    
};
