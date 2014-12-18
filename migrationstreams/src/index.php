<?php
require_once __DIR__.'/../../vendor/silex/vendor/autoload.php';
require_once __DIR__.'/../../vendor/propelServiceProvider/src/Propel/Silex/PropelServiceProvider.php';
require_once __DIR__.'/controller/MigrationController.php';
require_once __DIR__.'/controller/PersonController.php';
require_once __DIR__.'/../../vendor/propel/runtime/lib/Propel.php';
Propel::init("/conf/migrationstreams-conf.php");
set_include_path("/model" . PATH_SEPARATOR . get_include_path());

$app = new Silex\Application();

$app['propel.config_file'] = __DIR__.'/conf/migrationstreams-conf.php';
$app['propel.model_path'] = __DIR__.'/model';
$app->register(new Propel\Silex\PropelServiceProvider());

$app->mount('/migrations', new Migration\MigrationController());
$app->mount('/person', new Person\PersonController());

$app ['debug'] = true;

$app->run();


?>