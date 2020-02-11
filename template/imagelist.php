<?php
namespace DSimageStorage;

$userOnline = $this->sessionHaveUser();
$userData = false;


if ($userOnline)
{
    $userData = $this->getUserSessionData();
    $imgData = $this->paginateImageData($userData['page'], $userData['perpage']); //pagination data
    $pagination = $this->getPaginationData();    

    echo $this->passViewData('accountData', 
    [
        'session' => $userOnline,
        'title' => $this->projectCodeName,
        'imgData' => $imgData,   
        'userData' => $userData,    
        'imageDir' => $this->getConfigValue('publicUploadDir'),
        'paginator' => $pagination,
    ]);

    echo file_get_contents(__DIR__ . '/layout/head.html', true);
    echo file_get_contents(__DIR__ . '/layout/flash.html', true);
    echo file_get_contents(__DIR__ . '/layout/navbar.html', true);
    echo file_get_contents(__DIR__ . '/imagelist/upload.html', true);    
    echo file_get_contents(__DIR__ . '/imagelist/list.html', true);   
    echo file_get_contents(__DIR__ . '/imagelist/paginate.html', true);
    echo file_get_contents(__DIR__ . '/layout/tail.html', true);
}
else 
{
    $this->setError("Access forbiden.");  
    $this->viewErrorPage();
}



die();
