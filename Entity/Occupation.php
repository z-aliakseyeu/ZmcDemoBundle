<?php

namespace Zmc\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="occupations")
 *
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class Occupation implements PopularableInterface
{
	/**
	 * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
	 */
	protected $id;

	/**
	 *
	 * @ORM\Column(type="string", length=255)
	 */
	protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="occupations")
     */
    protected $users;

    /**
     * @ORM\Column(type="integer")
     */
    protected $popularity;


    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->popularity = 0;
    }

    public function __toString()
    {
        return null === $this->name ? '' : $this->name;
    }

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
     * Set name
     *
     * @param string $name
     * @return Occupation
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
     * Add users
     *
     * @param \Zmc\DemoBundle\Entity\User $users
     * @return Occupation
     */
    public function addUser(\Zmc\DemoBundle\Entity\User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param \Zmc\DemoBundle\Entity\User $users
     */
    public function removeUser(\Zmc\DemoBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set popularity
     *
     * @param integer $popularity
     * @return Occupation
     */
    public function setPopularity($popularity)
    {
        $this->popularity = $popularity;
    
        return $this;
    }

    /**
     * Get popularity
     *
     * @return integer 
     */
    public function getPopularity()
    {
        return $this->popularity;
    }
}