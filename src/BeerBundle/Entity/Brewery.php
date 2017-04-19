<?php

namespace BeerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Brewery.
 *
 * @ORM\Table("breweries")
 * @ORM\Entity(repositoryClass="BeerBundle\Repository\BreweryRepository")
 */
class Brewery
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue("AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string")
     *
     * @Serializer\SerializedName("address1")
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="secondary_address", type="string")
     *
     * @Serializer\SerializedName("address2")
     */
    protected $secondaryAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string")
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string")
     */
    protected $state;

    /**
     * @var int
     *
     * @ORM\Column(name="code", type="integer")
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string")
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string")
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string")
     */
    protected $website;

    /**
     * @var string
     *
     * @ORM\Column(name="filepath", type="string")
     *
     * @Serializer\SerializedName("filepath")
     */
    protected $filePath;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     *
     * @Serializer\SerializedName("descript")
     */
    protected $description;

    /**
     * @var int
     *
     * @ORM\Column(name="add_user", type="integer")
     *
     * @Serializer\SerializedName("add_user")
     */
    protected $addUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\SerializedName("last_mod")
     * @Serializer\Type("DateTime<'Y-m-d H:i:s e'>")
     */
    protected $updatedAt;

    /**
     * @var Beer[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BeerBundle\Entity\Beer", mappedBy="brewery")
     */
    protected $beers;

    /**
     * @var Location
     *
     * @ORM\OneToMany(targetEntity="BeerBundle\Entity\Location", mappedBy="brewery")
     */
    protected $location;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecondaryAddress()
    {
        return $this->secondaryAddress;
    }

    /**
     * @param string $secondaryAddress
     *
     * @return $this
     */
    public function setSecondaryAddress($secondaryAddress)
    {
        $this->secondaryAddress = $secondaryAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $website
     *
     * @return $this
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     *
     * @return $this
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getAddUser()
    {
        return $this->addUser;
    }

    /**
     * @param int $addUser
     *
     * @return $this
     */
    public function setAddUser($addUser)
    {
        $this->addUser = $addUser;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Beer[]|ArrayCollection
     */
    public function getBeers()
    {
        return $this->beers;
    }

    /**
     * @param Beer[]|ArrayCollection $beers
     *
     * @return $this
     */
    public function setBeers($beers)
    {
        $this->beers = $beers;

        return $this;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Location $location
     *
     * @return $this
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }
}
