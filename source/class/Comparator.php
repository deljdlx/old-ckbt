<?php

namespace CKBT;

class Comparator
{

    /**
     * @var File[]
     */
    private $files = array();
    private $comparator = null;

    public function __construct()
    {

    }


    public function setComparator($function)
    {
        $this->comparator = $function;
        return $this;
    }


    public function addFile(File $file)
    {
        $this->files[] = $file;
        return $this;
    }


    public function compareAllOk($withEmptySentence = false)
    {
        $doublons = array();
        $sentences = array();

        //scan des phrases

        $start = memory_get_usage();
        $lastUsage = $start;

        $i = 0;

        foreach ($this->files as $indexSource => $source) {
            while ($sentence = $source->getSentence()) {

                $usage = memory_get_usage() - $start;
                $currentUsage = $usage - $lastUsage;
                $lastUsage = $usage;

                $i++;

                //echo $i . "\t" . ($usage / (1048576)) . "\t" . $currentUsage . "\t" . (int)$sentence->getOffset() . "\t" . $source->getPath();
                //echo "\n";

                $key = $sentence->getHash();
                //on ne traite que les lignes non vides

                $sourceKey = $source->getFingerPrint();

                if (strlen(trim($sentence)) || $withEmptySentence) {

                    if (!isset($sentences[$key])) {


                        $sentences[$key] = array(
                            'occurrences' => array(
                                $sourceKey => array(
                                    'source' => $source,
                                    'offsets' => array(
                                        (int)$sentence->getOffset()
                                    )
                                )
                            ),
                            'sentence' => null
                        );
                    }

                    else {


                        $sentences[$key]['occurrences'][$source->getFingerPrint()]['source'] = $source;


                        $sentences[$key]['occurrences'][$source->getFingerPrint()]['offsets'][] = (int)$sentence->getOffset();

                        if ($sentences[$key]['sentence'] === null) {
                            $sentences[$key]['sentence'] = $sentence;
                        }

                    }
                }

            }
        }


        //filtrage des phrases apparaissant en plusieurs occurances
        foreach ($sentences as $descriptor) {
            if ($descriptor['sentence'] !== null) {


                $match = new Match($descriptor['sentence']);

                foreach ($descriptor['occurrences'] as $occurenceByFile) {

                    foreach ($occurenceByFile['offsets'] as $offset) {

                        $match->addOccurrence($occurenceByFile['source'], $offset);

                    }
                }

                $doublons[] = $match;

            }
        }


        return $doublons;
    }




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


    public function compareFiles(File $source, File $compare, &$doublons)
    {


        while ($sourceSentence = $source->getSentence()) {

            while ($compareSentence = $compare->getSentence()) {

                if ($this->compareSentences($sourceSentence, $compareSentence)) {

                    if(!isset($doublons[$sourceSentence->getHash()])) {
                        $match = new Match($sourceSentence);
                        $doublons[$sourceSentence->getHash()]=$match;
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


    protected function compareSentences($source, $compare)
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




