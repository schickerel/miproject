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

			if (array_key_exists('filter', $params)) {
				$filter = $params['filter'];
				if($filter == 'denomination') {
					$personsJson = $this->getPersonsByDenomination($params['denomination']);
				} else if ($filter == 'professionalcategory') {
					$personsJson = $this->getPersonsByProfessionalCategory($params['professionalcategory']);
				}
			} else {
				$persons = PersonQuery::create()
					->find();

				$personsJson = $persons->toJSON(true, true);
			}

			return new Response($personsJson, 200, ['Content-Type' => 'application/json']);
		}

		public function getPersonsByDenomination($denominationId) {
			$persons = PersonQuery::create()
				->filterByDenominationId($denominationId)
				->find();

			$personsJson = $persons->toJSON(true, true);
			return $personsJson;
		}

		public function getPersonsByProfessionalCategory($professionalCategoryId) {
			$persons = PersonQuery::create()
				->filterByProfessionalCategoryId($professionalCategoryId)
				->find();

			$personsJson = $persons->toJSON(true, true);
			return $personsJson;
		}

    }
}

?>