<?php

namespace Ideato\StarRatingBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ideato\StarRatingBundle\StarRating\Exception;

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

        $contentId = (int)$request->request->get('contentId');
        $score = (float)$request->request->get('score');

        $starrating = $this->get('ideato_starrating_service');
        $average = $starrating->save( $contentId, $score );

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
        $starrating = $this->get('ideato_starrating_service');
        $average = $starrating->getAverage( $contentId );

        return $this->render(
            "IdeatoStarRatingBundle:StarRating:rate.html.twig",
            array(
                'contentId' => $contentId,
                'score' => $average
            )
        );
    }

}
