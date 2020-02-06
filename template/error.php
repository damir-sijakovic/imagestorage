<?php


echo $this->passViewData('errorData', [
    'title' => $this->projectCodeName,
    'msg' => $this->getError(),    
]);
echo file_get_contents(__DIR__ . '/error/page.html', true);
die();
