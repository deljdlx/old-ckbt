<?php

require(__DIR__.'/../source/bootstrap.php');

ini_set('memory_limit', '600M');

$file = new \CKBT\File(__DIR__.'/../test/lorem1.txt');

$arguments = $argv;



array_shift($argv);
$filePathes = $argv;



$comparator=new \CKBT\Comparator();

foreach ($filePathes as $path) {
    $file = new \CKBT\File($path);
    $comparator->addFile($file);
}


$doublons=$comparator->compareAll();


/**
 * @var \CKBT\Match[] $doublons
 */

foreach ($doublons as $match) {


    foreach ($match->getOccurances() as $occurance) {
        echo $occurance['source']->getPath().':'.$occurance['offset']."\t";
    }
    echo $match->getSentence()->normalize()."\n";


    /*
    echo
            $doublon->getSource()."\t".
            $doublon->getSourceSentence()->getOffset()."\t".

            $doublon->getComparisonFile()."\t".
            $doublon->getCompareSentence()->getOffset()."\t".


            $doublon->getSourceSentence();
    echo "\n";
    */
}




