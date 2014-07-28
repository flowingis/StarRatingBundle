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
            ->setMethods( array('getRepository', 'persist', 'commit') )
            ->getMock();
    }

    /**
     * Test load() method
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

    /**
     * Test getAverage() method
     */
    public function testGetAverage()
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
        $average = $starrating->getAverage($id);

        $this->assertEquals( null, $average );
    }

    /**
     * Test saveNewScore() method
     */
    public function testSaveNewScore()
    {
        $id = 7700;
        $score = 5;

        $object = $this->expectedRating( $id, $score );

        $this->doctrine->expects($this->atLeastOnce())
            ->method('persist')
            ->with( $object )
            ->will($this->returnValue( $object ));

        $starrating = new StarRatingService( $this->doctrine );
        $rating = $starrating->saveNewScore($id, $score);

        $this->assertEquals( $object, $rating );
    }




    protected function expectedRating( $id, $score ){
        $rating = new Rating();
        $rating->setId( $id );
        $rating->setTotalcount( $score );
        $rating->setAverage( $score );
        $rating->setCount( 1 );

        return $rating;
    }


}