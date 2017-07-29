<?php

namespace CKBT;

class Comparator
{

    /**
     * @var File[]
     */
    private $files = array();

    public function __construct()
    {

    }


    public function addFile(File $file)
    {
        $this->files[] = $file;
        return $this;
    }

    public function compareAll()
    {
        $doublons = array();

        $compared = array();

        foreach ($this->files as $indexSource => $source) {


            foreach ($this->files as $indexCompare => $compare) {
                if ($indexCompare !== $indexSource && !isset($compared[(string)$source . '-' . (string)$compare])) {
                    $doublons = array_merge($doublons, $this->compare($source, $compare));

                    $compared[(string)$source . '-' . (string)$compare] = true;
                    $compared[(string)$compare . '-' . (string)$source] = true;

                }
            }
        }

        return $doublons;
    }


    public function compare(File $source, File $compare)
    {
        $doublons = array();

        while ($sourceSentence = $source->getSentence()) {

            while ($compareSentence = $compare->getSentence()) {

                if (trim($sourceSentence) === trim($compareSentence)) {
                    $match = new Match($source, $compare, $sourceSentence, $compareSentence);
                    $doublons[] = $match;
                }
            }

            $compare->rewind();
        }

        $source->rewind();

        return $doublons;
    }
}



