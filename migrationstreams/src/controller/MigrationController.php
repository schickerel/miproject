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
					$migrationsJson = $this->getOverview($params);
				} else if($filter == 'firstMigration') {
					$migrationsJson = $this->getFirstMigrations($params);
				} else if ($filter == 'targetCountryMigration') {
					$migrationsJson = $this->getTargetCountryMigrations($params);
				} else if ($filter == 'distributionByCountries') {
					$migrationsJson = $this->getDistributionByCountries($params);
				}

			} else if (array_key_exists('personId', $params)) {
				$migrationsJson = $this->getMigrationsByPersonId($params['personId']);
			} else {
				$migrationCountries = array();
				$migrations = MigrationQuery::create()
					->orderByCountryId()
					->groupByCountryId()
					->find();
				foreach ($migrations as $migration) {
					$country = $migration->getCountry()->getCode();
					array_push($migrationCountries,$country);
				}
				$migrationsJson = json_encode($migrationCountries);
			}

			return new Response($migrationsJson, 200, ['Content-Type' => 'application/json']);
        }

		public function getOverview($params) {
			$migrationsYearAmount = array();
			$personIds = $this->getPersonIds($params);
			foreach($personIds as $personId) {
				$personMigration = MigrationQuery::create()
					->filterByPersonId($personId)
					->orderByYear()
					->orderByMonth()
					->findOne();
				$year = $personMigration->getYear();
				$amount = 1;
				if(empty($migrationsYearAmount)){
					$migration = array("year"=>$year, "amount"=>$amount);
					array_push($migrationsYearAmount, $migration);
				} else {
					$exists = false;
					foreach($migrationsYearAmount as $key => $value){
						if($migrationsYearAmount[$key]["year"] === $year) {
							$currentAmount = $migrationsYearAmount[$key]["amount"];
							$currentAmount++;
							$migrationsYearAmount[$key]["amount"] = $currentAmount;
							$exists = true;
						}
					}
					if(!$exists){
						$migration = array("year"=>$year, "amount"=>$amount);
						array_push($migrationsYearAmount, $migration);
					}
				}
			}
			$migrationsJson = json_encode($migrationsYearAmount);
			return $migrationsJson;
		}

		public function getFirstMigrations($params) {
			$migrationsCountryAmount = array();
			$personIds = $this->getPersonIds($params);
			foreach ($personIds as $personId) {
				$personMigration = MigrationQuery::create()
					->filterByPersonId($personId)
					->orderByYear()
					->orderByMonth()
					->findOne();
				$country = $personMigration->getCountry()->getCode();
				$amount = 1;
				if(empty($migrationsCountryAmount)){
					$migrationToCountry = array("country"=>$country, "amount"=>$amount);
					array_push($migrationsCountryAmount, $migrationToCountry);
				} else {
					$exists = false;
					foreach($migrationsCountryAmount as $key => $value){
						if($migrationsCountryAmount[$key]["country"] === $country) {
							$currentAmount = $migrationsCountryAmount[$key]["amount"];
							$currentAmount++;
							$migrationsCountryAmount[$key]["amount"] = $currentAmount;
							$exists = true;
						}
					}
					if(!$exists){
						$migrationToCountry = array("country"=>$country, "amount"=>$amount);
						array_push($migrationsCountryAmount, $migrationToCountry);
					}
				}
			}
			$migrationsJson = json_encode($migrationsCountryAmount);
			return  $migrationsJson;
		}

		public function getTargetCountryMigrations($params) {
			$startYear = 1933;
			$endYear = 1945;
			$targetCountries = array();
			$personIds = $this->getPersonIds($params);
			foreach($personIds as $personId) {
				$personMigrations = MigrationQuery::create()
					->filterByPersonId($personId)
					->filterByYear(array('max' => $endYear))
					->orderByYear()
					->orderByMonth()
					->find();
				$numberOfMigrations = $personMigrations->count();
				$migrationCount = 1;
				$year = $personMigrations[0]->getYear();
				$countryId = $personMigrations[0]->getCountry()->getCode();
				$longestPeriod = 0;
				$longestCountry = 0;
				foreach($personMigrations as $personMigration) {
					$lastYear = $year;
					$year = $personMigration->getYear();
					$lastCountryId = $countryId;
					$countryId = $personMigration->getCountry()->getCode();
					$period = 0;
					$country = 0;
					if($year !== $lastYear) {
						if ($lastYear < $startYear && $year > $startYear) {
							$period = $year - $startYear;
							$country = $lastCountryId;
						} else if ($lastYear >= $startYear) {
							$period = $year - $lastYear;
							$country = $lastCountryId;
						}
						if($period > $longestPeriod) {
							$longestPeriod = $period;
							$longestCountry = $country;
						}
					}
					if($migrationCount === $numberOfMigrations && $year !== $endYear) {
						if($year < $startYear){
							$period = $endYear - $startYear;
							$country = $countryId;
						} else {
							$period = $endYear - $year;
							$country = $countryId;
						}
						if($period > $longestPeriod) {
							$longestPeriod = $period;
							$longestCountry = $country;
						}
					}
					++$migrationCount;
				}
				$amount = 1;
				if(empty($targetCountries)){
					$targetCountry = array("country"=>$longestCountry, "amount"=>$amount);
					array_push($targetCountries, $targetCountry);
				} else {
					$exists = false;
					foreach($targetCountries as $key => $value){
						if($targetCountries[$key]["country"] === $longestCountry) {
							$currentAmount = $targetCountries[$key]["amount"];
							$currentAmount++;
							$targetCountries[$key]["amount"] = $currentAmount;
							$exists = true;
						}
					}
					if(!$exists){
						$targetCountry = array("country"=>$longestCountry, "amount"=>$amount);
						array_push($targetCountries, $targetCountry);
					}
				}
			}
			$migrationsJson = json_encode($targetCountries);
			return  $migrationsJson;
		}

		public function getDistributionByCountries($params) {
			$year = $params['year'];
			$month = $params['month'];
			$countryDistributions = array();
			$personIds = $this->getPersonIds($params);
			foreach($personIds as $personId) {
				$amount = 1;
				$personMigrations = MigrationQuery::create()
					->filterByPersonId($personId)
					->condition('condYearEqual', 'migrations.year = ?', $year)
					->condition('condMonth', 'migrations.month <= ?', $month)
					->combine(array('condYearEqual', 'condMonth'), 'and', 'condMonthYear')
					->condition('condYearSmaller', 'migrations.year < ?', $year)
					->where(array('condMonthYear', 'condYearSmaller'), 'or')
					->orderByYear('desc')
					->orderByMonth('desc')
					->find();
				if($personMigrations->count() != 0) {
					$country = $personMigrations[0]->getCountry()->getCode();
				} else {
					$person = MigrationQuery::create()
						->filterByPersonId($personId)
						->findOne()
						->getPerson();
					$country = $person->getCountry()->getCode();
				}

				if(empty($countryDistributions)){
					$countryDistribution = array("country"=>$country, "amount"=>$amount);
					array_push($countryDistributions, $countryDistribution);
				} else {
					$exists = false;
					foreach($countryDistributions as $key => $value){
						if($countryDistributions[$key]["country"] === $country) {
							$currentAmount = $countryDistributions[$key]["amount"];
							$currentAmount++;
							$countryDistributions[$key]["amount"] = $currentAmount;
							$exists = true;
						}
					}
					if(!$exists){
						$countryDistribution = array("country"=>$country, "amount"=>$amount);
						array_push($countryDistributions, $countryDistribution);
					}
				}
			}
			$migrationsJson = json_encode($countryDistributions);
			return  $migrationsJson;
		}

		public function getMigrationsByPersonId($personId){
			$migrations = MigrationQuery::create()
				->filterByPersonId($personId)
				->find();
			return $migrations->toJSON(true, true);
		}

		private function getPersonIds($params) {
			$personIds = array();
			$returnMigration = 'false';
			$personMigrations = MigrationQuery::create()
				->groupByPersonId();
			if(array_key_exists('denomination', $params)) {
				$denominationId = $params['denomination'];
				$personMigrations = $personMigrations
					->usePersonQuery()
						->filterByDenominationId($denominationId)
					->endUse();
			}
			if(array_key_exists('professionalCategory', $params)) {
				$professionalCategoryId = $params['professionalCategory'];
				$personMigrations = $personMigrations
					->usePersonQuery()
					->filterByProfessionalCategoryId($professionalCategoryId)
					->endUse();
			}
			if(array_key_exists('returnMigration', $params)) {
				$returnMigration = $params['returnMigration'];
			}
			$filteredPersonMigrations = $personMigrations->find();

			foreach ($filteredPersonMigrations as $filteredPersonMigration) {
				$personId = $filteredPersonMigration->getPersonId();
				if($returnMigration === 'true'){
					$migrationsOfPerson = MigrationQuery::create()
						->filterByPersonId($personId)
						->filterByCountryId(7)
						->find();
					if($migrationsOfPerson->count() !== 0){
						array_push($personIds, $personId);
					}
				} else {
					array_push($personIds, $personId);
				}
			}
			return $personIds;
		}

    }
}

?>