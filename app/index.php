<?php
require_once('app/config/registry.php');
require_once('app/config/settings.php');

$app = new Slim(array(
	'templates.path' => 'app/views/',
	'debug' => true,
	'view' => new TwigView(),
 	'cookies.secret_key' => 'appsecretkey'
));
$app->setName('appname');


/**
 * Automatic login based on user cookie
 * uncomment when user model has been defined
 */

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
$app->view()->appendData(
		array('currentUser' => $currentUser,
				'app' => $app,
				'rootUri' => $rootUri,
				'assetUri' => $rootUri . '/web',
				'resourceUri' => $resourceUri
));

foreach(glob('app/controllers/*.php') as $router) {
	include $router;
}


//GET route
$app->get('/', function () use ($app) {
    $template = <<<EOT
            <header>
                <a href="http://www.slimframework.com"><img src="logo.png" alt="Slim"/></a>
            </header>
            <h1>Welcome to Slim!</h1>
            <p>
                Congratulations! Your Slim application is running. If this is
                your first time using Slim, start with this <a href="http://www.slimframework.com/learn" target="_blank">"Hello World" Tutorial</a>.
            </p>
            <section>
                <h2>Get Started</h2>
                <ol>
                    <li>The application code is in <code>index.php</code></li>
                    <li>Read the <a href="http://www.slimframework.com/documentation/stable" target="_blank">online documentation</a></li>
                    <li>Follow <a href="http://www.twitter.com/slimphp" target="_blank">@slimphp</a> on Twitter</li>
                </ol>
            </section>
            <section>
                <h2>Slim Framework Community</h2>

                <h3>Support Forum</h3>
                <p>
                    Join the <a href="http://forum.slimframework.com" target="_blank">Slim Framework forum</a>
                    to read announcements, chat with fellow Slim users, ask questions, help others, or show off your cool 
                    Slim Framework apps.
                </p>

                <h3>Twitter</h3>
                <p>
                    Follow <a href="http://www.twitter.com/slimphp" target="_blank">@slimphp</a> on Twitter to receive the very latest news
                    and updates about the framework.
                </p>

                <h3>IRC</h3>
                <p>
                    Find Josh Lockhart in the "##slim" chat room during the day. Say hi, ask questions,
                    or just hang out with fellow Slim users.
                </p>
            </section>
            <section style="padding-bottom: 20px">
                <h2>Slim Framework Extras</h2>
                <p>
                    Custom View classes for Smarty, Twig, Mustache, and other template
                    frameworks are available online in a separate repository.
                </p>
                <p><a href="https://github.com/codeguy/Slim-Extras" target="_blank">Browse the Extras Repository</a></p>
            </section>
EOT;
    $data['template'] = $template;
    $app->render('index.html.twig', $data);
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