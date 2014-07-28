<?php

namespace Ideato\StarRatingBundle\Tests\Controller;

use Ideato\StarRatingBundle\Service\StarRatingService;
use Ideato\StarRatingBundle\Entity\Rating;
use Ideato\StarRatingBundle\Exception;


class StarRatingServiceTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $repository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $doctrine;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->repository = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->setMethods( array('find') )
            ->getMock();

        $this->doctrine = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods( array('getRepository') )
            ->getMock();
    }

    /**
     *
     */
    public function testLoad()
    {
        $id = 7700;
        $object = new Rating();

        $this->repository->expects($this->atLeastOnce())
                ->method('find')
                ->with($id)
                ->will($this->returnValue( $object ));

        $this->doctrine->expects($this->atLeastOnce())
                ->method('getRepository')
                ->with('IdeatoStarRatingBundle:Rating')
                ->will($this->returnValue( $this->repository ));

        $starrating = new StarRatingService( $this->doctrine );
        $rating = $starrating->load($id);

        $this->assertEquals( $object, $rating );
    }



}