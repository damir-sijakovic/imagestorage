<?php
  
namespace DSimageStorage;
require __DIR__ . '/User.php';

class Image extends User
{
    public function uploadImage($files){
       
        if ($files["error"])
        {
            return ['error' => "Image upload error code: ". $files["error"]]; 
        }     
                
        
        $uploadDir = $this->getConfigValue('uploadDir');
        $thumbnailSize = $this->getConfigValue('thumbnailSize');
        
        if (!is_writable($uploadDir))
        {                
            return ['error' => 'Project "images" directory is not set to read/write.']; 
           
        }
        
        if (($files['size'] >= 2097152) || ($files["size"] == 0)) 
        {
            return ['error' => 'File too large. File must be less than 2 megabytes.']; 
        }
        
                
        $filetype = '';
        
        if ($files["type"] == "image/jpeg")
        {	
            $filetype = 'jpg';
        }
        else if($files["type"] == "image/png")
        {	
            $filetype = 'png';        
        } 
        
        // file info
        $filesize = $files["size"];
        $originalFilename = pathinfo($files["name"])['filename'];
        $userId = $this->getUserSessionData()['id'];    
         
        // store to sql
        $imageId = $this->addImage($originalFilename, $filetype, intval($userId),  intval($filesize));
        if ($this->haveError()){
            return ['error' => $this->getError()]; 
        }
   
        $outputFilename = $userId  . '_' . $imageId  . '.' . $filetype;     
        $outputThumbFilename = $userId  . '_' . $imageId  . '_t.' . $filetype;     
        
        if ($filetype == 'jpg')
        {
            $thumbMade = $this->makeJpgThumb($files['tmp_name'], $uploadDir.$outputThumbFilename, $thumbnailSize);
            if (!$thumbMade) return ['error' => "Upload failed."];
        }
        else if ($filetype == 'png')
        {
            $thumbMade = $this->makePngThumb($files['tmp_name'], $uploadDir.$outputThumbFilename, $thumbnailSize);
            if (!$thumbMade) return ['error' => "Upload failed."];
        }
        
        if (move_uploaded_file($files['tmp_name'], $uploadDir.$outputFilename))
        {    
            $this->setSessionConfig('project_images', $this->getNumberOfImages());  
            
            $returnData = [
                'imageid' => $imageId,
                'filename' => $originalFilename,
                'type' => $filetype,
                'size' => $filesize,
                'userid' => $userId,
                'username' => $this->getUserSessionData()['name'],             
                'originalname' => $files['name'],
                'newname' => $outputFilename,
            ];

            return ['success' => $returnData]; 
        }
        else 
        {
            return ['error' => "Upload failed."]; 
        }

        return ['error' => "Upload failed."]; 
    }
    
    
    function makeJpgThumb($src, $dest, $desired_width) 
    {
        $source_image = imagecreatefromjpeg($src);
        $width = imagesx($source_image);
        $height = imagesy($source_image);
        
        $desired_height = floor($height * ($desired_width / $width));
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
        return imagejpeg($virtual_image, $dest);
    }
    
    function makePngThumb($src, $dest, $desired_width) 
    {
        $source_image = imagecreatefrompng($src);
        $width = imagesx($source_image);
        $height = imagesy($source_image);
        
        $desired_height = floor($height * ($desired_width / $width));
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
        return imagepng($virtual_image, $dest);
    }
    
    
    function getThumbnails()
    {
        $uploadDir = $this->getConfigValue('uploadDir');
        
        $jpg = glob($uploadDir. '*t.jpg');
        $png = glob($uploadDir. '*t.png');
        
        if (count($jpg) == 0 && count($png) == 0)
        {
            return [];
        }
        
        $filelist = array_merge($jpg, $png);         
        $filelist = array_map(function ($a) { return basename($a); }, $filelist);
        
        
        // consoleDump($filelist);
        return $filelist;                
    } 
    
    public function getNumberOfDirImages()
    {  
        $uploadDir = $this->getConfigValue('uploadDir');
        $fileNumber = count(scandir($this->getConfigValue('uploadDir'))) -3;
        
        if ($fileNumber == 0){
           return $fileNumber; 
        }
        
        if ($number % 2 == 0) { //if even
            return $fileNumber / 2;
        }
        else {
            return -1;
        }
        
    }  
    
    public function getImageNumber()
    {  
        $status = $this->getNumberOfImages();        
        return ['success' => $status];        
    }  
    
    
    
    public function deleteDirImage($imgid)
    {     
        $uploadDir = $this->getConfigValue('uploadDir'); 
        
        
                
        if (!is_writable($uploadDir))
        {   
            return ['error' => 'Project "images" directory is not set to read/write.']; 
        }
          
    
        $imagefiles = glob($uploadDir . '*_' .$imgid. '*'); 
        
        if (count($imagefiles) > 0){ 
            
            $unlinkImg = unlink($imagefiles[0]);
            $unlinkThumb = unlink($imagefiles[1]);
            
            if ($unlinkImg && $unlinkThumb)
            {  
                return ['success' => "Image deleted."]; 
            }   
        }
        else 
        {
            return ['error' => 'Internal error: No image file found.'];     
        }
        
        
    }
    
    
    public function deleteUserDirImages($userid)
    {     
        $uploadDir = $this->getConfigValue('uploadDir'); 
                
        if (!is_writable($uploadDir))
        {   
            return ['error' => 'Project "images" directory is not set to read/write.']; 
        }
          

        $imagefiles = glob($uploadDir . $userid . '_*'); 
        
        if (count($imagefiles) > 0)
        {
            foreach($imagefiles as $file)
            { 
                if (is_file($file))
                {
                    unlink($file); 
                }
            }
        }
        else 
        {
            return ['success' => 'No images to delete.']; 
        }
        
        $imagefiles = glob($uploadDir . $userid . '_*'); 
        
        if (count($imagefiles) == 0)
        {
            return ['success' => 'All image file deleted.']; 
        }
        else {
            return ['error' => 'Internal error.']; 
        }
        
    }
    

    public function imageAlreadyUploaded($imageMd5)
    {
        $uploadDir = $this->getConfigValue('uploadDir'); 
        $filelist = glob($uploadDir . '*'); 
        
        $filelist = array_map(function ($a) { return md5_file($a); }, $filelist);
                
        foreach ($filelist as $v) {
            if ($v == $imageMd5){
                return true;
            }            
        }

        return false;         
    }
    
    
    
};




