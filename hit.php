<?php
$counter = file_get_contents('./counter.txt') + 1;
file_put_contents('./counter.txt', $counter);