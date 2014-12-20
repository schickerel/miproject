<?php

namespace ProfessionalCategory
{
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;

    class ProfessionalCategoryController implements ControllerProviderInterface {

        public function connect(Application $app) {
            $factory = $app['controllers_factory'];

            $factory->get(
                '/professionalcategories',
                'ProfessionalCategory\ProfessionalCategoryController::getProfessionalCategories'
            );

            $factory->get(
                '/{id}',
                'ProfessionalCategory\ProfessionalCategoryController::getProfessionalCategoryById'
            );

            return $factory;
        }

        public function getProfessionalCategories(Application $app) {
            $professionalCategories = ProfessionalCategoryQuery::create()
                ->find();

            $professionalCategoriesJson = $professionalCategories->toJSON(true, true);

            return new Response($professionalCategoriesJson, 200, ['Content-Type' => 'application/json']);
        }

        public function getProfessionalCategoryById(Application $app, $id) {
            $professionalCategory = ProfessionalCategoryQuery::create()
                ->findPK($id);

            $professionalCategoryJson = $professionalCategory->toJSON(true, true);

            return new Response($professionalCategoryJson, 200, ['Content-Type' => 'application/json']);
        }
    }
}

?>