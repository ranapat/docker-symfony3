<?php

namespace NeoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * Potentially hazardous asteroids
     *
     * Display all DB entries which contain potentially hazardous asteroids
     *
     * @Route("/neo/hazardous")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function hazardousAction()
    {
        $neoRepository = $this->get('neo.repository');
        $hazardous = $neoRepository->findAllHazardous();

        return $this->json($hazardous);
    }

    /**
     * The fastest ateroid
     *
     * Calculate and return the model of the fastest ateroid
     *
     * @Route("/neo/fastest")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fastestAction(Request $request)
    {
        $neoRepository = $this->get('neo.repository');
        $fastest = $neoRepository->findFastest($request->get('hazardous', 'false') === 'true');

        return $this->json($fastest);
    }

    /**
     * Year with most ateroids
     *
     * Calculate and return a year with most ateroids
     *
     * @Route("/neo/best-year")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function bestYearAction(Request $request)
    {
        $neoRepository = $this->get('neo.repository');
        $year = $neoRepository->findYearWithMostRecords($request->get('hazardous', 'false') === 'true');

        return $this->json([ 'year' => $year ]);
    }

    /**
     * Month with most ateroids
     *
     * Calculate and return a month with most ateroids
     *
     * @Route("/neo/best-month")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function bestMonthAction(Request $request)
    {
        $neoRepository = $this->get('neo.repository');
        $month = $neoRepository->findMonthWithMostRecords($request->get('hazardous', 'false') === 'true');

        return $this->json([ 'month' => $month ]);
    }
}
