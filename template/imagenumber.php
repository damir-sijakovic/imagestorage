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
    'imgData' => $imgData,   
    'userData' => $userData,    
]);



if ($userOnline) {
    echo file_get_contents(__DIR__ . '/layout/head.html', true);
    echo file_get_contents(__DIR__ . '/layout/flash.html', true);
    echo file_get_contents(__DIR__ . '/layout/navbar.html', true);
    echo file_get_contents(__DIR__ . '/imagenumber/number.html', true);
    echo file_get_contents(__DIR__ . '/layout/tail.html', true);
}
else {
    $this->setError("Access forbiden.");  
    $this->viewErrorPage();
}

die();
