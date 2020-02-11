<?php

//DAMIR ŠIJAKOVIĆ, MIT Licence (c) 2019

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
    
    function getPrevUrl()
    { 
        if ($this->currentPage == 1) return null;  
        $and = '&';         
        if (substr($this->cleanUrl, -1) == '?'){
            $and = '';
        }

        $page = $this->currentPage-1;
        return $this->cleanUrl . $and . $this->urlPageString . '=' . $page . '&' . $this->urlPerPageString . '=' . $this->itemsPerPage;
    }
    
    function getNextUrl()
    { 
        if ($this->currentPage == $this->numberOfPages){
            return null;          
        }
        
        $and = '&';         
        if (substr($this->cleanUrl, -1) == '?'){
            $and = '';
        }

        $page = $this->currentPage+1;
        return $this->cleanUrl . $and . $this->urlPageString . '=' . $page . '&' . $this->urlPerPageString . '=' . $this->itemsPerPage;
    }
    
    function getFirstUrl()
    { 
        $and = '&';         
        if (substr($this->cleanUrl, -1) == '?'){
            $and = '';
        }
        
        $page = 1;
        return $this->cleanUrl . $and . $this->urlPageString . '=' . $page . '&' . $this->urlPerPageString . '=' . $this->itemsPerPage;
    }    
    
    function getLastUrl()
    { 
        $and = '&';         
        if (substr($this->cleanUrl, -1) == '?'){
            $and = '';
        }
        
        $page = $this->numberOfPages;
        return $this->cleanUrl . $and . $this->urlPageString . '=' . $page . '&' . $this->urlPerPageString . '=' . $this->itemsPerPage;
    }    
    
    function getCurrentUrl()
    { 
        $and = '&';         
        if (substr($this->cleanUrl, -1) == '?'){
            $and = '';
        }
        
        $page = $this->currentPage;
        return $this->cleanUrl . $and . $this->urlPageString . '=' . $page . '&' . $this->urlPerPageString . '=' . $this->itemsPerPage;
    }    
    
    
    function getCurrentPageNumberFromUrl()
    { 
        $retval;
        $url = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];  
        $parsed = parse_url($url);
        $query = $parsed['query'];        
        parse_str($query, $params);
        $retval = $params[$this->urlPageString];   
        if (isset($retval)) return $retval;
        return -1;
    }
       
    //["currentPage"=>1, "itemsPerPage"=>3, "pageStr"=>"page", "perPageStr"=>"per_page"]
    public function getPageObj($itemsArr, $settingsArr)
    {
        //check args        
        if (!isset($itemsArr)) trigger_error('Paginator:getPageObj(): missing argument itemsArr or is null.' , E_USER_NOTICE);
        if (!isset($settingsArr)) trigger_error('Paginator:getPageObj(): missing argument settingsArr or is null.' , E_USER_NOTICE);        
        if (!is_array($itemsArr)) trigger_error('Paginator:getPageObj(): argument itemsArr is not array.' , E_USER_NOTICE);
        if (!is_array($settingsArr)) trigger_error('Paginator:getPageObj(): argument settingsArr is not array. Must be in format: ["currentPage"=>1, "itemsPerPage"=>3, "pageStr"=>"page", "perPageStr"=>"per_page"]' , E_USER_NOTICE);
        
        
        //check $settingsArr keys
        $okKeys = 0;
        foreach ($settingsArr as $key => $value)
        {
          if ($key == 'currentPage' || $key == 'itemsPerPage' || $key == 'pageStr' || $key == 'perPageStr')
          {
               $okKeys++;            
          } 
        }

        if ($okKeys != 4)
        {
            trigger_error('Paginator:__construct(): argument settingsArr is wrong. Must be in format: ["currentPage"=>1, "itemsPerPage"=>3, "pageStr"=>"page", "perPageStr"=>"per_page"]' , E_USER_NOTICE);
        }        
        
        //create url data       
        $this->url = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];  
        $this->urlWithoutParams = $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'], 2)[0];          
        
        $this->urlPageString = $settingsArr['pageStr'];
        $this->urlPerPageString = $settingsArr['perPageStr'];
        $this->itemsPerPage = $settingsArr['itemsPerPage'];
        $this->currentPage = $settingsArr['currentPage'];
        $this->items = $itemsArr;
        
        $urlData = explode('?', $this->url);
        //$this->cleanUrl = $urlData[1];
        
        if (strpos($urlData[1], '&') !== false){
            $arr = explode('&', $urlData[1]);

            foreach ($arr as $k=>$v){
                if (strncmp($v, $this->urlPageString .'=', strlen($this->urlPageString)+1) === 0){
                    unset($arr[$k]);
                }
                else if (strncmp($v, $this->urlPerPageString .'=', strlen($this->urlPerPageString)+1) === 0){
                    unset($arr[$k]);    
                }
            }

            $this->cleanUrl = implode('&', $arr);            
        }
        else {
            $this->cleanUrl = $urlData[1];
        }
            
            
        /*
        //create cleanUrl        
        $parsed = parse_url($this->url);        
        $query = $parsed['query'];
        parse_str($query, $params);
        unset($params[$this->urlPageString]);
        unset($params[$this->urlPerPageString]);     
        $onlyParams = http_build_query($params);
        $this->cleanUrl = $this->urlWithoutParams . '?' . $onlyParams;        
        */
        
        
        
        $this->numberOfItems = count($this->items);        
        $this->pageData = array_chunk($this->items, $this->itemsPerPage, true);
        $this->numberOfPages = count($this->pageData); 

        //make return obj
        $retObj = new StdClass;
        $retObj->next = $this->getNextUrl();
        $retObj->first = $this->getFirstUrl();
        $retObj->last = $this->getLastUrl(); 
        $retObj->self = $this->getCurrentUrl();
        $retObj->pageItems = array_values($this->pageData[intval($this->currentPage) -1]);
        return $retObj;
    } 
    
};


$itemsArr = [12,54,33,11,77,44,88,46,24,76,55];
$p = new Paginator();
$pagedata = $p->getPageObj($itemsArr, ['currentPage'=>1, 'itemsPerPage'=>3, 'pageStr'=>"page", 'perPageStr'=>"per_page"]);

//$p->getPageObj

echo "<pre>";
    print_r($p);
echo "</pre>";
echo "<pre>";
    print_r($pagedata);
echo "</pre>";
