<?php
include './vendor/autoload.php';

use Hello\SayThanks;

SayThanks::sayhello();

print_r(get_included_files());
