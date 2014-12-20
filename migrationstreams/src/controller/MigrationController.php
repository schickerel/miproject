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
				'/migrations',
				'Migration\MigrationController::getMigrations'
			);

			$factory->get(
				'/{id}',
				'Migration\MigrationController::getMigrationById'
			);

			return $factory;
		}

		public function getMigrationById(Application $app, $id) {
			$migration = MigrationQuery::create()
				->findPK($id);
			$migrationJson = $migration->toJSON(true, true);

			return  new Response($migrationJson, 200, ['Content-Type' => 'application/json']);
		}

	 	public function getMigrations(Application $app, Request $request) {
			$migrationsJson = null;
			$params = $request->query->all();

			if (array_key_exists('filter', $params)) {
				$filter = $params['filter'];

				if($filter == 'year') {
					$migrationsJson = $this->getMigrationsByYear($params['year']);
				} else if($filter == 'period') {
					$migrationsJson = $this->getMigrationsByPeriod($params['start'], $params['end']);
				}  else if($filter == 'first') {
					$migrationsJson = $this->getFirstMigrations();
				}  else if($filter == 'last') {
					$migrationsJson = $this->getLastMigrations();
				}  else if($filter == 'centreoflife') {
					$migrationsJson = $this->getCentreOfLifeMigrations($params['start'], $params['end']);
				}  else if($filter == 'country') {
					$migrationsJson = $this->getMigrationsByCountryId($params['country']);
				} else if($filter == 'person') {
					$migrationsJson = $this->getMigrationsByPersonId($params['country']);
				}
			} else {
				$migrations = MigrationQuery::create()
					->find();
				$migrationsJson = $migrations->toJSON(true, true);
			}

			return new Response($migrationsJson, 200, ['Content-Type' => 'application/json']);
        }

		public function getMigrationsByYear($year) {
			$migrations = MigrationQuery::create()
						->filterByYear($year)
						->find();

			$migrationsJson = $migrations->toJSON;
		    return  $migrationsJson;
        }

        public function getMigrationsByPeriod($startYear, $endYear) {
			$migrations = MigrationQuery::create()
						->filterByYear(array('min' => $startYear, 'max' => $endYear))
						->find();
			$migrationsJson = $migrations->toJSON(true, true);
		    return $migrationsJson;
        }

        public function getFirstMigrations() {
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
				array_push($firstMigrations, $firstMigration->toArray());
			}
			$migrationsJson = json_encode($firstMigrations);
		    return $migrationsJson;
        }

		public function getLastMigrations() {
			$lastMigrations = array();
			$persons = MigrationQuery::create()
				->groupByPersonId()
				->find();

			foreach ($persons as $person) {
				$lastMigration = MigrationQuery::create()
					->filterByPersonId($person->getPersonId())
					->orderByYear('desc')
					->orderByMonth('desc')
					->findOne();
				array_push($lastMigrations, $lastMigration->toArray());
			}
			$migrationsJson = json_encode($lastMigrations);
			return $migrationsJson;
		}

		public function getCentreOfLifeMigrations($startYear, $endYear) {
			$persons = MigrationQuery::create()
				->groupByPersonId()
				->find();
			foreach ($persons as $person) {
				$centreOfLifeMigrations = MigrationQuery::create()
					->filterByPersonId($person->getPersonId())
					->filterByYear(array('min' => $startYear, 'max' => $endYear))
					->orderByYear()
					->orderByMonth()
					->find();

				$arrayObject = new ArrayObject($centreOfLifeMigrations);
				$iterator = $arrayObject->getIterator();

				while($iterator->valid()) {
					echo $iterator->key() . ' => ' . $iterator->current();
				}

			}

			//$migrationsJson = json_encode($lastMigration);
			return null;
		}

        public function getMigrationsByCountryId($countryId) {
			$migrations = MigrationQuery::create()
						->filterByCountryId($countryId)
						->find();

			$migrationsJson = $migrations->toJSON(true, true);
		    return $migrationsJson;
        }

		public function getMigrationsByPersonId($personId) {
			$migrations = MigrationQuery::create()
				->filterByPersonId($personId)
				->find();

			$migrationsJson = $migrations->toJSON(true, true);
			return $migrationsJson;
		}
    }
}

?>