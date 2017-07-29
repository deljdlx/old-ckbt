<?php

require(__DIR__.'/../source/bootstrap.php');

$arguments = $argv;



array_shift($argv);
$filePathes = $argv;



$comparator=new \CKBT\Comparator();

foreach ($filePathes as $path) {
    $file = new \CKBT\File($path);
    $comparator->addFile($file);
}


$doublons=$comparator->compareAll();


foreach ($doublons as $doublon) {
    echo
            $doublon->getSource()."\t".
            $doublon->getSourceSentence()->getOffset()."\t".

            $doublon->getComparisonFile()."\t".
            $doublon->getCompareSentence()->getOffset()."\t".


            $doublon->getSourceSentence();
    echo "\n";
}




