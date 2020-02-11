<?php

$userOnline = $this->sessionHaveUser();
$userData = false;
if ($userOnline){
    $userData = $this->getUserSessionData();
}


echo $this->passViewData('accountData', [
    'session' => $userOnline,
    'title' => $this->projectCodeName,
    'userData' => $userData,    
]);

echo file_get_contents(__DIR__ . '/layout/head.html', true);
echo file_get_contents(__DIR__ . '/layout/flash.html', true);

echo file_get_contents(__DIR__ . '/layout/navbar.html', true);

if ($userOnline){
    echo file_get_contents(__DIR__ . '/account/panel.html', true);
    echo file_get_contents(__DIR__ . '/account/imagenumber.html', true);
    
}
else
{
    echo file_get_contents(__DIR__ . '/account/notregistrated.html', true);
    echo file_get_contents(__DIR__ . '/account/register.html', true);
    echo file_get_contents(__DIR__ . '/account/login.html', true);
}

echo file_get_contents(__DIR__ . '/layout/tail.html', true);

die();
