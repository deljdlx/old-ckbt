<?php

require(__DIR__ . '/../source/bootstrap.php');
?>

<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="style.css"/>
</head>

<body>



<div class="container">
    <div id="filedrop" class="">Drop text file here</div>
</body>
</div>







<div id="files"></div>



<script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>




<script src="filedrop.js"></script>


<script>



    function render(data) {



        $('#files').html(data.content);

        $('.delete').click(function() {
            var file=this.getAttribute('data-file');

            $.ajax({
                type: "GET",
                url: 'controller.php?delete='+file,
                success: function(data) {
                    render(data);
                }
            });

        });


        var doublons=data.doublons;

        for (var i = 0; i < doublons.length; i++) {
            var descriptor = doublons[i];

            var sourceSentenceId = descriptor['sourceId'];
            var compareSentenceId = descriptor['compareId'];

            $('span[data-sentence-id="'+sourceSentenceId+'"]').addClass('doublon');
            $('span[data-sentence-id="'+compareSentenceId+'"]').addClass('doublon');

        }


        $('.doublon').hover(function() {
            var sentenceHash=this.getAttribute('data-sentence-hash');
            $('.sentence').addClass('blur');
            $('span[data-sentence-hash="'+sentenceHash+'"]').addClass('hightlight');

        }, function() {
            $('.sentence').removeClass('hightlight');
            $('.sentence').removeClass('blur');
        });
    }





    var dropZone=document.getElementById('filedrop');
    var uploadURL='controller.php';



    var drop =new FileDrop(dropZone, uploadURL, {
        success: function(data) {
            if(data) {
                var data=JSON.parse(data);
            }
            if(!data.error) {
                render(data);
            }
        }
    });

    $.ajax({
        type: "GET",
        url: 'controller.php',
        success: function(data) {
            render(data);
        }
    });


</script>


</body>

</html>




