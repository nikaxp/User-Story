<?php

namespace UserStoryBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="UserStoryBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity = "Person", mappedBy = "user")
     */
    private $people;



    public function __construct()
    {
        parent::__construct();
    }


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
     * Add person
     *
     * @param \UserStoryBundle\Entity\Person $person
     *
     * @return User
     */
    public function addPerson(\UserStoryBundle\Entity\Person $person)
    {
        $this->people[] = $person;

        return $this;
    }

    /**
     * Remove person
     *
     * @param \UserStoryBundle\Entity\Person $person
     */
    public function removePerson(\UserStoryBundle\Entity\Person $person)
    {
        $this->people->removeElement($person);
    }

    /**
     * Get people
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeople()
    {
        return $this->people;
    }
}
