<?php
/**
 * ACTIVERECORD SETTINGS
 */
ActiveRecord\Config::initialize(function($cfg)
{
	$models = 	ROOT.'/app/models';
	$cfg->set_model_directory($models);
	try {
		$cfg->set_connections(array(
			'development' => 'mysql://user:pass@localhost/dbname?charset=utf8'

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


\Slim\Extras\Views\Twig::$twigDirectory = ROOT.'/vendor/Twig/lib/Twig';
\Slim\Extras\Views\Twig::$twigOptions = array(
		"debug" => true
);
if(is_writable(ROOT . '/cache/twig')) {
	\Slim\Extras\Views\Twig::$twigOptions['cache'] = ROOT . '/cache/twig'; 
}
	
	
\Slim\Extras\Views\Twig::$twigExtensions = array(
		'Twig_Extensions_Slim',
		'Twig_Extension_Debug',
		'Twig_Extensions_Markdown'
);

\Slim\Extras\Views\Twig::$twigFunctions =  array(
		
);