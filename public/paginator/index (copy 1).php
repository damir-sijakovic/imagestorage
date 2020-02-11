<?php
  
class Paginator 
{
    /*
    public $url;
    public $urlWithoutParams;
    public $cleanUrl;
    public $items;
    public $numberOfItems;
    public $numberOfPages;
    public $itemsPerPage;  
    public $pageData;
    public $urlParamName = 'page';
    */

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
    
       
    /*
     
    addIdDataArray()
    getFirstPage()
    getLastPage()
    getNextPage()
    getPrevPage()   
    setItemsPerPage()    
    getCleanUrl()    
      
      trigger_error('Paginator.__construct(): argument itemsPerPage is less then 1.' , E_USER_NOTICE);
      
    */
    
    // public function __construct(){}
    public function __construct(){
        $this->url = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];  
        $this->urlWithoutParams = $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'], 2)[0];  
        $this->currentPage = 1;
    }
   
    public function setUrlPageString($string){
        if (!is_string($string)) trigger_error('setUrlPageString(): argument is not string.' , E_USER_NOTICE);
        $this->urlPageString = $string;        
    }
   
    public function setUrlPerPageString($string){
        if (!is_string($string)) trigger_error('setUrlPerPageString(): argument is not string.' , E_USER_NOTICE);
        $this->urlPerPageString = $string;        
    }
    
    public function setIdDataArray($array){
        if (!is_array($array)) trigger_error('setIdDataArray(): argument is not array.' , E_USER_NOTICE);
        $this->items = $array;        
    }
    
    public function setItemsPerPage($int){
        if (!is_int($int)) trigger_error('setIdDataArray(): argument is not int.' , E_USER_NOTICE);
        $this->itemsPerPage = $int;        
    } 
    
    public function prepare(){
        if (is_null($this->urlPageString) && is_null($this->urlPerPageString)){
            trigger_error('getCleanUrl(): set urlPageString() and urlPerPageString() before using getCleanUrl().' , E_USER_NOTICE);   
        }        
        
        //create cleanUrl        
        $parsed = parse_url($this->url);        
        $query = $parsed['query'];
        parse_str($query, $params);
        unset($params['page']);
        unset($params['per_page']);
        $this->cleanUrl = http_build_query($params);         

        //page data        
        $this->numberOfItems = count($this->items);        
        $this->pageData = array_chunk($this->items, $this->itemsPerPage, true);
        $this->numberOfPages = count($this->pageData); 
    } 
        
    function getPageGroup($number){ // :array
        if ($number > $this->numberOfPages) return null;
        
        return $this->pageData[$number-1];
    }
    
    function getFirstPage(){ // :array   
        return $this->pageData[0];
    }
    
    function getLastPage(){ // :array          
        return $this->pageData[array_key_last($this->pageData)];
    }
    
    function getCurrentPageNumberFromUrl(){ 
        $retval;
        $url = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];  
        $parsed = parse_url($url);
        $query = $parsed['query'];        
        parse_str($query, $params);
        $retval = $params[$this->urlPageString];   
        if (isset($retval)) return $retval;
        return -1;
    }
    
    function getPageObj(){ // :array
        if (is_null($this->urlPageString) && is_null($this->urlPerPageString)){
            trigger_error('getCleanUrl(): set urlPageString() and urlPerPageString() before using getCleanUrl().' , E_USER_NOTICE);   
        } 
             
        $returnObject = new StdClass;
        $returnObject->firstPage = null;
        $returnObject->secondPage = null;
        $returnObject->prevPage = null;
        $returnObject->nextPage = null;
        
        return $returnObject;
    }
    
};


//$p = new Paginator($arr, 3, 'page', 'per_page');

$p = new Paginator();

$p->setUrlPageString('page');
$p->setUrlPerPageString('per_page');
$p->setIdDataArray([12,54,33,11,77,44,88,46,24,76,55]);
$p->setItemsPerPage(3);

$p->prepare();



echo "<pre>";
    print_r($p->getPageObj());
echo "</pre>";




/*
class Paginator 
{
    public $url;
    public $urlWithoutParams;
    public $cleanUrl;
    public $items;
    public $numberOfItems;
    public $numberOfPages;
    public $itemsPerPage;  
    public $pageData;
    public $urlParamName = 'page';
    
    function makePaginator($arr, $itemsPerPage){        
        //ARG CHECK
        if (!is_array($arr)){
            trigger_error('Paginator.__construct(): argument is not array.' , E_USER_NOTICE);
            die(); 
        }
        
        foreach($arr as $value){
            if (!is_int($value)){
                trigger_error('Paginator.__construct(): argument array values must be integers.' , E_USER_NOTICE);
                die(); 
            }
            
        }
        
        if ($itemsPerPage < 1){
            trigger_error('Paginator.__construct(): argument itemsPerPage is less then 1.' , E_USER_NOTICE);
            die(); 
        }
            
        //URL DATA
        $this->url = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];  
        $this->urlWithoutParams = $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'], 2)[0];
     
        $parsed = parse_url($this->url);
        $query = $parsed['query'];

        parse_str($query, $params);

        unset($params[$this->urlParamName]);
        $string = http_build_query($params);
        $this->cleanUrl = $this->urlWithoutParams . '?' . $string;
                        
        //ITEMS                                        
        $this->items = $arr;
        $this->itemsPerPage = $itemsPerPage;
        $this->numberOfItems = count($arr);        
        $this->pageData = array_chunk($arr, $itemsPerPage, true);
        $this->numberOfPages = count($this->pageData);
        //
    }
    
    function makePageUrl($number){
        if ($number > $this->numberOfItems) return null;
        $amp = '&';
        if ($this->urlWithoutParams.'?' === $this->cleanUrl){
            $amp = '';
        }
        return $this->cleanUrl . $amp . $this->urlParamName . '=' . $number;  
    }
    
    function getPageGroup($number){ 
        if ($number > $this->numberOfPages) return null;
        
        return $this->pageData[$number-1];
    }
    
    function getCurrentPageNumber(){ 
        $retval;
        $url = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];  
        $parsed = parse_url($url);
        $query = $parsed['query'];        
        parse_str($query, $params);
        $retval = $params[$this->urlParamName];   
        if (isset($retval)) return $retval;
        return -1;
    }
       
};

*/

/*
$p = new Paginator([12,54,33,11,77,44,88,46,24,76], 3);

echo "<pre>";
    print_r($p);
echo "</pre>";

echo "<pre>";
    //print_r($p->getPageGroup(5));
  // if (is_null(print_r(  $p->getCurrentPageNumber()) )) echo "NULL";
echo "</pre>";


$p->getCurrentPageNumber();
*/
  
