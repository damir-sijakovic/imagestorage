<?php

namespace DSimageStorage;

class Config
{
    
    public $projectCodeName = "ds_img_storage"; //no whitespace
    
	public $config = 
    [    

        //db credentials
		'dbHost' => 'localhost',
		'dbPort' => 3306,
		'dbName' => 'dsImageStorage',
		'dbUserName' => 'dsImageStorage',
		'dbPass' => 'dsImageStorage',     
        
        //image
        'thumbnailSize' => 64,   
        'uploadDir' => __DIR__ . '/public/_imagedata_/',
        'publicUploadDir' => './_imagedata_/',
        
        
	]; 
    
};
