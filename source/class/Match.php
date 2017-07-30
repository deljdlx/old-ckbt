<?php

namespace CKBT;


class Match
{


    private $sentence = null;
    private $occurances = array();


    public function __construct(Sentence $sentence)
    {
        $this->sentence = $sentence;
    }

    public function addOccurance($file, $offset)
    {
        $this->occurances[] = array(
            'source' => $file,
            'offset' => $offset
        );
    }

    public function getOccurances()
    {
        return $this->occurances;
    }

    public function getSentence()
    {
        return $this->sentence;
    }


    /*
    private $source;
    private $compare;
    private $sourceSentence;
    private $compareSentence;


    public function __construct(File $source, $compare, $sourceSentence, $compareSentence)
    {
        $this->source = $source;
        $this->compare = $compare;
        $this->sourceSentence = $sourceSentence;
        $this->compareSentence=$compareSentence;
    }


    public function getSourceSentence() {
        return $this->sourceSentence;
    }

    public function getCompareSentence() {
        return $this->compareSentence;
    }




    public function getSource() {
        return $this->source;
    }

    public function getComparisonFile() {
        return $this->compare;
    }
    */


}