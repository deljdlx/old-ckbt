<?php

namespace CKBT;

class File
{

    private $path;

    private $pointer;
    private $offset = 0;


    private $currentBuffer = '';

    private $endOfFile = false;

    public function __construct($path)
    {
        $this->path = $path;
        $this->pointer = fopen($this->path, 'r');

    }


    public function getSentence($normalize = false)
    {

        while (!preg_match('`[.?!]`s', $this->currentBuffer)) {
            $buffer = fgets($this->pointer, 1024);


            if ($buffer) {
                $this->currentBuffer .= $buffer;
            }
            else {
                if (!$this->endOfFile) {
                    $this->endOfFile = true;
                    if($normalize) {
                        return $this->wrapSentence($this->normalize($this->currentBuffer));
                    }
                    return $this->wrapSentence($this->currentBuffer);
                }
                else {
                    return false;
                }
            }
        }

        $sentence = preg_replace('`(.*?[.?!]+).*`s', '$1', $this->currentBuffer);
        $this->currentBuffer = preg_replace('`.*?[.?!]+(.*)`s', '$1', $this->currentBuffer);


        if (!strlen($sentence)) {
            return $this->getSentence();
        }
        else {
            if ($normalize) {
                return $this->wrapSentence($this->normalize($sentence), $this->offset);
            }
            else {
                return $this->wrapSentence($sentence, $this->offset);
            }

        }
    }


    protected function normalize($buffer) {
        return trim($buffer);
    }


    public function rewind()
    {
        fseek($this->pointer, 0);
        $this->offset = 0;
        return $this;
    }

    public function wrapSentence($content) {
        $sentence=new Sentence($this->offset, $content);
        $this->offset+=mb_strlen($content);
        return $sentence;
    }



    public function __toString()
    {
        return $this->path;
    }

}



