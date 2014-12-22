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
			if ($migration == null) {
				$app->error(function (\Exception $e, $code) {
					return new Response('We are sorry, but something went terribly wrong.');
				});
			} else {
				$migrationJson = $migration->toJSON(true, true);
				return  new Response($migrationJson, 200, ['Content-Type' => 'application/json']);
			}

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
			$centreOfLifeMigrations = array();
			$migrationsByPersonIds = MigrationQuery::create()
				->groupByPersonId()
				->find();
			foreach ($migrationsByPersonIds as $migrationByPersonIds) {
				$migrationId = 0;
				$migrationDuration = 0;
				$maxMigrationId = 0;
				$maxMigrationDuration = 0;
				$person = $migrationByPersonIds->getPerson();
				$personId = $migrationByPersonIds->getPersonId();
				$personYear = date("Y");
				$personDayOfDeath = $person->getDayOfDeath();
				$migrationsByPerson = MigrationQuery::create()
					->filterByPersonId($personId)
					->orderByYear()
					->orderByMonth()
					->find();
				$arrayObject = new \ArrayObject($migrationsByPerson);
				$iterator = $arrayObject->getIterator();

				if($personDayOfDeath != "") {
					$personYear = date("Y" ,strtotime($personDayOfDeath));
				}

				while($iterator->valid()) {
					$currentElement = $iterator->current();
					$currentYear = $currentElement->getYear();

					$iterator->next();
					if($iterator->valid()) {
						$nextElement = $iterator->current();
						$nextYear = $nextElement->getYear();

						if($startYear <= $currentYear) {
							$migrationId = $currentElement->getId();
							$migrationDuration = $nextYear-$currentYear;
						} else if ($startYear > $currentYear && $startYear < $nextYear) {
							$migrationId = $currentElement->getId();
							$migrationDuration = $nextYear-$startYear;
						}
						if($endYear < $nextYear) {
							$migrationId = $currentElement->getId();
							$migrationDuration = $endYear-$currentYear;
						}
					} else {
						if($currentYear < $endYear){
							$migrationId = $currentElement->getId();
							if($personYear < $endYear) {
								$migrationDuration = $personYear-$currentYear;
							} else {
								$migrationDuration = $endYear-$currentYear;
							}

						}
					}
					if($migrationDuration >= $maxMigrationDuration){
						$maxMigrationId = $migrationId;
						$maxMigrationDuration = $migrationDuration;
					}
				}
				$centreOfLifeMigration = MigrationQuery::create()->findPk($maxMigrationId);
				array_push($centreOfLifeMigrations, $centreOfLifeMigration->toArray());
			}

			$migrationsJson = json_encode($centreOfLifeMigrations);
			return $migrationsJson;
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