<?php


require(__DIR__ . '/../source/bootstrap.php');

$comparePath = __DIR__ . '/file';


/*
 * pas de vrai couche controleur ; pas eu le temps
 */


//=======================================================
//upload
if (!empty($_FILES)) {
    $data = reset($_FILES);
    $path = $data['tmp_name'];
    $destination = $comparePath . '/' . $data['name'];

    //Attention pas de vérification si c'est bien un fichier texte
    move_uploaded_file($path, $destination);
}

//=======================================================




//=======================================================
//supression de fichier
if (isset($_GET['delete'])) {

    //securité ultra minimale
    $cleaned=str_replace('../', '', $_GET['delete']);


    if (is_file($comparePath . '/' . $cleaned)) {
        unlink($comparePath . '/' . $cleaned);
    }
}
//=======================================================


//$comparator = new \CKBT\ComparatorStrategy\SQLite();
//$comparator = new \CKBT\ComparatorStrategy\DumbAndCheap();
$comparator = new \CKBT\ComparatorStrategy\Hash();



//affichage des contenus des fichier dans www/file=====================================
$dir = opendir($comparePath);
ob_start();
while ($fileName = readdir($dir)) {

    $path = $comparePath . '/' . $fileName;
    if (is_file($path)) {

        $file = new \CKBT\File($path);
        $comparator->addFile($file);

        echo '<div class="file">';

        echo '<h2>' . basename($path) . '<i data-file="' . basename($path) . '" class="delete fa fa-times" aria-hidden="true"></i></h2>';


        while ($sentence = $file->getSentence()) {

            $id = $sentence->getFingerprint(
                $file->getFingerPrint() . '-' . $sentence->getOffset()
            );

            $hash = $sentence->getHash();

            echo '<span class="sentence" data-sentence-id="' . $id . '" data-sentence-hash="' . $hash . '">' . nl2br($sentence) . '</span>';

        }
        $file->rewind();
        echo '</div>';
    }
}
$content = ob_get_clean();
//===========================================================================



//Détection des doublons=======================================================
$doublons = $comparator->compareAll();
$descriptors = array();


/**
 * @var $doublons \CKBT\Match[]
 */


foreach ($doublons as $match) {
    $occuranceDescriptors = array();

    foreach ($match->getOccurrences() as $occurance) {
        $descriptors[] = array(
            'id' => $match->getSentence()->getFingerprint(
                $occurance['source']->getFingerPrint() . '-' . $occurance['offset']
            ),
            'sentence' => (string)$match->getSentence()
        );
    }
}



header('Content-type: application/json');
echo json_encode(array(
    'content' => $content,
    'doublons' => $descriptors
));


//=========================================================================




