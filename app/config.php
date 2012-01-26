<?php
require 'vendor/php-activerecord/ActiveRecord.php';
ActiveRecord\Config::initialize(function($cfg)
 {
     $cfg->set_model_directory('app/models');
     $cfg->set_connections(array(
         'development' => 'mysql://u:p@localhost/db?charset=utf8',
     	'sqlite' => 'sqlite:/'.getcwd().'/db.sqlite'
     ));
 });
 

require_once('vendor/Slim-Extras/TwigView.php');
TwigView::$twigDirectory = 'vendor/Twig';
TwigView::$twigOptions = array(
    //'cache' => '/cache/twig', uncomment if folder permissions resolved
);
TwigView::$twigExtensions = array(
    'Extension_Twig_Slim',
);