<?php

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

use Zend\Http\Response;

class MovieReturnApiControllerTestCase extends AbstractHttpControllerTestCase
{

    protected function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__.'/../../../../../config/application.config.php'
        );

        parent::setUp();
    }

    public function testMovieRenturnApiControllerAuthenticationUnsuccessful()
    {
        $this->dispatch('/movie-return', 'POST');
        $this->assertResponseStatusCode(401);

    }
}
