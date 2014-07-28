<?php
/**
 *
 * StarRating service
 *
 */

namespace Ideato\StarRatingBundle\Service;

use Ideato\StarRatingBundle\Entity\Rating;
use Ideato\StarRatingBundle\Exception;
use Doctrine\ORM\EntityManager;


class StarRatingService {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;


    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->repository = $this->em->getRepository('IdeatoStarRatingBundle:Rating');
    }

    /**
     * Load rating
     *
     * @param $contentId
     * @throws Exception\NotFoundException
     * @return null|object
     */
    public function load( $contentId ) {
        $rating = $this->repository->find($contentId);

        if( !$rating ) {
            throw new Exception\NotFoundException (
                'No content found for id: '. $contentId
            );
        }

        return $rating;
    }

    /**
     * Get average
     *
     * @param $contentId
     * @return float
     */
    public function getAverage( $contentId ) {
        try {
            return $this->load( $contentId )->getAverage();
        } catch( Exception\NotFoundException $e ) {
            return 0.0;
        }
    }


    /**
     * Store score
     *
     * @param $contentId
     * @param $score
     * @throws Exception\InvalidArgumentException
     * @return float
     */
    public function save( $contentId, $score )
    {
        if( !$contentId ) {
            throw new Exception\InvalidArgumentException('$contentId not provided');
        }

        if( !$score ) {
            throw new Exception\InvalidArgumentException('$score value not provided');
        }

        if( $score < 0 || $score > 5 ) {
            throw new Exception\InvalidArgumentException('$score value not valid. Valid values are between 0 and 5');
        }

        try {
            //update the record
            $rating = $this->load( $contentId );

            $count = $rating->getCount() + 1;
            $totalCount = $rating->getTotalcount() + $score;
            $average = $totalCount / $count;

            $rating->setCount( $count );
            $rating->setTotalcount( $totalCount );
            $rating->setAverage( $average );
            $this->em->flush();
        } catch( Exception\NotFoundException $e ) {
            //create a new one
            $rating = new Rating();
            $rating->setId($contentId);
            $rating->setCount( 1 );
            $rating->setTotalcount( $score );
            $rating->setAverage( $score );
            $average = $score;

            $this->em->persist($rating);
            $this->em->flush();
        }

        return $average;
    }

}