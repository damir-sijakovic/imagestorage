<?php

namespace DSimageStorage;
require __DIR__ . '/../App.php';

//consoleLog("hello app");
session_start();

$app = new App();
$app->parseUrl($_REQUEST);

