<?php
  
namespace DSimageStorage;
require __DIR__ . '/DbConnection.php';

class Model extends DbConnection
{
    

    public function userExists($name)
    { 
        if (!is_string($name)) consoleLog('userExists(): argument value must be string.');
        $pdo = self::$pdo;
        
        
        $data = [
            'name' => $name,        
        ];
        $sql = "SELECT `id` FROM `users` WHERE `name` = :name";
        $stmt = $pdo->prepare($sql);  
        
        try 
        {  
            $stmt->execute($data);
        } 
        catch (\PDOException $e)
        {
            consoleLog('userExists() SQL error: ' . $e);          
            return null;
        } 
        
        if ($stmt->rowCount() > 0)
        {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return intval($row['id']);
        }
        
        return -1;
    }
    
    public function getUserData($id)
    { 
        if (!is_int($id)) consoleLog('getUserData(): argument value must be integer.');
        $pdo = self::$pdo;
        
        
        $data = [
            'id' => $id,        
        ];
        $sql = "SELECT  `id`, `name`, `password`, `email`, UNIX_TIMESTAMP(`created`) as `created` FROM `users` WHERE `id` = :id";
        $stmt = $pdo->prepare($sql);  
        
        try 
        {  
            $stmt->execute($data);
        } 
        catch (\PDOException $e)
        {
            consoleLog('getUserData() SQL error: ' . $e);
            return null;
        } 
        
        if ($stmt->rowCount() > 0)
        {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row;
        }
        
        return [];
    }

    public function emailExists($email)
    { 
        if (!is_string($email)) consoleLog('emailExists(): argument value must be string.');
        $pdo = self::$pdo;
        
        
        $data = [
            'email' => $email,        
        ];
        $sql = "SELECT `id` FROM `users` WHERE `email` = :email";
        $stmt = $pdo->prepare($sql);  
        
        try 
        {  
            $stmt->execute($data);
        } 
        catch (\PDOException $e)
        {
            consoleLog('emailExists() SQL error: ');
            return null;
        } 
        
        if ($stmt->rowCount() > 0)
        {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return intval($row['id']);
        }
        
        return -1;
    }



    public function addUser($name, $password, $email)
    { 
        if (!is_string($name)) consoleLog('addUser(): argument value must be string.');
        if (!is_string($email)) consoleLog('addUser(): argument value must be string.');
        if (!is_string($password)) consoleLog('addUser(): argument value must be string.');
        $pdo = self::$pdo;
                
        $data = [
            'name' => $name,        
            'email' => $email,        
            'password' => $password,        
        ];
        $sql = "INSERT INTO `users` (`name`, `email`, `password`) VALUES (:name, :email, :password)";
        $stmt = $pdo->prepare($sql);          
        try 
        {  
            $stmt->execute($data);
        } 
        catch (\PDOException $e)
        {
            consoleLog('addUser() SQL error: ' . $e);
            return -1;
        } 
        
        if (stripos($sql, 'insert ') === 0) $insert_id = $pdo->lastInsertId();
        $insert_id = (stripos($sql, 'insert ') === 0) ? $pdo->lastInsertId() : -1;
        return $insert_id;

    }

    public function changePass($id, $newpassword)
    { 
        if (!is_int($id)) consoleLog('changePass(): argument value must be integer.');
        if (!is_string($newpassword)) consoleLog('changePass(): argument value must be string.');
        $pdo = self::$pdo;
                
        $data = [
            'id' => $id,  
            'newpassword' => $newpassword,        
        ];
        $sql = "UPDATE `users` SET `password` = :newpassword WHERE id = :id";
        $stmt = $pdo->prepare($sql);          
        try 
        {  
            $stmt->execute($data);
        } 
        catch (\PDOException $e)
        {
           consoleLog('changePass() SQL error: ' . $e );
            return -1;
        } 
        
        return $id;
    }

     public function removeUser($name)
     { 
        if (!is_string($name)) consoleLog('removeUser(): argument value must be string.');
        $pdo = self::$pdo;
                
        $data = [
            'name' => $name,        
        ];
        $sql = "DELETE FROM `users` WHERE `name` = :name";
        $stmt = $pdo->prepare($sql);  
        
        try 
        {  
            $stmt->execute($data);
        } 
        catch (\PDOException $e)
        {
           consoleLog('removeUser() SQL error: ' . $e );
            return null;
        } 
        
        if ($stmt->rowCount() > 0)
        {
            return true;
        }
        return false;
        
    }
    

    public function addImage($filename, $type, $userid, $size )
    { 
        if (!is_string($filename)) consoleLog('addImage(): argument filename must be string.');
        if (!is_string($type)) consoleLog('addImage(): argument type must be string.');
        if (!is_int($userid)) consoleLog('addImage(): argument userid must be integer.');
        if (!is_int($size)) consoleLog('addImage(): argument size must be integer.');
        
        $pdo = self::$pdo;
                
        $data = [
            'filename' => $filename,        
            'type' => $type,   
            'userid' => $userid,        
            'size' => $size,        
        ];
        $sql = "INSERT INTO `images` (`filename`, `type`,  `user_id`, `size`) VALUES (:filename, :type, :userid, :size)";
        $stmt = $pdo->prepare($sql);          
        try 
        {  
            $stmt->execute($data);
        } 
        catch (\PDOException $e)
        {
           consoleLog('addImage() SQL error: ' . $e );
            return -1;
        } 
        
        if (stripos($sql, 'insert ') === 0) $insert_id = $pdo->lastInsertId();
        $insert_id = (stripos($sql, 'insert ') === 0) ? $pdo->lastInsertId() : -1;
        return $insert_id;
    }
    

    public function deleteImage($id)
    { 
        if (!is_int($id)) consoleLog('deleteImage(): argument value must be integer.');
        $pdo = self::$pdo;
                
        $data = [
            'id' => $id,        
        ];
        $sql = "DELETE FROM `images` WHERE `id` = :id";
        $stmt = $pdo->prepare($sql);  
        
        try 
        {  
            $stmt->execute($data);
        } 
        catch (\PDOException $e)
        {
            consoleLog('deleteImage() SQL error: ' . $e );
            return null;
        } 
        
        if ($stmt->rowCount() > 0)
        {
            return true;
        }
        return false;
        
    }
    
    public function getNumberOfImages()
    {
        $pdo = self::$pdo;

        $sql = "SELECT count(*) as count FROM `images`";
        
        try 
        {  
            $result = $pdo->query($sql);
        } 
        catch (\PDOException $e)
        {
           consoleLog('getNumberOfImages() SQL error: ' . $e );
            return null;
        } 
        
        if ($result->rowCount() > 0)
        {
            return $result->fetchColumn(); 
        }
        return -1;
    }
    
    public function getImageData()
    {
        $pdo = self::$pdo;

        $sql = "SELECT `id` AS `imageid`, `filename`, `type`, `size`, `user_id` AS `userid`, 
        (SELECT `name` FROM `users` WHERE `id` = `user_id`) AS `username`,
        (SELECT `email` FROM `users` WHERE `id` = `user_id`) AS `email`,
        CONCAT(`user_id`, '_', `id`, '.', `type`) AS `newname`
        FROM `images`";
        try 
        {  
            $result = $pdo->query($sql);
        } 
        catch (\PDOException $e)
        {
            consoleLog('getImageData() SQL error: ' . $e );
            return null;
        } 
        

        if ($result->rowCount() > 0)
        {
            $row = $result->fetchAll(\PDO::FETCH_ASSOC);
            return $row;
        }

        
        return [];
    }
    
    
};







