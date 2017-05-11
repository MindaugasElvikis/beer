<?php

namespace BeerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Beer.
 *
 * @ORM\Table("beers")
 * @ORM\Entity(repositoryClass="BeerBundle\Repository\BeerRepository")
 */
class Beer
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
     * @var Brewery
     *
     * @ORM\ManyToOne(targetEntity="BeerBundle\Entity\Brewery")
     * @ORM\JoinColumn(name="brewery_id", referencedColumnName="id")
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("brewery_id")
     */
    protected $brewery;

    /**
     * @var int
     *
     * @ORM\Column(name="title", type="string")
     *
     * @Serializer\SerializedName("name")
     */
    protected $title;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="BeerBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("cat_id")
     */
    protected $category;

    /**
     * @var Style
     *
     * @ORM\ManyToOne(targetEntity="BeerBundle\Entity\Style")
     * @ORM\JoinColumn(name="style_id", referencedColumnName="id")
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("style_id")
     */
    protected $style;

    /**
     * @var float
     *
     * @ORM\Column(name="abv", type="float", nullable=true)
     */
    protected $abv;

    /**
     * @var float
     *
     * @ORM\Column(name="abu", type="float", nullable=true)
     */
    protected $abu;

    /**
     * @var float
     *
     * @ORM\Column(name="srm", type="float", nullable=true)
     */
    protected $srm;

    /**
     * @var float
     *
     * @ORM\Column(name="upc", type="float", nullable=true)
     */
    protected $upc;

    /**
     * @var string
     *
     * @ORM\Column(name="file_path", type="string", nullable=true)
     *
     * @Serializer\SerializedName("filepath")
     */
    protected $filePath;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     *
     * @Serializer\SerializedName("description")
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
     * @return Brewery
     */
    public function getBrewery()
    {
        return $this->brewery;
    }

    /**
     * @param Brewery $brewery
     *
     * @return $this
     */
    public function setBrewery($brewery)
    {
        $this->brewery = $brewery;

        return $this;
    }

    /**
     * @return int
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param int $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     *
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Style
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param Style $style
     *
     * @return $this
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * @return float
     */
    public function getAbv()
    {
        return $this->abv;
    }

    /**
     * @param float $abv
     *
     * @return $this
     */
    public function setAbv($abv)
    {
        $this->abv = $abv;

        return $this;
    }

    /**
     * @return float
     */
    public function getAbu()
    {
        return $this->abu;
    }

    /**
     * @param float $abu
     *
     * @return $this
     */
    public function setAbu($abu)
    {
        $this->abu = $abu;

        return $this;
    }

    /**
     * @return float
     */
    public function getSrm()
    {
        return $this->srm;
    }

    /**
     * @param float $srm
     *
     * @return $this
     */
    public function setSrm($srm)
    {
        $this->srm = $srm;

        return $this;
    }

    /**
     * @return float
     */
    public function getUpc()
    {
        return $this->upc;
    }

    /**
     * @param float $upc
     *
     * @return $this
     */
    public function setUpc($upc)
    {
        $this->upc = $upc;

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
}
