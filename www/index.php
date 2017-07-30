<?php

require(__DIR__ . '/../source/bootstrap.php');
?>

<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="asset/style.css"/>
</head>

<body>


<div>
    Passer la souris sur les phrases en gras pour surligner leurs ocurrences.
</div>
<div class="container">
    <div id="filedrop" class="">Drop text file here</div>
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




