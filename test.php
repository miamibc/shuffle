<?php

/*
include 'Shuffle.class.php';

$test = new Shuffle('sample-src.jpg');
$test ->shuffle(10)
      ->save('sample-dsc.jpg');
*/

include 'Shuffle.function.php';

shuffle_image('sample-src.jpg',10,'sample-dsc.jpg');