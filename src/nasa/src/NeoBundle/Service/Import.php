<?php

namespace NeoBundle\Service;

use Doctrine\ORM\EntityManager;

use Buzz\Browser;

use NeoBundle\Entity\Neo;
use NeoBundle\Repository\NeoRepository;
use NeoBundle\Exception\NeoImportStructureInvalidException;

class Import
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Browser
     */
    private $buzz;

    /**
     * @var NeoRepository;
     */
    private $neoRepository;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $days;

    public function __construct(
        EntityManager $em,
        Browser $buzz,
        NeoRepository $neoRepository,
        string $key, string $url, int $days)
    {
        $this->em = $em;
        $this->buzz = $buzz;
        $this->neoRepository = $neoRepository;
        $this->key = $key;
        $this->url = $url;
        $this->days = $days;
    }

    /**
     * Imports data from nasa.api to DB
     *
     * If not passed will take %api.nasa.gov_days.to.import% days from the current day
     *
     * @param \DateTime $start
     * @param \DateTime $end
     */
    public function import(\DateTime $start = null, \DateTime $end = null)
    {
        if ($start === null || $end === null) {
            $end = new \DateTime();
            $start = new \DateTime();
            $start->sub(new \DateInterval('P' . ($this->days - 1) . 'D'));
        }

        $result = json_decode($this->buzz->get($this->getUrl($start, $end))->getContent(), true);

        if ($this->analyze($result)) {
            $this->process($result['near_earth_objects']);
        } else {
            throw new NeoImportStructureInvalidException();
        }
    }

    private function process($data)
    {
        foreach ($data as $key=>$value) {
            foreach ($value as $item) {
                $neo = new Neo();

                $neo->setName($item['name']);
                $neo->setReference($item['neo_reference_id']);
                $neo->setSpeed(
                    count($item['close_approach_data']) > 0 ?
                        $item['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'] : 0
                );
                $neo->setApproachAtAsString(
                    count($item['close_approach_data']) > 0 ?
                        $item['close_approach_data'][0]['close_approach_date'] : null
                );
                $neo->setIsHazardous($item['is_potentially_hazardous_asteroid']);

                $this->persist($neo);
            }
        }
    }

    private function persist(Neo $neo)
    {
        if ($this->neoRepository->referenceExists($neo->getReference())) {
            $this->em->persist($neo);

            $this->em->flush();
        }
    }

    private function analyze($data)
    {
        if (
            is_array($data)
            && isset($data['near_earth_objects'])
        ) {
            return true;
        } else {
            return false;
        }
    }

    private function getUrl(\DateTime $start, \DateTime $end)
    {
        return str_replace(
            '##key##',
            $this->key,
            str_replace(
                '##start##',
                $start->format('Y-m-d'),
                str_replace(
                    '##end##',
                    $end->format('Y-m-d'),
                    $this->url
                )
            )
        );
    }
}