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
            ->setMethods( array('getRepository', 'persist', 'flush') )
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
     * Test load() method
     *
     * @expectedException \Exception
     */
    public function testLoadException()
    {
        $id = 7701;

        $this->repository->expects($this->atLeastOnce())
                ->method('find')
                ->with($id)
                ->will($this->throwException( new \Exception() ));

        $this->doctrine->expects($this->atLeastOnce())
                ->method('getRepository')
                ->with('IdeatoStarRatingBundle:Rating')
                ->will($this->returnValue( $this->repository ));

        $starrating = new StarRatingService( $this->doctrine );
        $starrating->load($id);
    }

    /**
     * Test getAverage() method
     */
    public function testGetAverage()
    {
        $id = 7700;
        $score = 5;

        $object = $this->expectedRating( $id, $score );

        $this->repository->expects( $this->once() )
                ->method('find')
                ->with( $id )
                ->will( $this->returnValue( $object ) );

        $this->doctrine->expects($this->atLeastOnce())
            ->method('getRepository')
            ->with('IdeatoStarRatingBundle:Rating')
            ->will($this->returnValue( $this->repository ));

        $starrating = new StarRatingService( $this->doctrine );
        $average = $starrating->getAverage( $id );

        $this->assertEquals( 5, $average );
    }

    /**
     * Test getAverage() method
     */
    public function testGetAverageShouldReturn0WhenNotFoundExceptionIsThrown()
    {
        $id = 7701;

        $this->repository->expects( $this->once() )
                ->method('find')
                ->with( $id )
                ->will( $this->returnValue(null) );

        $this->doctrine->expects($this->atLeastOnce())
            ->method('getRepository')
            ->with('IdeatoStarRatingBundle:Rating')
            ->will($this->returnValue( $this->repository ));

        $starrating = new StarRatingService( $this->doctrine );
        $average = $starrating->getAverage( $id );

        $this->assertEquals( 0.0, $average );
    }

    /**
     * Test getAverage() method
     *
     * @expectedException \Exception
     */
    public function testGetAverageException()
    {
        $id = 7701;

        $this->repository->expects( $this->once() )
                ->method('find')
                ->with( $id )
                ->will( $this->throwException( new \Exception() ) );

        $this->doctrine->expects($this->atLeastOnce())
            ->method('getRepository')
            ->with('IdeatoStarRatingBundle:Rating')
            ->will($this->returnValue( $this->repository ));

        $starrating = new StarRatingService( $this->doctrine );
        $starrating->getAverage( $id );
    }

    /**
     * Test saveNewScore() method
     */
    public function testSaveNewScore()
    {
        $id = 7700;
        $score = 5;

        $object = $this->expectedRating( $id, $score );

        $this->doctrine->expects($this->once())
            ->method('persist')
            ->with( $object );

        $this->doctrine->expects($this->once())
            ->method('flush');

        $starrating = new StarRatingService( $this->doctrine );
        $rating = $starrating->saveNewScore($id, $score);

        $this->assertEquals( $object, $rating );
    }

    /**
     * Test updateScore() method
     */
    public function testUpdateScore()
    {
        $id = 7700;
        $score = 5;

        $object = $this->expectedRating( $id, $score, 5, 1 );

        $this->repository->expects( $this->once() )
            ->method('find')
            ->with( $id )
            ->will( $this->returnValue($object) );

        $this->doctrine->expects($this->atLeastOnce())
            ->method('getRepository')
            ->with('IdeatoStarRatingBundle:Rating')
            ->will($this->returnValue( $this->repository ));

        $this->doctrine->expects($this->once())
            ->method('flush');

        $starrating = new StarRatingService( $this->doctrine );
        $rating = $starrating->updateScore($id, $score);

        $this->assertEquals( $object, $rating );
    }

    /**
     * Test updateScore() method
     *
     * @expectedException \Exception
     */
    public function testUpdateScoreException()
    {
        $id = 7701;
        $score = 5;

        $object = $this->expectedRating( $id, $score, 5, 1 );

        $this->repository->expects( $this->once() )
            ->method('find')
            ->with( $id )
            ->will( $this->throwException( new \Exception() ) );

        $this->doctrine->expects($this->atLeastOnce())
            ->method('getRepository')
            ->with('IdeatoStarRatingBundle:Rating')
            ->will($this->returnValue( $this->repository ));

        $starrating = new StarRatingService( $this->doctrine );
        $starrating->updateScore($id, $score);
    }


    protected function expectedRating( $id, $score, $totalcount = 0, $count = 0 ){
        $totalcount = $totalcount + $score;
        $average = $totalcount / ++$count;

        $rating = new Rating();
        $rating->setId( $id );
        $rating->setAverage( $average );
        $rating->setTotalcount( $totalcount );
        $rating->setCount( $count );

        return $rating;
    }


}