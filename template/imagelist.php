<?php
  
$userOnline = $this->sessionHaveUser();
$imgData = $this->getImageData();

$userData = false;
if ($userOnline){
    $userData = $this->getUserSessionData();
}


echo $this->passViewData('accountData', [
    'session' => $userOnline,
    'title' => $this->projectCodeName,
   // 'imgFiles' => $imgFiles,    
    'imgData' => $imgData,   
    'userData' => $userData,    
    'imageDir' => $this->getConfigValue('publicUploadDir'),
]);



if ($userOnline) {
    echo file_get_contents(__DIR__ . '/layout/head.html', true);
    echo file_get_contents(__DIR__ . '/layout/flash.html', true);
    echo file_get_contents(__DIR__ . '/layout/navbar.html', true);
    //echo file_get_contents(__DIR__ . '/imagelist/modal.html', true);
    echo file_get_contents(__DIR__ . '/imagelist/upload.html', true);
    echo file_get_contents(__DIR__ . '/imagelist/list.html', true);
    echo file_get_contents(__DIR__ . '/layout/tail.html', true);
}
else {
    $this->setError("Access forbiden.");  
    $this->viewErrorPage();
}

die();
