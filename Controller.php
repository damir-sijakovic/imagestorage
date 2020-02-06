<?php
  
namespace DSimageStorage;
require __DIR__ . '/View.php';

class Controller extends View
{
    public function parseUrl($request)
    {    
        
        // GRAB FILES AND CHECK SIZE 
        if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') 
        {
            
            echo json_encode(['error' => 'Selected file is too large for upload. (2 MB is limit)']); 
            die();
        }    
        
        
        // POST REQUESTS
        if (isset($_POST) && count($_POST) > 0) 
        {            
            if (isset($request['login']))
            {       
                $response = $this->loginUser($request['login']);               
            }            
            else if (isset($request['register']))
            { 
                $response = $this->registerUser($request['register']);
            }
            
            else if (isset($request['logout']))
            {         
                $response = $this->logoutUser($request['logout']);   
            }
                
            else if (isset($request['changepassword']))
            { 
                $response = $this->changeUserPass($request['changepassword']);
            }
            else if (isset($request['deleteaccount']))
            { 
                $response = $this->deleteAccount($request['deleteaccount']);
            }
            else if (isset($request['imagenumber']))
            { 
                $response = $this->getImageNumber();
            }
            else if (isset($request['deleteimage']))
            { 
                $response = $this->deleteUserImage($request['deleteimage']);
            }
         
            if (isset($response['success']))
            {
                echo json_encode($response); 
                die();
            }                
            else if (isset($response['error']))
            {
                echo json_encode($response); 
                die();
            }
            else 
            {
                echo json_encode(['error' => 'Internal error.']); 
                die();
            }            
        }
        
        // GET ROUTES
        
        if (isset($_GET) && count($_GET) > 0) 
        {
            if (isset($request['dst']))
            {                   
                if ($request['dst'] == 'account'){
                    $this->viewAccountPage(); 
                    return;
                }                
                else if ($request['dst'] == 'imagelist'){
                    $this->viewImageListPage(); 
                    return;
                }                 
                else if ($request['dst'] == 'imagenumber'){
                    $this->viewImageNumberPage(); 
                    return;
                }     
                else {
                    $this->setError('Wrong url parameter.');
                    $this->viewErrorPage();    
                }
                
            }
            
            else {
                $this->setError('Wrong url parameter.');
                $this->viewErrorPage();    
            } 
            
        } 
    
        
        // UPLOAD
        
        if (isset($_FILES["imageupload"]) )
        {	
            $response = $this->uploadImage($_FILES["imageupload"]);
         
            if (isset($response['success']))
            {
                echo json_encode($response); 
                die();
            }                
            else if (isset($response['error']))
            {
                echo json_encode($response); 
                die();
            }
            else 
            {
                echo json_encode(['error' => 'Internal error.']); 
                die();
            }    
        }
        
        $this->viewAccountPage();  

    }

    
};
