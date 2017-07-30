<?php

namespace CKBT;


class Sentence
{


    private $content;
    private $offset;
    private $end;

    public function __construct($offset, $content, $sentenceEnd = '')
    {
        $this->offset = $offset;
        $this->content = $content;
        $this->end = $sentenceEnd;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getFingerprint($prefix = '')
    {
        return $prefix . '-' . $this->getHash();
    }

    public function getHash()
    {
        $normalized = $this->normalize($this->content);
        return md5($normalized) . crc32($normalized);
    }

    public function normalize()
    {
        return trim($this->content);
    }

    public function getContent()
    {
        return $this->content;
    }


    public function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }

    public function getEnd()
    {
        return $this->end;
    }



    public function __toString()
    {
        //return $this->content;

        return $this->content . $this->end;
    }


}