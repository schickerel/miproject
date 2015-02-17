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

				if($filter == 'overview') {
					$migrationsJson = $this->getOverview();
				} else if($filter == 'firstMigration') {
					$migrationsJson = $this->getFirstMigrations();
				} else if ($filter == 'lastMigration') {
					$migrationsJson = $this->getLastMigrations();
				} else if ($filter == 'distributionByCountries') {
					$migrationsJson = $this->getDistributionByCountries($params['year']);
				}

				/*if($filter == 'year') {
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
				}*/
			} else {
				$migrationCountries = array();
				$migrations = MigrationQuery::create()
					->orderByCountryId()
					->groupByCountryId()
					->find();
				foreach ($migrations as $migration) {
					$country = $migration->getCountry();
					array_push($migrationCountries,$country->getCountry());
				}
				$migrationsJson = json_encode($migrationCountries);
			}

			return new Response($migrationsJson, 200, ['Content-Type' => 'application/json']);
        }

		public function getOverview() {
			$migrationYear = array();
			$personIds = array();
			$migrations = MigrationQuery::create()
				->groupByPersonId()
				->find();
			foreach ($migrations as $migration) {
				array_push($personIds, $migration->getPersonId());
			}
			foreach($personIds as $personId) {
				$personMigration = MigrationQuery::create()
					->filterByPersonId($personId)
					->orderByYear()
					->orderByMonth()
					->findOne();
				$year = $personMigration->getYear();
				$count = 1;
				if(array_key_exists($year,$migrationYear)){
					$currentCount = $migrationYear[$year];
					$currentCount++;
					$migrationYear[$year] = $currentCount;
				} else {
					$migrationYear[$year] = $count;
				}
			}
			$migrationsJson = json_encode($migrationYear);
			return  $migrationsJson;
		}

		public function getFirstMigrations() {
			$migrationYear = array();
			$personIds = array();
			$migrations = MigrationQuery::create()
				->groupByPersonId()
				->find();
			foreach ($migrations as $migration) {
				array_push($personIds, $migration->getPersonId());
			}
			foreach ($personIds as $personId) {
				$personMigration = MigrationQuery::create()
					->filterByPersonId($personId)
					->orderByYear()
					->orderByMonth()
					->findOne();
				$country = $personMigration->getCountry()->getId();
				$count = 1;
				if(array_key_exists($country, $migrationYear)) {
					$currentCount = $migrationYear[$country];
					$currentCount++;
					$migrationYear[$country] = $currentCount;
				} else {
					$migrationYear[$country] = $count;
				}
			}
			$migrationsJson = json_encode($migrationYear);
			return  $migrationsJson;
		}

		public function getLastMigrations() {
			$startYear = 1933;
			$endYear = 1945;
			$longestStay = array();
			$personIds = array();
			$migrations = MigrationQuery::create()
				->groupByPersonId()
				->find();
			foreach ($migrations as $migration) {
				array_push($personIds, $migration->getPersonId());
			}
			foreach($personIds as $personId) {
				$personMigrations = MigrationQuery::create()
					->filterByPersonId($personId)
					->filterByYear(array('max' => $endYear))
					->orderByYear()
					->orderByMonth()
					->find();
			}
		}

		public function getDistributionByCountries($year) {
			$personIds = array();
			$countryDistribution = array();
			$migrations = MigrationQuery::create()
				->groupByPersonId()
				->find();
			foreach ($migrations as $migration) {
				array_push($personIds, $migration->getPersonId());
			}
			foreach($personIds as $personId) {
				$count = 1;
				$personMigrations = MigrationQuery::create()
					->filterByPersonId($personId)
					->filterByYear(array('max' => $year))
					->orderByYear('desc')
					->orderByMonth('desc')
					->find();
				if($personMigrations->count() == 0) {
					$person = MigrationQuery::create()
						->filterByPersonId($personId)
						->findOne()
						->getPerson();
					$country = $person->getCountry()->getId();
					if(array_key_exists($country, $countryDistribution)) {
						$currentCount = $countryDistribution[$country];
						$currentCount++;
						$countryDistribution[$country] = $currentCount;
					} else {
						$countryDistribution[$country] = $count;
					}
				} else {
					$country = $personMigrations[0]->getCountryId();

					if(array_key_exists($country, $countryDistribution)) {
						$currentCount = $countryDistribution[$country];
						$currentCount++;
						$countryDistribution[$country] = $currentCount;
					} else {
						$countryDistribution[$country] = $count;
					}
				}
			}
			$migrationsJson = json_encode($countryDistribution);
			return  $migrationsJson;
		}


    }
}

?>