<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="manufacturer")
 * @UniqueEntity("name", groups={"post_manufacturers"})
 * @ApiResource(
 *   collectionOperations={
 *      "GET"={
 *      },
 *      "POST"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::POST_MANUFACTURERS'))",
 *          "validation_groups"={"post_manufacturers"}
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
   */
  private $id;
  
  /**
   * @var null|string
   * @ORM\Column(type="string", length=128)
   * @Groups({"post_manufacturers"})
   * @Assert\NotBlank(groups={"post_manufacturers"})
   * @Assert\Length(max="128", groups={"post_manufacturers"})
   */
  private $name;
  
  function __construct()
  {
    $this->id = Uuid::uuid4()->toString();
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
  
  
}