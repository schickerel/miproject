<?php

namespace Person
{
	use Silex\Application;
	use Silex\ControllerProviderInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\JsonResponse;

	class PersonController implements ControllerProviderInterface {

		public function connect(Application $app) {
			$factory = $app['controllers_factory'];

			$factory->get(
					'',
					''
			);

			$factory->get(
					'',
					''
			);

			return $factory;
		}

	 	public function getFirst(Application $app) {
        	return null;
        }

        public function getById(Application $app, $id) {
        	$author = new \Bookstore\AuthorQuery();
			$author = AuthorQuery::create()->findPK($id);
			$result = $author->toJSON();

		    return  new Response($result, 200, ['Content-Type' => 'application/json']);
        }

		public function getByYear(Application $app, $id) {
        	$author = new \Bookstore\AuthorQuery();
			$author = AuthorQuery::create()->findPK($id);
			$result = $author->toJSON();

		    return  new Response($result, 200, ['Content-Type' => 'application/json']);
        }
    }
}

?>