<?php
require ROOT.'/app/config/registry.php';
\Slim\Slim::registerAutoloader();
require ROOT.'/app/config/appconfig.php';

$app = new \Slim\Slim(array(
	'templates.path' => ROOT.'/app/views/',
	'debug' => false,
	'view' => new \Slim\Extras\Views\Twig(),
 	'cookies.secret_key' => md5('appsecretkey'),

	'log.enabled'    => false,
	'log.writer' => new \Slim\Extras\Log\DateTimeFileWriter(array(
				'path' => './logs',
				'name_format' => 'Y-m-d',
				'message_format' => '%label% - %date% - %message%'
		))
));
$app->setName('appname');


/**
 * Automatic login based on user cookie
 * uncomment when user model has been defined
 */
$currentUser = null;
if($userid = $app->getEncryptedCookie('user_id')) {
	/*if(User::exists($userid)) {
		$currentUser = User::find($userid);
	} else {
		$currentUser = null;
	}
	*/

} else {
	$currentUser = null;
}


/**
 * authentication middleware for is in routes you want protected
 * 
 */
//authentication
$auth = function () use ($app, $currentUser) {
	if($currentUser instanceof User) {
		$app->config('cookies.user_id', $currentUser->id);
		$app->view()->appendData(array('currentUser' => $currentUser, 'app' => $app));
		$app->setEncryptedCookie('user_id', $currentUser->id, "+ 30 day");
		//$app->
		return true; //true if authenticated, false otherwise
	} else {
		//uncomment if redirect
		//$app->redirect($app->urlFor('login'));
	}
};


/*
 * SET some globally available view data
 */
$resourceUri = $_SERVER['REQUEST_URI'];
$rootUri = $app->request()->getRootUri();
$assetUri = $rootUri;
$app->view()->appendData(
		array('currentUser' => $currentUser,
				'app' => $app,
				'rootUri' => $rootUri,
				'assetUri' => $assetUri,
				'resourceUri' => $resourceUri
));

foreach(glob(ROOT.'/app/controllers/*.php') as $router) {
	include $router;
}

$app->get('/', function () use ($app) {
	
    $app->render('slim.html.twig');
});

$app->run();