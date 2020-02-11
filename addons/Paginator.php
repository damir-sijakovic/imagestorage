<?php

//DAMIR ŠIJAKOVIĆ, MIT Licence (c) 2019



function ds_paginate($itemsArr, $settingsArr)
{    
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
    
    if ($haveParams)
    {
        $urlData = explode('?', $url);

        if (strpos($urlData[1], '&') !== false)
        {
            $arr = explode('&', $urlData[1]);

            foreach ($arr as $k=>$v){
                if (strncmp($v, $settingsArr['pageStr'] .'=', strlen($settingsArr['pageStr'])+1) === 0){
                    unset($arr[$k]);
                }
                else if (strncmp($v, $settingsArr['perPageStr'] .'=', strlen($settingsArr['perPageStr'])+1) === 0){
                    unset($arr[$k]);    
                }
            }

            $urlParams = implode('&', $arr);     
     
        }
        else 
        {
            $urlParams = $urlData[1];
        }
    }
    
    $newUrlParams = $entryUrl .'?'. $urlParams .'&'. $settingsArr['pageStr'] .'='. $settingsArr['currentPage'] .'&'. $settingsArr['perPageStr'] .'='. $settingsArr['itemsPerPage'];
    
    error_log('urlParams: ' . $urlParams);  
    error_log('url: ' . $url);
    error_log('entryUrl: ' . $entryUrl);
    error_log('newUrlParams: ' . $newUrlParams);
    error_log('------------------------------------------------------------');
    
    /*
   //error_log(print_r( array_chunk($itemsArr, $settingsArr['itemsPerPage'], true) ));
    error_log(print_r(  
        array_chunk($itemsArr, $settingsArr['itemsPerPage'], true)
    ,true));
    //$retObj->pageItems = array_values($this->pageData[intval($this->currentPage) -1]);
    */
    
    
    $splitPages = array_chunk($itemsArr, $settingsArr['itemsPerPage'], true);
    $pageItems = array_values($splitPages[intval($settingsArr['currentPage']) -1]);
    
    error_log(print_r(  
        $pageItems 
    ,true));
    
    error_log(print_r(  
        $itemsArr 
    ,true));
    
}


/*

class Paginator 
{
   
    private $url;
    private $cleanUrl;
    private $urlPageString; 
    private $urlPerPageString; 
    private $urlWithoutParams; 
    private $items;
    private $itemsPerPage;
    private $numberOfItems;        
    private $pageData;
    private $numberOfPages; 
    private $currentPage; 
   
    
    private $url;
    

    
    
    function getCleanUrl()
    {    
        $url = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
        
        if (strpos($url, '?') !== false) 
        {
            $res = explode('?', $url);
            return $res[0];
        }  
        else  
        {
            if (substr($url, -1) === '/')
            {
                return $url . 'index.php';
            }
            
            return $url;
        }
    }

    
    public function paginate()
    {
        $url = self::getCleanUrl();  
        //error_log($url);
        
        
        
        
    }
    
};

*/


/*
 
$itemsArr = [12,54,33,11,77,44,88,46,24,76,55];
$p = new Paginator();
$pagedata = $p->getPageObj($itemsArr, ['currentPage'=>1, 'itemsPerPage'=>3, 'pageStr'=>"page", 'perPageStr'=>"per_page"]);

*/
