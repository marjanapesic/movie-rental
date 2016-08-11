<?php
namespace ApplicationTest\Service\BonusPoints;

use PHPUnit_Framework_TestCase;

use ApplicationTest\Bootstrap;
use Application\ValueObject\Price;
use Application\Service\BonusPoints\Service;

class ServiceTest extends \PHPUnit_Framework_TestCase {

    /** @var Service  */
    protected $service;

    function setUp()
    {
        $this->service = new Service();
    }

    function tearDown()
    {
        unset($this->service);
    }

    function testCalculateBonusPoints()
    {
        $start = new \DateTime();
        $start->setTime(0, 0);
        $movie = new \Application\Entity\Movie();

        $movieRental = new \Application\Entity\MovieRental();
        $movieRental->setMovie($movie);
        $movieRental->setStartDate(new \DateTime());


        $movieTypeAssignment = new \Application\Entity\MovieTypeAssignment();

        $movieTypeAssignmentRepositoryMock = $this->getMockBuilder('Application\Repository\MovieTypeAssignment')
            ->disableOriginalConstructor()
            ->getMock();
        $movieTypeAssignmentRepositoryMock->expects($this->atLeastOnce())
            ->method( 'getMovieTypeAssignment' )
            ->withAnyParameters()
            ->will( $this->returnValue( $movieTypeAssignment ) );


        $emMock = $this->getMock('\Doctrine\ORM\EntityManager',
            array('getRepository'), array(), '', false);
        $emMock->expects( $this->atLeastOnce() )
            ->method( 'getRepository' )
            ->will( $this->returnValue( $movieTypeAssignmentRepositoryMock ) );

        /** @var \Zend\ServiceManager\ServiceManager $serviceManager */
        $serviceManager =  Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('doctrine.entitymanager.orm_default', $emMock);

        $this->service->setServiceLocator($serviceManager);


        /**
         * Assert number of bonus points for NEW type of movie
         */
        $movieTypeAssignment->setMovieTypeIdentifier($movieTypeAssignment::NEW_TYPE);
        $result = $this->service->calculateBonusPoints($movieRental);
        $this->assertEquals(2, $result);


        /**
         * Assert number of bonus points for REGULAR type of movie
         */
        $movieTypeAssignment->setMovieTypeIdentifier($movieTypeAssignment::REGULAR_TYPE);
        $result = $this->service->calculateBonusPoints($movieRental);
        $this->assertEquals(1, $result);


        /**
         * Assert number of bonus points for OLD type of movie
         */
        $movieTypeAssignment->setMovieTypeIdentifier($movieTypeAssignment::OLD_TYPE);
        $result = $this->service->calculateBonusPoints($movieRental);
        $this->assertEquals(1, $result);

    }

}