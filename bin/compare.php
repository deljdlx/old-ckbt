<?php

require(__DIR__.'/../source/bootstrap.php');

ini_set('memory_limit', '600M');

$file = new \CKBT\File(__DIR__.'/../test/lorem1.txt');

$arguments = $argv;



array_shift($argv);
$filePathes = $argv;



if(empty($filePathes)) {
    $filePathes=array(
        __DIR__.'/../www/file/lorem0.txt',
        __DIR__.'/../www/file/lorem1.txt',
        __DIR__.'/../www/file/lorem2.txt',
        __DIR__.'/../www/file/lorem3.txt',
    );
}


/**
 *
 * CHANGER LES STRATEGIES ICI
 *
 */


//$comparator=new \CKBT\ComparatorStrategy\SQLite();
//$comparator=new \CKBT\ComparatorStrategy\DumbAndCheap();
$comparator=new \CKBT\ComparatorStrategy\Hash();


foreach ($filePathes as $path) {
    $file = new \CKBT\File($path);
    $comparator->addFile($file);
}


$doublons=$comparator->compareAll();


/**
 * @var \CKBT\Match[] $doublons
 */

foreach ($doublons as $match) {


    foreach ($match->getOccurrences() as $occurance) {
        echo realpath($occurance['source']->getPath()).':'.$occurance['offset']."\t";
    }
    echo $match->getSentence()."\n";
}




