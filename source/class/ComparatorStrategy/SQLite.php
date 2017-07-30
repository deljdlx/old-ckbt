<?php

namespace CKBT\ComparatorStrategy;


use CKBT\Comparator;
use CKBT\File;
use CKBT\Match;
use CKBT\Sentence;

class SQLite extends Comparator
{


    //attention, requêtes SQL en mode sale
    public function compareAll($withEmptySentence = false)
    {
        $sqlite = new \SQLite3(':memory:');

        $createTable = "
            CREATE TABLE sentence (
                content varchar(1024),
                file varchar,
                offset INTEGER,
                end varchar
            );
        ";

        $sqlite->exec($createTable);
        $sqlite->exec('CREATE INDEX sentenceKey ON sentence(content, file, offset);');


        foreach ($this->files as $indexSource => $source) {
            while ($sentence = $source->getSentence()) {

                if (trim($sentence)) {
                    //sale !
                    $query = "
                    INSERT INTO sentence VALUES (
                      '" . $sqlite->escapeString($sentence->getContent()) . "',
                      '" . $sqlite->escapeString($source) . "',
                      " . $sqlite->escapeString($sentence->getOffset()) . ",
                      '" . $sqlite->escapeString($sentence->getEnd()) . "'
                    )
                ";
                    $sqlite->exec($query);
                }
            }
        }


        $query = "
            SELECT
              s0.content as content,
              s0.end as end,
              s0.file as f0,
              s1.file as f1,
              s0.offset as o0,
              s1.offset as o1
             FROM sentence s0
             JOIN sentence s1
              ON trim(s0.content)=trim(s1.content)
              AND s0.content!=''
              AND (s0.file!=s1.file OR s0.offset!=s1.offset)
        ";

        $statement = $sqlite->query($query);


        $doublons = array();
        while ($values = $statement->fetchArray(SQLITE3_ASSOC)) {

            $sentence = new Sentence(0, $values['content'], $values['end']);
            $key = $sentence->getHash();

            $file0 = new File($values['f0']);
            $file1 = new File($values['f1']);


            if (!isset($doublons[$key])) {
                $match = new Match($sentence);
                $doublons[$key] = $match;
            }
            else {
                $match = $doublons[$key];
            }

            $match->addOccurrence($file0, $values['o0']);
            $match->addOccurrence($file1, $values['o1']);
        }

        return $doublons;
    }

}




