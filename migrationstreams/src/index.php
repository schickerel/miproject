<?php
require_once __DIR__.'/../../vendor/silex/vendor/autoload.php';
require_once __DIR__.'/../../vendor/propelServiceProvider/src/Propel/Silex/PropelServiceProvider.php';
require_once __DIR__.'/controller/MigrationController.php';
require_once __DIR__.'/controller/PersonController.php';
require_once __DIR__.'/controller/CountryController.php';
require_once __DIR__.'/controller/DenominationController.php';
require_once __DIR__.'/controller/ProfessionalCategoryController.php';
require_once __DIR__.'/../../vendor/propel/runtime/lib/Propel.php';
Propel::init(__DIR__."/conf/migrationstreams-conf.php");
set_include_path("/model" . PATH_SEPARATOR . get_include_path());

$app = new Silex\Application();

$app['propel.config_file'] = __DIR__.'/conf/migrationstreams-conf.php';
$app['propel.model_path'] = __DIR__.'/model';
$app->register(new Propel\Silex\PropelServiceProvider());

$app->mount('/migration', new Migration\MigrationController());
$app->mount('/person', new Person\PersonController());
$app->mount('/country', new Country\CountryController());
$app->mount('/denomination', new Denomination\DenominationController());
$app->mount('/professionalcategory', new ProfessionalCategory\ProfessionalCategoryController());

$app ['debug'] = true;

$app->run();


?>