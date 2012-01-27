<?php
session_start();
require_once('app/config.php');

/**
 * Step 1: Require the Slim PHP 5 Framework
 *
 * If using the default file layout, the `Slim/` directory
 * will already be on your include path. If you move the `Slim/`
 * directory elsewhere, ensure that it is added to your include path
 * or update this file path as needed.
 */


/**
 * Step 2: Instantiate the Slim application
 *
 * Here we instantiate the Slim application with its default settings.
 * However, we could also pass a key-value array of settings.
 * Refer to the online documentation for available settings.
 */

$app = new Slim(array(
	'templates.path' => 'app/views/',
	'debug' => true,
	'view' => new TwigView(),
 	'cookies.secret_key' => 'appsecretkey'
));
$app->setName('appname');

//send the $currentUser to all the templates
//$app->view()->appendData(array('currentUser' => $currentUser));

//authentication
$auth = function () use ($app) {
	if($currentUser instanceof User) {
		$app->config('cookies.user_id', $currentUser->id);
		//$app->view()->appendData(array('currentUser' => $currentUser, 'fapp' => $app));
		//$app->setEncryptedCookie('user_id', $currentUser->id);
		//$app->
		return true; //true if authenticated, false otherwise
	} else {
		$app->redirect($app->urlFor('login'));
	}
};

foreach(glob('app/functions/*.php') as $function) {
	include $function;
}
foreach(glob('app/classes/*.php') as $class) {
	include $class;
}

foreach(glob('app/controllers/*.php') as $router) {
	include $router;
}

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, and `Slim::delete`
 * is an anonymous function. If you are using PHP < 5.3, the
 * second argument should be any variable that returns `true` for
 * `is_callable()`. An example GET route for PHP < 5.3 is:
 *
 * $app = new Slim();
 * $app->get('/hello/:name', 'myFunction');
 * function myFunction($name) { echo "Hello, $name"; }
 *
 * The routes below work with PHP >= 5.3.
 */

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