<?php

namespace CKBT;


class Match
{

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


}