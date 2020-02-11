<?php
  
namespace DSimageStorage;
require __DIR__ . '/Model.php';


class User extends Model
{
    public function changeUserPass($jsonData)
    {
        if (!$this->sessionHaveUser()){
            return ['error' => 'No user logged in.']; 
        }
                    
        $data = json_decode(urldecode(base64_decode($jsonData, TRUE)));  
        $sessionData = $this->getUserSessionData();
        $sqlData = $this->getUserData(intval($sessionData['id']));
         
        if (password_verify(strval($data->oldpass), $sqlData['password']))
        {
            $passwordHash = password_hash(strval($data->newpass), PASSWORD_BCRYPT, ['cost'=>12]);   
            $this->changePass(intval($sessionData['id']), $passwordHash);
            return ['success' => 'Password updated!']; 
        }
        
        return ['error' => "Enter correct current password."];
    }
    
    public function logoutUser($jsonData)
    {
        $data = json_decode(urldecode(base64_decode($jsonData, TRUE))); 
        $sessionData = $this->getUserSessionData();

        if ($data->id == $sessionData['id'] && $data->name == $sessionData['name']){    
            $this->removeUserSessionData();        
            return ['success' => "User logout."]; 

        }
        else {
            return ['error' => "Bad logout request."]; 
        }
    }


    public function deleteUserImage($jsonData)
    { 
        $data = json_decode(urldecode(base64_decode($jsonData, TRUE))); 

        if ($this->getUserSessionData()['id'] == $data->ownerid){
    
            $delDirMsg = $this->deleteDirImage($data->imageid);
            $delSqlMsg = $this->deleteImage(intval($data->imageid));
              
            if (isset($delDirMsg['success'])){                
                $this->setSessionConfig('project_images', $this->getNumberOfImages());                 
                return ['success' => "Image deleted."]; 
            }
            
        }
        else
        {
            return ['error' => "You cannot delete image that you don't own."];     
        }
        
        return ['error' => "Bad Ajax request."]; 
    }    
       
    public function deleteAccount($jsonData)
    { 
        $ajaxdata = json_decode(urldecode(base64_decode($jsonData, TRUE))); 
        $sqlData = $this->getUserData($ajaxdata->id);
        
        if (password_verify(strval($ajaxdata->pass), $sqlData['password']))
        {
            $this->removeUser($sqlData['name']);
            $this->deleteUserDirImages($sqlData['id']);
            $this->removeUserSessionData();
            
            return ['success' => 'Account deleted!']; 
        }
                
        return ['error' => "Wrong password."]; 
    }    
        
    public function loginUser($jsonData)
    { 
        $data = json_decode(urldecode(base64_decode($jsonData, TRUE))); 
        //consoleDump($data);
        $userId = $this->emailExists($data->email);
        
        if ($userId > 0)
        {         
            $sqlData = $this->getUserData($userId);
            $sqlData['numberOfImages'] = $this->getNumberOfImages();
            
            $password = false; 
            
            if (password_verify(strval($data->password), $sqlData['password']))
            {
                $password = true;
            }
            
            if ($password)
            {
                $this->setUserSessionData($sqlData);
                return ['success' => $this->getUserSessionData()];  
            }
            else
            {
                return ['error' => "Wrong password!"];
            }            
        }
        else 
        {
            return ['error' => "No user with '" . $data->email . "' email found!"]; 
        }
    }
    
    public function registerUser($jsonData)
    {
        $data = json_decode(urldecode(base64_decode($jsonData, TRUE)));  
   
        
        if ($this->userExists($data->username) > 0)
        {
            return ['error' => 'User name already exists!']; 
        }  

        if ($this->emailExists($data->email) > 0)
        {
            return ['error' => 'User with this email already exists!']; 
        }  
        
        $passwordHash = password_hash(strval($data->password), PASSWORD_BCRYPT, ['cost'=>12]);  
        $newUserId = $this->addUser($data->username, $passwordHash, $data->email);     
         
        if ($newUserId > 0)
        {
            return ['success' => $data->email ]; 
        } 
    }
    
    
    public function setUserSessionData($sqlData) //id, name, password, email, created 	
    {
       
        $this->setSessionConfig('user_id', $sqlData['id']);
        $this->setSessionConfig('user_name', $sqlData['name']);
        $this->setSessionConfig('user_email', $sqlData['email']);    
        $this->setSessionConfig('project_images', $sqlData['numberOfImages']);    
        
        $this->setSessionConfig($this->getConfigValue('pageString'), 1); 
        $this->setSessionConfig($this->getConfigValue('perPageString'), $this->getConfigValue('itemsPerPage')); 
       // $this->setSessionConfig('user_token', guidv4(openssl_random_pseudo_bytes(16)));     
    }
    
    public function removeUserSessionData() 
    {
        $this->removeSessionConfig('user_id');
        $this->removeSessionConfig('user_name');
        $this->removeSessionConfig('user_email');         
        $this->removeSessionConfig('project_images');
         
        $this->removeSessionConfig($this->getConfigValue('pageString')); 
        $this->removeSessionConfig($this->getConfigValue('perPageString')); 
                
      //  $this->removeSessionConfig('user_token');
    }
    
    public function getUserSessionData() 
    {
        if ($this->sessionHaveUser())
        {
            $id = $this->getSessionConfig('user_id');
            $name = $this->getSessionConfig('user_name');
            $email = $this->getSessionConfig('user_email'); 
            $images = $this->getSessionConfig('project_images'); 
            $page = $this->getSessionConfig($this->getConfigValue('pageString')); 
            $perPage = $this->getSessionConfig($this->getConfigValue('perPageString')); 
      
            //$token = $this->getSessionConfig('user_token');
           
            return ['id' => $id, 'name' => $name, 'email' => $email, 'images' => $images, 'page' => $page, 'perpage' => $perPage];
        }
        else 
        {
            return [];
        }
    }
        
    
    public function sessionHaveUser(){
        if ($this->isSessionKeySet('user_id')){
            $this->setSessionConfig('project_images', $this->getNumberOfImages());  
            return true;
        }
        return false;
    }
    
    
};
