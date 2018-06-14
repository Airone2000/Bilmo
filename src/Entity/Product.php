<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product is the keystone of Bilmo-API.
 * A product belongs to one Manufacturer and to one or many Categories.
 *
 * As for other entities, this is identified by an Uuid that could have been defined as annotation
 * but once again, I prefer to manually define it in the constructor.
 *
 * @ORM\Entity
 * @ORM\Table(name="product")
 * @ApiResource(
 *   collectionOperations={
 *      "POST"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::POST_PRODUCTS'))",
 *          "denormalization_context"={"groups"={"post_products"}},
 *          "validation_groups"={"post_products"},
 *          "normalization_context"={"groups"={"get_products"}}
 *      },
 *      "GET"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::LIST_PRODUCTS'))",
 *          "normalization_context"={"groups"={"list_products"}}
 *      }
 *   },
 *   itemOperations={
 *      "GET"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::GET_PRODUCTS'), object)",
 *          "normalization_context"={"groups"={"get_products"}}
 *      },
 *      "PUT"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::PUT_PRODUCTS'), object)",
 *          "denormalization_context"={"groups"={"put_products"}},
 *          "validation_groups"={"put_products"},
 *          "normalization_context"={"groups"={"get_products"}}
 *      },
 *      "DELETE"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::DELETE_PRODUCTS'), object)"
 *      }
 *   }
 * )
 *
 * The following filter applies on the name/description properties.
 * It's case insensitive.
 * @ApiFilter(
 *     "ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter",
 *     properties={
 *          "name"="ipartial",
 *          "description"="ipartial"
 *     }
 * )
 *
 * The following filter allows to filter on price.
 * Path type : products?priceMin[between]=5.40..5.41
 * @ApiFilter(
 *     "ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter",
 *     properties={"priceMin", "priceMax"}
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Product
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=50)
     * @Groups({"get_products", "list_products"})
     */
    private $id;
    
    /**
     * @var null|\App\Entity\Manufacturer
     * @ORM\ManyToOne(targetEntity="App\Entity\Manufacturer", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post_products", "get_products", "put_products"})
     * @Assert\NotNull(groups={"post_products", "put_products"})
     */
    private $manufacturer;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="products")
     * @Groups({"post_products", "get_products", "put_products"})
     * @Assert\Count(min="1", groups={"post_products", "put_products"})
     */
    private $categories;
    
    /**
     * @var null|string
     * @ORM\Column(type="string", length=255)
     * @Groups({"post_products", "get_products", "list_products", "put_products"})
     * @Assert\NotBlank(groups={"post_products", "put_products"})
     * @Assert\Length(max="255", groups={"post_products", "put_products"})
     */
    private $name;
    
    /**
     * @var null|string
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"post_products", "get_products", "list_products", "put_products"})
     */
    private $description;
    
    /**
     * @var null|float
     * @ORM\Column(type="float")
     * @Groups({"post_products", "get_products", "put_products"})
     * @Assert\NotBlank(groups={"post_products", "put_products"})
     * @Assert\Type(type="float", groups={"post_products", "put_products"})
     */
    private $priceMin;
    
    /**
     * @var null|float
     * @ORM\Column(type="float")
     * @Groups({"post_products", "get_products", "put_products"})
     * @Assert\NotBlank(groups={"post_products", "put_products"})
     * @Assert\Type(type="float", groups={"post_products", "put_products"})
     * @Assert\GreaterThanOrEqual(propertyPath="priceMin", groups={"post_products", "put_products"})
     */
    private $priceMax;
    
    /**
     * @var null|\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;
    
    /**
     * Product constructor.
     */
    function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->categories = new ArrayCollection();
    }
    
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * @param string $id
     * @return Product
     */
    public function setId(string $id): Product
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @return \App\Entity\Manufacturer|null
     */
    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }
    
    /**
     * @param \App\Entity\Manufacturer|null $manufacturer
     * @return Product
     */
    public function setManufacturer(Manufacturer $manufacturer): self
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }
    
    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }
    
    /**
     * @param \Doctrine\Common\Collections\Collection $categories
     * @return Product
     */
    public function setCategories(Collection $categories): Product
    {
        $this->categories = $categories;
        return $this;
    }
    
    /**
     * @param \App\Entity\Category $category
     * @return \App\Entity\Product
     */
    public function addCategory(Category $category): self
    {
        if(!$this->categories->contains($category)) {
            $this->categories->add($category);
        }
        
        return $this;
    }
    
    /**
     * @param \App\Entity\Category $category
     * @return \App\Entity\Product
     */
    public function removeCategory(Category $category): self
    {
        if($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }
        
        return $this;
    }
    
    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    
    /**
     * @param null|string $name
     * @return Product
     */
    public function setName(string  $name): self
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    /**
     * @param null|string $description
     * @return Product
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getPriceMin()
    {
        return $this->priceMin;
    }
    
    /**
     * @param mixed $priceMin
     * @return Product
     */
    public function setPriceMin($priceMin)
    {
        $this->priceMin = $priceMin;
        return $this;
    }
    
    /**
     * @return float|null
     */
    public function getPriceMax()
    {
        return $this->priceMax;
    }
    
    /**
     * @param float|null $priceMax
     * @return Product
     */
    public function setPriceMax($priceMax)
    {
        $this->priceMax = $priceMax;
        return $this;
    }
    
    /**
     * @return \DateTime|null
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }
    
    /**
     * @param \DateTime|null $deletedAt
     * @return Product
     */
    public function setDeletedAt(\DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }
    
}