<?php
  
namespace DSimageStorage;
require __DIR__ . '/Controller.php';

class App extends Controller
{
    // STARTUP SETUP
    /////////////////
    
    public function __construct()
    {        
        $this->resetErrorMsg();
        $this->initGetPdo();  
        //$this->setSessionConfig('numberOfImages', $this->getNumberOfImages());
        if ($this->haveError()){
            $this->viewErrorPage();
            
        }
        
        //$this->imageAlreadyUploaded('99fab143ef9758a9e8222ce60b0efc46');
    }
};
