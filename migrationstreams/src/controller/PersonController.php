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

		public function getPersonById(Application $app, $id) {
			$person = PersonQuery::create()
				->findPK($id);
			$personJson = $person->toJSON(true, true);

			return  new Response($personJson, 200, ['Content-Type' => 'application/json']);
		}

		public function getPersons(Application $app, Request $request) {
			$personsJson = null;
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
				$persons = $this ->filterPersonsByReturnMigration($persons);
			}
			$persons = $persons ->find();

			return new Response($persons, 200, ['Content-Type' => 'application/json']);
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
    }
}

?>