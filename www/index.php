<?php

require(__DIR__ . '/../source/bootstrap.php');
header('Content-type: text/html; charset="utf-8"');
?>

<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="asset/style.css"/>
</head>

<body>



<div class="container">
    <div id="filedrop" class="">
        Glisser déposer un fichier texte ici<hr/>
        Passer la souris sur les phrases en gras pour surligner leurs ocurrences.

    </div>
</body>
</div>







<div id="files"></div>



<script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>



<script src="asset/Application.js"></script>
<script src="asset/Filedrop.js"></script>



<script>
var application=new Application();
application.run();
</script>


</body>

</html>




