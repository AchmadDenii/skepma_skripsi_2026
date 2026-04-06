<?php

echo "ZipArchive exists: ";
var_dump(class_exists('ZipArchive'));

echo "<br>";

$zip = new ZipArchive();