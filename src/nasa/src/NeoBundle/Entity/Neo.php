<?php

namespace NeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="neo")
 * @ORM\Entity(repositoryClass="NeoBundle\Repository\NeoRepository")
 */
class Neo implements \JsonSerializable
{
    /**
     * @var id
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approach_at", type="datetime")
     */
    private $approachAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $reference;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $speed;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_hazardous", type="boolean")
     */
    private $isHazardous;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Neo
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set approachAt
     *
     * @param \DateTime $approachAt
     *
     * @return Neo
     */
    public function setApproachAt(\DateTime $approachAt)
    {
        $this->approachAt = $approachAt;

        return $this;
    }

    /**
     * Set approachAt as string
     *
     * @param string $approachAt

     * @return Neo
     */
    public function setApproachAtAsString(string $approachAtAsString)
    {
        $date = \DateTime::createFromFormat('Y-m-d', $approachAtAsString);
        $date->setTime(0, 0, 0, 0);
        $this->setApproachAt($date);

        return $this;
    }

    /**
     * Get approachAt
     *
     * @return \DateTime
     */
    public function getApproachAt()
    {
        return $this->approachAt;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Neo
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set reference
     *
     * @param integer $reference
     *
     * @return Neo
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return integer
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set speed
     *
     * @param float $speed
     *
     * @return Neo
     */
    public function setSpeed(float $speed)
    {
        $this->speed = $speed;

        return $this;
    }

    /**
     * Get speed
     *
     * @return float
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * Set isHazardous
     *
     * @param bool $isHazardous
     *
     * @return Neo
     */
    public function setIsHazardous($isHazardous)
    {
        $this->isHazardous = $isHazardous;

        return $this;
    }

    /**
     * Get isHazardous
     *
     * @return bool
     */
    public function getIsHazardous()
    {
        return $this->isHazardous;
    }

    public function jsonSerialize()
    {
        return array(
            'reference' => $this->getReference(),
            'name' => $this->getName(),
            'approachAt' => $this->getApproachAt(),
            'speed' => $this->getSpeed(),
            'isHazardous'=> $this->getIsHazardous(),
        );
    }
}
