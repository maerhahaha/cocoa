<?php
include './vendor/autoload.php';
echo 123;echo 'test';echo 'yyy';echo 123123;
use Hello\SayThanks;

SayThanks::sayhello();

print_r(get_included_files());
