<?php

namespace Migration
{
	use Silex\Application;
	use Silex\ControllerProviderInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\JsonResponse;

	class MigrationController implements ControllerProviderInterface {

		public function connect(Application $app) {
			$factory = $app['controllers_factory'];

			$factory->get(
					'/',
					'Migration\MigrationController::getAllMigrations'
			);

			$factory->get(
					'/{id}',
					'Migration\MigrationController::getMigrationById'
			);

			return $factory;
		}

	 	public function getAllMigrations(Application $app, Request $request) {

			$params = $request->query->all();

			foreach ( $request->attributes as $key => $val ) {
				$params[ $key ] = $val;
				echo ($key);
				echo(" ");
				echo(" ");

			}

        	$migrations = MigrationQuery::create()
        				 ->find();
        	$migrationsJson = $migrations->toJSON();

        	return new Response($migrationsJson, 200, ['Content-Type' => 'application/json']);
        }

        public function getMigrationById(Application $app, $id) {
			$migration = MigrationQuery::create()
						->findPK($id);
			$migrationJson = $migration->toJSON();

		    return  new Response($migrationJson, 200, ['Content-Type' => 'application/json']);
        }

		public function getMigrationsByYear(Application $app, $year) {
			$migrations = MigrationQuery::create()
						->filterByYear($year)
						->find();
			$migrationsJson = $migrations->toJSON();

		    return  new Response($migrationsJson, 200, ['Content-Type' => 'application/json']);
        }

        public function getMigrationsByPeriod(Application $app, $startYear, $endYear) {
			$migrations = MigrationQuery::create()
						->filterByYear(array('min' => $startYear, 'max' => $endYear))
						->find();
			$migrationsJson = $migrations->toJSON();

		    return  new Response($migrationsJson, 200, ['Content-Type' => 'application/json']);
        }

        public function getFirstMigrations(Application $app) {
        	$firstMigrations = array();
			$persons = MigrationQuery::create()
						->groupByPersonId()
						->find();

			foreach ($persons as $person) {
				$firstMigration = MigrationQuery::create()
								->filterByPersonId($person->getPersonId())
								->orderByYear()
								->orderByMonth()
								->findOne();
				echo($firstMigration);
				array_push($firstMigrations, $firstMigration->toJSON());
			}

			$migrationsJson = json_encode($firstMigrations);

		    return  new Response($migrationsJson, 200, ['Content-Type' => 'application/json']);
        }

        public function getMigrationsByCountry(Application $app, $id) {
			$migration = MigrationQuery::create()
						->findPK($id);
			$result = $migration->toJSON();

		    return  new Response($migrationJson, 200, ['Content-Type' => 'application/json']);
        }
    }
}

?>