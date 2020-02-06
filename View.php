<?php
  
namespace DSimageStorage;
require __DIR__ . '/Image.php';

class View extends Image
{      
    public function viewErrorPage()
    {        
        $this->compilePage('error');
    }
    
    public function viewHomePage()
    {        
        $this->compilePage('home');
    }
    
    public function viewAccountPage()
    {        
        $this->compilePage('account');
    }
    
    public function viewImageListPage()
    {        
        $this->compilePage('imagelist');
    }
    
    public function viewImageNumberPage()
    {        
        $this->compilePage('imagenumber');
    }
       
    public function compilePage($pageName)
    {        
        ob_start();
        require __DIR__ . '/template/'. $pageName .'.php';  
        $page = ob_get_contents();
        ob_end_clean(); 
        return $page; 
    }

        
    public function passViewData($key, $value)
    {        
        $jsonArg = json_encode($value);
        $jsObject = <<<END_JSOBJECT
        <script>
            var $key = JSON.parse('$jsonArg');
        </script>        
        END_JSOBJECT;

        return $jsObject;    
    }
    
};
