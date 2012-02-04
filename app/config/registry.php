<?php
require_once ROOT. '/vendor/php-activerecord/ActiveRecord.php';
require_once 'vendor/Slim-Extras/TwigView.php';

foreach(glob('app/functions/*.php') as $function) {
	include $function;
}
foreach(glob('app/classes/*.php') as $class) {
	include $class;
}