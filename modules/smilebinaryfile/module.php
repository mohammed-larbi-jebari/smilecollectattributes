<?php

$Module = array( 'name' => 'SmileBinaryFile' );

$ViewList = array();

$ViewList['deletebinary'] = array(
        'functions' => array( 'deletebinary' ),
        'params' => array(),
        'script' => 'deletebinaryfile.php' );


$ViewList['download'] = array(
        'functions' => array( 'download' ),
        'script' => 'downloadbinaryfile.php',
        'params' => array( 'informationCollectionID', 'ContentObjectAttributeID' ),
        'unordered_params' => array() );

$FunctionList = array();
$FunctionList['deletebinary'] = array();
$FunctionList['download'] = array();

?>
