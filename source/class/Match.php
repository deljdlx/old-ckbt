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

    public function addOccurrence(File $file, $offset)
    {
        $key = $file->getFingerPrint() . '-' . $offset;
        $this->occurances[$key] = array(
            'source' => $file,
            'offset' => $offset
        );

        return $this;
    }

    public function getOccurrences()
    {
        return $this->occurances;
    }

    public function getSentence()
    {
        return $this->sentence;
    }


}