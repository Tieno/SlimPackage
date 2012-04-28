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
			'development' => 'mysql://root:durnez10@localhost/comm_sen?charset=utf8'

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

TwigView::$twigDirectory = ROOT.'/vendor/Twig/lib/Twig';
TwigView::$twigOptions = array(
		"debug" => true
);
if(is_writable(ROOT . '/cache/twig')) {
	TwigView::$twigOptions['cache'] = ROOT . '/cache/twig'; 
}
	
	
TwigView::$twigExtensions = array(
		'Twig_Extensions_Slim',
		'Twig_Extension_Debug',
		'Twig_Extensions_Markdown'
);

TwigView::$twigFunctions =  array(
		
);