<?php

namespace Zmc\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class User
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
	protected $username;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $avatar;

    /**
     * just for file uploading
     */
    public $avatarFile;

    /**
     * @ORM\ManyToMany(targetEntity="Occupation", inversedBy="users")
     * @ORM\JoinTable(name="users_occupations")
     */
    protected $occupations;

    public function __construct()
    {
        $this->occupations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Add occupations
     *
     * @param \Zmc\DemoBundle\Entity\Occupation $occupations
     * @return User
     */
    public function addOccupation(\Zmc\DemoBundle\Entity\Occupation $occupations)
    {
        $this->occupations[] = $occupations;
    
        return $this;
    }

    /**
     * Remove occupations
     *
     * @param \Zmc\DemoBundle\Entity\Occupation $occupations
     */
    public function removeOccupation(\Zmc\DemoBundle\Entity\Occupation $occupations)
    {
        $this->occupations->removeElement($occupations);
    }

    /**
     * Get occupations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOccupations()
    {
        return $this->occupations;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    
        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    public function setAvatarFile(UploadedFile $avatarFile = null)
    {
        $this->avatarFile = $avatarFile;
    }

    /**     
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null!== $this->getAvatarFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(),true));
            $this->avatar = $filename.'.'.$this->getAvatarFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // the file property can be empty if the field is not required    
        if (null=== $this->getAvatarFile()) {
            return;
        }    
        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the
        // target filename to move to
        $this->getAvatarFile()->move($this->getUploadRootDir(), $this->getAvatarFile()->getClientOriginalName());    
        // set the path property to the filename where you've saved the file    
        $this->avatar = $this->getAvatarFile()->getClientOriginalName();
        // clean up the file property as you won't need it anymore    
        $this->avatar =null;
    }

    public function getAbsolutePath()
    {
        returnnull=== $this->avatar ? null : $this->getUploadRootDir().'/'.$this->avatar;
    }

    public function getWebPath()
    {
        returnnull=== $this->avatar ? null : $this->getUploadDir().'/'.$this->avatar;
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return'uploads/avatars';    
    }
}