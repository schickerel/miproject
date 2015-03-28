<?php

namespace Denomination
{
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;

    class DenominationController implements ControllerProviderInterface {

        public function connect(Application $app) {
            $factory = $app['controllers_factory'];

            $factory->get(
                '/denominations',
                'Denomination\DenominationController::getDenominations'
            );

            $factory->get(
                '/{id}',
                'Denomination\DenominationController::getDenominationById'
            );

            return $factory;
        }

        public function getDenominations() {
            $denominations = DenominationQuery::create()
                ->find();

            $denominationsJson = $denominations->toJSON(true, true);

            return new Response($denominationsJson, 200, ['Content-Type' => 'application/json']);
        }

        public function getDenominationById($id) {
            $denomination = DenominationQuery::create()
                ->findPK($id);

            $denominationJson = $denomination->toJSON(true, true);

            return new Response($denominationJson, 200, ['Content-Type' => 'application/json']);
        }
    }
}

?>