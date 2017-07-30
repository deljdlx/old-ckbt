<?php

namespace CKBT\ComparatorStrategy;


use CKBT\Comparator;
use CKBT\Match;


class Hash extends Comparator
{

    public function compareAll()
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

                /*
                $i++;
                echo $i . "\t" . ($usage / (1048576)) . "\t" . $currentUsage . "\t" . (int)$sentence->getOffset() . "\t" . $source->getPath();
                echo "\n";
                */

                $key = $sentence->getHash();
                //on ne traite que les lignes non vides

                $sourceKey = $source->getFingerPrint();

                if (strlen(trim($sentence))) {

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

                        if (!isset($sentences[$key]['occurrences'][$sourceKey]['source'])) {
                            $sentences[$key]['occurrences'][$sourceKey]['source'] = $source;
                        }

                        if ($sentences[$key]['sentence'] === null) {
                            $sentences[$key]['sentence'] = $sentence;
                        }


                        $sentences[$key]['occurrences'][$sourceKey]['offsets'][] = (int)$sentence->getOffset();

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

}




