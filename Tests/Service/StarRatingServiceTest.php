<?php

namespace Ideato\StarRatingBundle\Tests\Controller;

use Ideato\StarRatingBundle\StarRating\StarRating;


class StarRatingServiceTest extends \PHPUnit_Framework_TestCase {




    public function testLoad()
    {
        $id = 7700;
        $object = new \stdClass();

        $repositoryMock = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
                                ->disableOriginalConstructor()
                                ->setMethods( array('find') )
                                ->getMock();

        $repositoryMock->expects($this->atLeastOnce())
            ->method('find')
            ->with($id)
            ->will($this->returnValue( $object ));

        $doctrineMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                             ->disableOriginalConstructor()
                             ->setMethods( array('getRepository') )
                             ->getMock();

        $doctrineMock->expects($this->atLeastOnce())
                     ->method('getRepository')
                     ->with('IdeatoStarRatingBundle:Rating')
                     ->will($this->returnValue($repositoryMock));



        $starrating = new StarRating( $doctrineMock );
        $rating = $starrating->load($id);

        $this->assertEquals( $object, $rating );
    }



}