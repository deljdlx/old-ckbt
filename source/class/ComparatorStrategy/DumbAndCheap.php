<?php

namespace CKBT\ComparatorStrategy;

use CKBT\Comparator;
use CKBT\File;
use CKBT\Match;

class DumbAndCheap extends Comparator
{

    public function compareAll()
    {


        $doublons = array();

        $compared = array();


        foreach ($this->files as $indexSource => $source) {


            foreach ($this->files as $indexCompare => $compare) {
                if ($indexCompare !== $indexSource && !isset($compared[(string)$source . '-' . (string)$compare])) {


                    $doublons = array_merge($doublons, $this->compareFiles($source, $compare, $doublons));

                    $compared[(string)$source . '-' . (string)$compare] = true;
                    $compared[(string)$compare . '-' . (string)$source] = true;


                }
            }
        }


        return $doublons;
    }


    private function compareFiles(File $source, File $compare, &$doublons)
    {

        while ($sourceSentence = $source->getSentence()) {

            while ($compareSentence = $compare->getSentence()) {

                if ($this->compareSentences($sourceSentence, $compareSentence)) {

                    if (!isset($doublons[$sourceSentence->getHash()])) {
                        $match = new Match($sourceSentence);
                        $doublons[$sourceSentence->getHash()] = $match;
                    }

                    $doublons[$sourceSentence->getHash()]->addOccurrence($source, $sourceSentence->getOffset());
                    $doublons[$sourceSentence->getHash()]->addOccurrence($compare, $compareSentence->getOffset());
                }
            }

            $compare->rewind();
        }

        $source->rewind();

        return $doublons;
    }


    private function compareSentences($source, $compare)
    {
        if (is_callable($this->comparator)) {
            return call_user_func_array($this->comparator, array($source, $compare));
        }
        else {
            if (trim($source) === trim($compare)) {
                return true;
            }
            else {
                return false;
            }
        }
    }


}




