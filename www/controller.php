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

            $id = md5($path . ':' . $sentence->getFingerprint());
            echo '<span class="sentence" data-sentence-id="' . $id . '" data-sentence-hash="' . md5(trim($sentence)) . '">' .
                nl2br($sentence) .
                '</span>';
        }
        $file->rewind();
        echo '</div>';
    }
}

$content = ob_get_clean();
$doublons = $comparator->compareAll();


$descriptors = array();

foreach ($doublons as $doublon) {
    $descriptors[] = array(
        'sourceId' => md5(
            $doublon->getSource() . ':' .
            $doublon->getSourceSentence()->getFingerPrint()
        ),
        'compareId' => md5(
            $doublon->getComparisonFile() . ':' .
            $doublon->getCompareSentence()->getFingerPrint()
        ),
        'source' => (string)$doublon->getSource(),
        'comparison' => (string)$doublon->getComparisonFile()
    );
}


header('Content-type: application/json');
echo json_encode(array(
    'content' => $content,
    'doublons' => $descriptors
));





