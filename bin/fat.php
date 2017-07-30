<?php

$content=file_get_contents(__DIR__.'/lorem0.txt');

for($i=0; $i<10000; $i++) {
    file_put_contents(__DIR__.'/fat0.txt', $content."\n", FILE_APPEND);
}



for($i=0; $i<10000; $i++) {
    file_put_contents(__DIR__.'/fat1.txt', $content."\n", FILE_APPEND);
}