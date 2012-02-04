<?php


ActiveRecord\Config::initialize(function($cfg)
{
	$models = 	'app/models';
	$cfg->set_model_directory($models);
	try {
		$cfg->set_connections(array(
			'development' => 'mysql://user:password@localhost/database?charset=utf8'

		));

	}  catch (ActiveRecord\DatabaseException $e) {
		echo "Database error";
	} catch (ActiveRecord\ConfigException $e) {
		echo "Config error";
	}

});
ActiveRecord\DateTime::$DEFAULT_FORMAT = 'd-M-Y';


/**
 * TWIG SETTINGS
 */

TwigView::$twigDirectory = 'vendor/Twig';
TwigView::$twigOptions = array(
		"debug" => true
		//,'cache' => ROOT . '/cache/twig' // uncomment if folder permissions resolved
);
TwigView::$twigExtensions = array(
		'Extension_Twig_Slim',
		'Twig_Extension_Debug'
);
TwigView::$twigFunctions =  array(
		
);