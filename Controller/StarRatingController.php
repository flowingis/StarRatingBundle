<?php

namespace Ideato\StarRatingBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ideato\StarRatingBundle\Entity\Rating;

class StarRatingController extends Controller
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;


    /**
     * Return the result of rating action
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Response
     */
    public function rateAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $contentId = (int)$request->request->get('contentId');
        $score = (float)$request->request->get('score');

        if( !$contentId ) {
            throw $this->createNotFoundException('$contentId not provided');
        }

        if( !$score ) {
            throw $this->createNotFoundException('$score value not provided');
        }

        if( $score < 0 || $score > 5 ) {
            throw new BadRequestHttpException('$score value not valid. Valid values are between 0 and 5');
        }

        $rating = $this->getRepository()->find($contentId);
        if( !$rating ) {
            //new record
            $rating = new Rating();
            $rating->setId($contentId);
            $rating->setCount( 1 );
            $rating->setTotalcount( $score );
            $rating->setAverage( $score );
            $average = $score;

            $em->persist($rating);
            $em->flush();
        } else {
            //update record
            $count = $rating->getCount() + 1;
            $totalCount = $rating->getTotalcount() + $score;
            $average = $totalCount / $count;

            $rating->setCount( $count );
            $rating->setTotalcount( $totalCount );
            $rating->setAverage( $average );
            $em->flush();
        }


        $response = new Response();
        return $response->setContent($average);
    }

    /**
     * Display the star rating
     *
     * @param int $contentId
     * @return Response
     */
    public function displayRateAction( $contentId )
    {
        $rating = $this->getRepository()->find($contentId);
        $average = $rating ? $rating->getAverage() : 0.0;

        return $this->render(
            "IdeatoStarRatingBundle:StarRating:rate.html.twig",
            array(
                'contentId' => $contentId,
                'score' => $average
            )
        );
    }

    /**
     * Return the Star Rating entity repository
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        if( !$this->repository ) {
            $this->repository = $this->getDoctrine()
                                     ->getRepository('IdeatoStarRatingBundle:Rating');
        }

        return $this->repository;
    }
}
