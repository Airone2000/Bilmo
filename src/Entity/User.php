<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\EntityListeners("App\Listener\UserListener")
 * @ORM\Table(name="user")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @UniqueEntity(fields={"username", "app"}, groups={"post_users"})
 * @UniqueEntity(fields={"emailAddress", "app"}, groups={"post_users"})
 * @ApiResource(
 *   itemOperations={"GET", "PUT", "DELETE"},
 *   collectionOperations={
 *      "GET"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::LIST_USERS'))",
 *          "normalization_context"={"groups"={"list_users"}}
 *      },
 *      "POST"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::POST_USERS'))",
 *          "denormalization_context"={"groups"={"post_users"}},
 *          "normalization_context"={"groups"={"get_users"}},
 *          "validation_groups"={"post_users"}
 *      }
 *   }
 * )
 */
class User
{
  /**
   * @var null|string
   *
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="NONE")
   * @ORM\Column(type="string", length=50)
   * @Groups({"get_users", "list_users"})
   */
  private $id;
  
  /**
   * @var null|\App\Entity\App
   * @ORM\ManyToOne(targetEntity="App\Entity\App", inversedBy="users")
   */
  private $app;
  
  /**
   * @var null|string
   *
   * @ORM\Column(type="string", length=100)
   * @Groups({"post_users", "get_users", "list_users"})
   * @Assert\NotBlank(groups={"post_users"})
   * @Assert\Length(max="100", min="3", groups={"post_users"})
   */
  private $username;
  
  /**
   * @var null|string
   *
   * @ORM\Column(type="string", length=255)
   * @Groups({"post_users", "get_users", "list_users"})
   * @Assert\NotBlank(groups={"post_users"})
   * @Assert\Email(groups={"post_users"})
   * @Assert\Length(max="255", groups={"post_users"})
   */
  private $emailAddress;
  
  /**
   * @var null|string
   *
   * @ORM\Column(type="string")
   */
  private $password;
  
  /**
   * @var null|string
   * @Groups({"post_users"})
   * @Assert\NotBlank(groups={"post_users"})
   */
  private $plainPassword;
  
  /**
   * @var null|string
   * @Groups({"post_users"})
   * @Assert\NotBlank(groups={"post_users"})
   * @Assert\EqualTo(propertyPath="plainPassword", groups={"post_users"})
   */
  private $plainPasswordConfirm;
  
  /**
   * @var null|\DateTime
   *
   * @ORM\Column(type="datetime")
   * @Gedmo\Timestampable(on="create")
   * @Groups({"get_users", "list_users"})
   */
  private $createdAt;
  
  /**
   * @var null|\DateTime
   *
   * @ORM\Column(type="datetime")
   * @Gedmo\Timestampable(on="update")
   * @Groups({"get_users", "list_users"})
   */
  private $updatedAt;
  
  /**
   * @var null|\DateTime
   *
   * @ORM\Column(type="datetime", nullable=true)
   */
  private $deletedAt;
  
  /**
   * User constructor.
   */
  function __construct()
  {
    $this->id = Uuid::uuid4();
  }
  
  /**
   * @return null|string
   */
  public function getId(): ?string
  {
    return $this->id;
  }
  
  /**
   * @param null|string $id
   * @return User
   */
  public function setId(string $id): self
  {
    $this->id = $id;
    return $this;
  }
  
  /**
   * @return \App\Entity\App|null
   */
  public function getApp(): ? App
  {
    return $this->app;
  }
  
  /**
   * @param \App\Entity\App|null $app
   * @return User
   */
  public function setApp(App $app): self
  {
    $this->app = $app;
    return $this;
  }
  
  /**
   * @return null|string
   */
  public function getUsername(): ?string
  {
    return $this->username;
  }
  
  /**
   * @param null|string $username
   * @return User
   */
  public function setUsername(?string $username)
  {
    $this->username = $username;
    return $this;
  }
  
  /**
   * @return null|string
   */
  public function getEmailAddress(): ?string
  {
    return $this->emailAddress;
  }
  
  /**
   * @param null|string $emailAddress
   * @return User
   */
  public function setEmailAddress(?string $emailAddress): self
  {
    $this->emailAddress = $emailAddress;
    return $this;
  }
  
  /**
   * @return null|string
   */
  public function getPassword(): ?string
  {
    return $this->password;
  }
  
  /**
   * @param null|string $password
   * @return User
   */
  public function setPassword(?string $password): self
  {
    $this->password = $password;
    return $this;
  }
  
  /**
   * @return null|string
   */
  public function getPlainPassword(): ?string
  {
    return $this->plainPassword;
  }
  
  /**
   * @param string $plainPassword
   * @return User
   */
  public function setPlainPassword(string $plainPassword): self
  {
    $this->plainPassword = $plainPassword;
    return $this;
  }
  
  /**
   * @return null|string
   */
  public function getPlainPasswordConfirm(): ?string
  {
    return $this->plainPasswordConfirm;
  }
  
  /**
   * @param string $plainPasswordConfirm
   * @return User
   */
  public function setPlainPasswordConfirm(string $plainPasswordConfirm): self
  {
    $this->plainPasswordConfirm = $plainPasswordConfirm;
    return $this;
  }
  
  /**
   * @return \DateTime|null
   */
  public function getCreatedAt(): ?\DateTime
  {
    return $this->createdAt;
  }
  
  /**
   * @param \DateTime|null $createdAt
   * @return User
   */
  public function setCreatedAt(\DateTime $createdAt): self
  {
    $this->createdAt = $createdAt;
    return $this;
  }
  
  /**
   * @return \DateTime|null
   */
  public function getUpdatedAt(): ?\DateTime
  {
    return $this->updatedAt;
  }
  
  /**
   * @param \DateTime|null $updatedAt
   * @return User
   */
  public function setUpdatedAt(\DateTime $updatedAt): self
  {
    $this->updatedAt = $updatedAt;
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
   * @return User
   */
  public function setDeletedAt(\DateTime $deletedAt): self
  {
    $this->deletedAt = $deletedAt;
    return $this;
  }
  
}