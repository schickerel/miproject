<?php

namespace Person
{
	use Silex\Application;
	use Silex\ControllerProviderInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;

	class PersonController implements ControllerProviderInterface {

		public function connect(Application $app) {
			$factory = $app['controllers_factory'];

			$factory->get(
				'/persons',
				'Person\PersonController::getPersons'
			);

			$factory->get(
				'/{id}',
				'Person\PersonController::getPersonById'
			);

			return $factory;
		}

		public function getPersonById($id) {
			$person = PersonQuery::create()
				->findPK($id);
			$personJson = $person->toJSON(true, true);

			return  new Response($personJson, 200, ['Content-Type' => 'application/json']);
		}

		public function getPersons(Request $request) {
			$params = $request->query->all();
			$persons = PersonQuery::create();

			if (array_key_exists('denomination', $params)) {
				$denominationIds = $params['denomination'];
				$persons = $this ->filterPersonsByDenominationId($persons, $denominationIds);
			} 
			if (array_key_exists('professionalCategory', $params)) {
				$professionalCategoryIds = $params['professionalCategory'];
				$persons = $this->filterPersonsByProfessionalCategoryId($persons, $professionalCategoryIds);
			}
			if (array_key_exists('returnMigration', $params)) {
				if($params['returnMigration'] === 'true') {
					$persons = $this->filterPersonsByReturnMigration($persons);
				}
			}
			if (array_key_exists('age', $params)) {
				$ageRange = $params['age'];
				$persons = $this ->filterPersonsByAge($persons, $ageRange);
			}
			if (array_key_exists('year', $params)) {
				$year = $params['year'];
				$persons = $this ->filterPersonsByYear($persons, $year);
			}
			$persons = $persons ->find();

			$personsJson = $persons->toJSON(true, true);
			return new Response($personsJson, 200, ['Content-Type' => 'application/json']);
		}

		public function filterPersonsByDenominationId($persons, $denominationIds) {
			if (strpos($denominationIds, ';') !== FALSE) {
				$denominationIds = explode(";", $denominationIds);
			}
			$filteredPersons = $persons ->filterByDenominationId($denominationIds);
			return $filteredPersons;
		}

		public function filterPersonsByProfessionalCategoryId($persons, $professionalCategoryIds) {
			if (strpos($professionalCategoryIds, ';') !== FALSE) {
				$professionalCategoryIds = explode(";", $professionalCategoryIds);
			}
			$filteredPersons = $persons ->filterByProfessionalCategoryId($professionalCategoryIds);
			return $filteredPersons;
		}

		public function	 filterPersonsByReturnMigration($persons) {
			$filteredPersons = $persons->useMigrationQuery()
					->filterByCountryId('7')
				->endUse();
			return $filteredPersons;
		}

		public function filterPersonsByAge($persons, $ageRange) {
			$ageRange = explode("-", $ageRange);
			$ages = array();
			$personsList = $persons->find();
			foreach($personsList as $person) {
				$birthday = $person->getBirthday('Y');
				$migrations = $person->getMigrations();
				$minYear = $migrations[0]->getYear();
				foreach ($migrations as $migration) {
					$year = $migration->getYear();
					if($minYear > $year) {
						$minYear = $year;
					}
				}
				$age = $minYear-$birthday;
				if($age >= $ageRange[0] && $age <= $ageRange[1]) {
					array_push($ages, $person->getId());
				}
			}
			$filterPersons = $persons ->filterById($ages);
			return $filterPersons;
		}

		public function filterPersonsByYear($persons, $year) {
			$ages = array();
			$personsList = $persons->find();
			foreach($personsList as $person) {
				$migrations = $person->getMigrations();
				$minYear = $migrations[0]->getYear();
				foreach ($migrations as $migration) {
					$migrationYear = $migration->getYear();
					if($minYear > $migrationYear) {
						$minYear = $migrationYear;
					}
				}

				if($minYear == $year) {
					echo "MinYear: ";
					echo($minYear);
					array_push($ages, $person->getId());
				}
			}
			$filterPersons = $persons ->filterById($ages);
			return $filterPersons;
		}
    }
}

?>