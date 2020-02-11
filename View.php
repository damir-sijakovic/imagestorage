<?php
  
namespace DSimageStorage;
require __DIR__ . '/Image.php';

class View extends Image
{      
    public function parsePaginate($urlParam)
    {
        $paginationPage = $_GET[$this->getConfigValue('pageString')];   
        
        //if garbage in urlparams set to 1
        if (!ctype_digit($paginationPage)){ 
            $paginationPage = 1;
        }
        if (! (intval($paginationPage) > 0)){ 
            $paginationPage = 1;
        }    
        
            
        $this->setSessionConfig( $this->getConfigValue('pageString') , $paginationPage);      

        if (isset($_GET[$this->getConfigValue('perPageString')]))
        {   
            $paginationPerPage = $_GET[$this->getConfigValue('perPageString')];
            
            //if garbage in urlparams set to 10
            if (!ctype_digit($paginationPerPage)){ 
                $paginationPerPage = 10;
            }            
            if (! (intval($paginationPerPage) > 0)){ 
                $paginationPerPage = 10;
            }  
                 
            $this->setSessionConfig($this->getConfigValue('perPageString'), $paginationPerPage);  
        }
        else 
        {
            $this->setSessionConfig($this->getConfigValue('perPageString'), $this->getConfigValue('itemsPerPage'));
        }                        
        
        // if requested page is above last page, set to last page 
        $paginationPerPage = intval($this->getSessionConfig($this->getConfigValue('perPageString')));
        $imageNumber = intval($this->getSessionConfig('project_images'));
        if ($imageNumber > 0) 
        {
            $lastPage = ceil($imageNumber/$paginationPerPage); 
        }        
        if ($paginationPage > $lastPage){
            $paginationPage = $lastPage;
            $this->setSessionConfig( $this->getConfigValue('pageString') , $paginationPage);   
        } 
        
        return true;        
    }
    
    public function getPaginationData()
    {
        $imageNumber = intval( $this->getSessionConfig('project_images') );
        
        if ($imageNumber == 0)
        {
            return [];
        }
        
        //url
        $haveParams = false;
        $url = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
        $entryUrl = null;
        
        if (strpos($url, '?') !== false) 
        {
            $haveParams = true;        
            
            $res = explode('?', $url);
            $entryUrl = $res[0];
        }  
        else  
        {
            if (substr($url, -1) === '/')
            {
                $entryUrl = $url . 'index.php';
            }
        } 
        
        //basic page data
        $pageString = $this->getConfigValue('pageString');
        $perPageString = $this->getConfigValue('perPageString');
        
        $page = intval( $this->getSessionConfig($pageString) ); 
        $perPage = intval( $this->getSessionConfig($perPageString) );         
        $lastPage = ceil($imageNumber/$perPage);        
          
          
        //page group, numbers in pagination  
        $i=0;
        $j=0;
        for (; $j<$page; $i++)
        {
            $j += 3;
        }
             
        $lastInGrp = $j;
                
        $pagThreeNumb[0] = $lastInGrp-2;
        $pagThreeNumb[1] = $lastInGrp-1;
        $pagThreeNumb[2] = $lastInGrp;           
 
        for ($i=0; $i<count($pagThreeNumb); $i++)
        {
            if ($pagThreeNumb[$i] > $lastPage)
            {        
                $pagThreeNumb[$i] = -1;
            }
        }
      
        //return object
        $data = [
            //'first'   => $entryUrl . '?dst=imagelist&' . $pageString .'=1&' . $perPageString .'='. $perPage,
            'first'   => ($page == 1) ? null : $entryUrl . '?dst=imagelist&' . $pageString .'=1&' . $perPageString .'='. $perPage,
            'last'    => $entryUrl . '?dst=imagelist&' . $pageString .'='. $lastPage .'&' . $perPageString .'='. $perPage,
            'next'    => ($page >= $lastPage) ? null : $entryUrl . '?dst=imagelist&' . $pageString .'='. strval($page+1) .'&' . $perPageString .'='. $perPage,
            'current' => $entryUrl . '?dst=imagelist&' . $pageString .'='. $page . '&' . $perPageString .'='. $perPage, 
            'prev'    => ($page == 1) ? null : $entryUrl . '?dst=imagelist&' . $pageString .'='. strval($page-1) . '&' . $perPageString .'='. $perPage ,
            'page1'   => ($pagThreeNumb[0] == -1) ? null : $entryUrl . '?dst=imagelist&' . $pageString .'='. $pagThreeNumb[0] .'&' . $perPageString .'='. $perPage,
            'page2'   => ($pagThreeNumb[1] == -1) ? null : $entryUrl . '?dst=imagelist&' . $pageString .'='. $pagThreeNumb[1] .'&' . $perPageString .'='. $perPage,
            'page3'   => ($pagThreeNumb[2] == -1) ? null : $entryUrl . '?dst=imagelist&' . $pageString .'='. $pagThreeNumb[2] .'&' . $perPageString .'='. $perPage, 
            'group'   => $pagThreeNumb[0] .','. $pagThreeNumb[1] .','. $pagThreeNumb[2],         
        ];
        
        return $data;
    }
    
    
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



