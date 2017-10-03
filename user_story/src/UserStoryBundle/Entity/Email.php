<?php

namespace UserStoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Email
 *
 * @ORM\Table(name="email")
 * @ORM\Entity(repositoryClass="UserStoryBundle\Repository\EmailRepository")
 */
class Email
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="addressEmail", type="string", length=255, nullable=true)
     */
    private $addressEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity = "Person", inversedBy="emails")
     * @ORM\JoinColumn(name = "person_id", referencedColumnName = "id", nullable = false)
     */
    private $person;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set addressEmail
     *
     * @param string $addressEmail
     *
     * @return Email
     */
    public function setAddressEmail($addressEmail)
    {
        $this->addressEmail = $addressEmail;

        return $this;
    }

    /**
     * Get addressEmail
     *
     * @return string
     */
    public function getAddressEmail()
    {
        return $this->addressEmail;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Email
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set person
     *
     * @param \UserStoryBundle\Entity\Person $person
     *
     * @return Email
     */
    public function setPerson(\UserStoryBundle\Entity\Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \UserStoryBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }
}
