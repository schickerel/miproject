<?php

namespace Country
{
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;

    class CountryController implements ControllerProviderInterface {

        public function connect(Application $app) {
            $factory = $app['controllers_factory'];
            $factory->get(
                '/countries',
                'Country\CountryController::getCountries'
            );

            $factory->get(
                '/{id}',
                'Country\CountryController::getCountryById'
            );

            return $factory;
        }

        public function getCountries(Application $app) {
            $countries = CountryQuery::create()
                ->find();

            $countriesJson = $countries->toJSON(true, true);

            return new Response($countriesJson, 200, ['Content-Type' => 'application/json']);
        }

        public function getCountryById(Application $app, $id) {
            $country = CountryQuery::create()
                ->findPK($id);

            $countryJson = $country->toJSON(true, true);

            return new Response($countryJson, 200, ['Content-Type' => 'application/json']);
        }
    }
}

?>