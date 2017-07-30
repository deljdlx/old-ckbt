<?php

namespace CKBT;

class File
{

    private $sentenceSeparator = "[.?!\n]+?";

    private $path;

    private $pointer;
    private $offset = 0;


    private $currentBuffer = '';

    private $endOfFile = false;


    public function __construct($path)
    {
        $this->path = $path;
    }


    /**
     * @return bool|Sentence
     */
    public function getSentence() /*@php_version:PHP7.1 : ?string*/
    {

        if (!$this->pointer) {
            $this->pointer = fopen($this->path, 'r');
        }

        while (!preg_match('`' . $this->sentenceSeparator . '`ms', $this->currentBuffer)) {
            $buffer = fgets($this->pointer, 1024);


            if ($buffer) {
                $this->currentBuffer .= $buffer;
            }
            else {
                if (!$this->endOfFile) {
                    $this->endOfFile = true;
                    return $this->wrapSentence($this->currentBuffer, '');
                }
                else {
                    return false;
                }
            }
        }

        $sentence = preg_replace('`(.*?)' . $this->sentenceSeparator . '.*`s', '$1', $this->currentBuffer);
        $sentenceEnd = preg_replace('`.*?(' . $this->sentenceSeparator . ').*`s', '$1', $this->currentBuffer);


        $this->currentBuffer = preg_replace('`.*?' . $this->sentenceSeparator . '(.*)`s', '$1', $this->currentBuffer);


        if (!strlen($sentence . $sentenceEnd)) {
            return $this->getSentence();
        }
        else {

            return $this->wrapSentence($sentence, $sentenceEnd);
        }
    }


    /**
     * @param $offset
     * @return bool|Sentence
     */
    public function getSentenceByOffset($offset)
    {
        fseek($this->pointer, $offset);
        return $this->getSentence();
    }


    /**
     * @return $this
     */
    public function rewind()
    {
        fseek($this->pointer, 0);
        $this->offset = 0;
        return $this;
    }

    /**
     * @param $content
     * @param $sentenceEnd
     * @return Sentence
     */
    public function wrapSentence($content, $sentenceEnd) /*@php_version:PHP7.0 : Sentence*/
    {
        $sentence = new Sentence($this->offset, $content, $sentenceEnd);
        $this->offset += mb_strlen($content . $sentenceEnd);
        return $sentence;
    }


    /**
     * @return string
     *
     */
    public function getFingerPrint()
    {
        return basename($this->path);
    }


    public function getPath()
    {
        return $this->path;
    }


    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->path;
    }

}



