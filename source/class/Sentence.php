<?php

namespace CKBT;


class Sentence
{


    private $content;
    private $offset;

    public function __construct($offset, $content)
    {
        $this->offset = $offset;
        $this->content = $content;
    }

    public function getOffset() {
        return $this->offset;
    }

    public function getFingerprint() {
        return md5(trim($this->content)).'-'.$this->offset;
    }


    public function __toString()
    {
        return $this->content;
    }


}