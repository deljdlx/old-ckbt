<?php


require(__DIR__ . '/../source/bootstrap.php');

$comparePath = __DIR__ . '/file';


//=======================================================
//upload
if (!empty($_FILES)) {
    $data = reset($_FILES);
    $path = $data['tmp_name'];
    $destination = $comparePath . '/' . $data['name'];
    move_uploaded_file($path, $destination);
}

//=======================================================


//=======================================================
//supression de fichier
if (isset($_GET['delete'])) {
    if (is_file($comparePath . '/' . $_GET['delete'])) {
        unlink($comparePath . '/' . $_GET['delete']);
    }
}
//=======================================================


$comparator = new \CKBT\Comparator();

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

            //echo '<span class="sentence" data-sentence-id="' . $id . '" data-sentence-hash="' . $hash . '">' . $sentence . '</span>';
        }
        $file->rewind();
        echo '</div>';
    }
}

$content = ob_get_clean();

/*
$content = trim($content);
$content = preg_replace("`\n+`s", "\n", $content);
$content = preg_replace("`\n`s", "</p><p>", $content);
$content = '<p>' . $content . '</p>';
*/
/*
echo $content;
die('EXIT '.__FILE__.'@'.__LINE__);
*/

$doublons = $comparator->compareAll();

/*
echo '<pre id="' . __FILE__ . '-' . __LINE__ . '" style="border: solid 1px rgb(255,0,0); background-color:rgb(255,255,255)">';
echo '<div style="background-color:rgba(100,100,100,1); color: rgba(255,255,255,1)">' . __FILE__ . '@' . __LINE__ . '</div>';
print_r($doublons);
echo '</pre>';
die('EXIT '.__FILE__.'@'.__LINE__);
*/

$descriptors = array();


/**
 * @var $doublons \CKBT\Match[]
 */

/*
echo '<pre id="' . __FILE__ . '-' . __LINE__ . '" style="border: solid 1px rgb(255,0,0); background-color:rgb(255,255,255)">';
echo '<div style="background-color:rgba(100,100,100,1); color: rgba(255,255,255,1)">' . __FILE__ . '@' . __LINE__ . '</div>';
print_r($doublons);
echo '</pre>';
die('EXIT '.__FILE__.'@'.__LINE__);

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


/*
foreach ($doublons as $doublon) {


    $descriptors[] = array(
        'sourceId' => $doublon->getSourceSentence()->getFingerprint($doublon->getSource()->getFingerPrint()),
        'compareId' => $doublon->getCompareSentence()->getFingerPrint($doublon->getComparisonFile()->getFingerPrint()),


        'source' => (string)$doublon->getSource(),
        'comparison' => (string)$doublon->getComparisonFile()
    );
}
*/


header('Content-type: application/json');
echo json_encode(array(
    'content' => $content,
    'doublons' => $descriptors
));





