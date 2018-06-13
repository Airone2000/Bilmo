<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="manufacturer")
 * @UniqueEntity("name", groups={"post_manufacturers", "put_manufacturers"})
 * @ApiResource(
 *   collectionOperations={
 *      "GET"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::LIST_MANUFACTURERS'))",
 *          "normalization_context"={"groups"={"list_manufacturers"}}
 *      },
 *      "POST"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::POST_MANUFACTURERS'))",
 *          "validation_groups"={"post_manufacturers"},
 *          "normalization_context"={"groups"={"get_manufacturers"}},
 *          "denormalization_context"={"groups"={"post_manufacturers"}}
 *      }
 *   },
 *   itemOperations={
 *      "GET"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::GET_MANUFACTURERS'), object)",
 *          "normalization_context"={"groups"={"get_manufacturers"}}
 *      },
 *      "PUT"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::PUT_MANUFACTURERS'), object)",
 *          "validation_groups"={"put_manufacturers"},
 *          "normalization_context"={"groups"={"get_manufacturers"}},
 *          "denormalization_context"={"groups"={"put_manufacturers"}}
 *      },
 *      "DELETE"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::DELETE_MANUFACTURERS'), object)"
 *      }
 *   }
 * )
 */
class Manufacturer
{
  /**
   * @var string
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="NONE")
   * @ORM\Column(type="string", length=50)
   * @Groups({"list_manufacturers", "get_manufacturers"})
   */
  private $id;
  
  /**
   * @var null|string
   * @ORM\Column(type="string", length=128)
   * @Groups({"post_manufacturers", "list_manufacturers", "get_manufacturers", "put_manufacturers"})
   * @Assert\NotBlank(groups={"post_manufacturers"})
   * @Assert\Length(max="128", groups={"post_manufacturers", "put_manufacturers"})
   */
  private $name;
  
  /**
   * @var Collection
   * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="manufacturer")
   * @Groups({"get_manufacturers"})
   */
  private $categories;
  
  /**
   * @var Collection
   * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="manufacturer")
   */
  private $products;
  
  function __construct()
  {
    $this->id = Uuid::uuid4()->toString();
    $this->categories = new ArrayCollection();
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
   * @return \App\Entity\Manufacturer
   */
  public function setId(string $id): self
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
   * @return \App\Entity\Manufacturer
   */
  public function setName(string $name): self
  {
    $this->name = $name;
    return $this;
  }
  
  /**
   * @return Collection
   */
  public function getCategories(): Collection
  {
    return $this->categories;
  }
  
  /**
   * @param Collection $categories
   * @return Manufacturer
   */
  public function setCategories(Collection $categories): Manufacturer
  {
    $this->categories = $categories;
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
   * @return Manufacturer
   */
  public function setProducts(Collection $products): Manufacturer
  {
    $this->products = $products;
    return $this;
  }
  
  
  
  
}