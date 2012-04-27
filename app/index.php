<?php
require ROOT.'/vendor/Slim/Slim.php';
require ROOT.'/app/config/registry.php';
require ROOT.'/app/config/appconfig.php';

$app = new Slim(array(
	'templates.path' => ROOT.'/app/views/',
	'debug' => true,
	'view' => new TwigView(),
 	'cookies.secret_key' => md5('appsecretkey')
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


//GET route
$app->get('/', function () use ($app) {
	
    $app->render('slim.html.twig');
});



//POST route
$app->post('/post', function () {
    echo 'This is a POST route';
});

//PUT route
$app->put('/put', function () {
    echo 'This is a PUT route';
});

//DELETE route
$app->delete('/delete', function () {
    echo 'This is a DELETE route';
});

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This is responsible for executing
 * the Slim application using the settings and routes defined above.
 */
$app->run();