<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @ApiResource(
 *   collectionOperations={
 *      "POST"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::POST_CATEGORIES'))",
 *          "denormalization_context"={"groups"={"post_categories"}},
 *          "validation_groups"={"post_categories"},
 *          "normalization_context"={"groups"={"get_categories"}}
 *      },
 *      "GET"={
 *          "controller"=App\Controller\HttpNotFoundAction::class
 *      }
 *   },
 *   itemOperations={
 *      "GET"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::GET_CATEGORIES'), object)",
 *          "normalization_context"={"groups"={"get_categories"}}
 *      },
 *      "PUT"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::PUT_CATEGORIES'), object)",
 *          "denormalization_context"={"groups"={"put_categories"}},
 *          "validation_groups"={"put_categories"},
 *          "normalization_context"={"groups"={"get_categories"}}
 *      },
 *      "DELETE"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::DELETE_CATEGORIES'), object)"
 *      }
 *   }
 * )
 */
class Category
{
  /**
   * @var string
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="NONE")
   * @ORM\Column(type="string", length=50)
   * @Groups({"get_categories"})
   */
  private $id;
  
  /**
   * @var null|string
   * @ORM\Column(type="string", length=128)
   * @Groups({"post_categories", "get_categories", "put_categories"})
   * @Assert\NotBlank(groups={"post_categories", "put_categories"})
   * @Assert\Length(max="128", groups={"post_categories", "put_categories"})
   */
  private $name;
  
  /**
   * @var null|\App\Entity\Manufacturer
   * @ORM\ManyToOne(targetEntity="App\Entity\Manufacturer", inversedBy="categories")
   * @Groups({"post_categories", "put_categories", "get_categories"})
   * @Assert\NotNull(groups={"post_categories", "put_categories"})
   */
  private $manufacturer;
  
  /**
   * @var Collection
   * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="categories")
   */
  private $products;
  
  /**
   * Category constructor.
   */
  function __construct()
  {
    $this->id = Uuid::uuid4()->toString();
    $this->products = new ArrayCollection();
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
   * @return Category
   */
  public function setId(string $id): Category
  {
    $this->id = $id;
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
   * @param string $name
   * @return \App\Entity\Category
   */
  public function setName(string $name): self
  {
    $this->name = $name;
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
   * @param \App\Entity\Manufacturer $manufacturer
   * @return \App\Entity\Category
   */
  public function setManufacturer(Manufacturer $manufacturer): self
  {
    $this->manufacturer = $manufacturer;
    return $this;
  }
  
  /**
   * @return Collection
   */
  public function getProducts(): Collection
  {
    return $this->products;
  }
  
  /**
   * @param Collection $products
   * @return Category
   */
  public function setProducts(Collection $products): Category
  {
    $this->products = $products;
    return $this;
  }
  
}