<?php
  
namespace DSimageStorage;
require __DIR__ . '/Controller.php';

class App extends Controller
{
    // STARTUP SETUP & TESTING
    //////////////////////////
    
    public function __construct()
    {        
        $this->resetErrorMsg();
        $this->initGetPdo();  
                
        
        if ($this->haveError()){
            $this->viewErrorPage();
        } 
    }
};
