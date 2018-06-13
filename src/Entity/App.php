<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\EntityListeners("App\Listener\AppListener")
 * @ORM\Table(name="app")
 * @ApiResource(
 *   collectionOperations={
 *      "GET"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::LIST_APPS'))",
 *          "normalization_context"={"groups"={"apps_list"}}
 *      },
 *      "POST"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::POST_APPS'))",
 *          "normalization_context"={"groups"={"apps_get"}}
 *      }
 *   },
 *   itemOperations={
 *      "GET"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::GET_APPS'), object)",
 *          "normalization_context"={"groups"={"apps_get"}}
 *      },
 *      "DELETE"={
 *          "access_control"="is_granted(constant('\\App\\Entity\\Permission::DELETE_APPS'), object)"
 *      }
 *   }
 * )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class App implements UserInterface
{
  /**
   * @var null|\Ramsey\Uuid\UuidInterface
   *
   * @ORM\Id()
   * @ORM\Column(type="string", length=50)
   * @ORM\GeneratedValue(strategy="NONE")
   * @Groups({"apps_get", "apps_list"})
   */
  private $id;
  
  /**
   * @var string
   * @ORM\Column(type="string")
   */
  private $secret;
  
  /**
   * @var array
   * @ORM\Column(type="array")
   */
  private $roles;
  
  /**
   * @var \Doctrine\Common\Collections\Collection
   * @ORM\ManyToMany(targetEntity="App\Entity\Permission")
   * @Groups({"apps_get"})
   */
  private $permissions;
  
  /**
   * @var null|\DateTime
   * @ORM\Column(type="datetime")
   * @Gedmo\Timestampable(on="create")
   * @Groups({"apps_get", "apps_list"})
   */
  private $createdAt;
  
  /**
   * @var null|\DateTime
   * @ORM\Column(type="datetime")
   * @Gedmo\Timestampable(on="update")
   */
  private $updatedAt;
  
  /**
   * @var null|\DateTime
   * @ORM\Column(type="datetime", nullable=true)
   */
  private $deletedAt;
  
  /**
   * @var Collection
   * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="app")
   */
  private $users;
  
  /**
   * App constructor.
   */
  function __construct()
  {
    $this->id = Uuid::uuid4();
    $this->secret = sha1(uniqid('3"az.]##Y8#[/YbY'));
    $this->roles = ['ROLE_CONSUMER'];
    $this->permissions = new ArrayCollection();
    $this->users = new ArrayCollection();
  }
  
  /**
   * @return null|string
   */
  public function getId(): ?string
  {
    return $this->id;
  }
  
  /**
   * @param string $id
   * @return \App\Entity\App
   */
  public function setId(string $id): self
  {
    $this->id = $id;
    return $this;
  }
  
  /**
   * @return string
   * Initialized in the constructor
   */
  public function getSecret(): string
  {
    return $this->secret;
  }
  
  /**
   * @param string $secret
   * @return \App\Entity\App
   */
  public function setSecret(string $secret): self
  {
    $this->secret = $secret;
    return $this;
  }
  
  /**
   * @return array
   * Initialized in the constructor
   */
  public function getRoles(): array
  {
    return $this->roles;
  }
  
  /**
   * @param array $roles
   * @return \App\Entity\App
   */
  public function setRoles(array $roles): self
  {
    $this->roles = $roles;
    return $this;
  }
  
  /**
   * @return string
   * Initialized in the constructor
   */
  public function getPassword(): string
  {
    return $this->getSecret();
  }
  
  /**
   * \Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder::encodePassword
   * does not consider our salt at all.
   * So just return null right here.
   */
  public function getSalt(): void
  {
  }
  
  /**
   * @return null|string
   */
  public function getUsername(): ?string
  {
    return $this->getId();
  }
  
  /**
   * Nothing to erase FTM
   * Maybe one day ...
   */
  public function eraseCredentials(): void {}
  
  /**
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getPermissions(): Collection
  {
    return $this->permissions;
  }
  
  /**
   * @param \Doctrine\Common\Collections\Collection $permissions
   * @return App
   */
  public function setPermissions(Collection $permissions): self
  {
    $this->permissions = $permissions;
    return $this;
  }
  
  /**
   * @param string $permissionId
   * @param bool $byCriteria
   * @return bool
   */
  public function hasPermission(string $permissionId, $byCriteria = true): bool
  {
    if(!$byCriteria) {
      return $this->permissions->containsKey($permissionId);
    }
    else {
      $criteria = Criteria::create();
      $criteria->where( Criteria::expr()->eq('id', $permissionId) );
      $permission = $this->permissions->matching($criteria);
      return !$permission->isEmpty();
    }
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
   * @return App
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
   * @param \DateTime $updatedAt
   * @return \App\Entity\App
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
   * @return App
   */
  public function setDeletedAt(\DateTime $deletedAt): self
  {
    $this->deletedAt = $deletedAt;
    return $this;
  }
  
  /**
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getUsers(): Collection
  {
    return $this->users;
  }
  
  /**
   * @param \Doctrine\Common\Collections\Collection $users
   * @return \App\Entity\App
   */
  public function setUsers(Collection $users): App
  {
    $this->users = $users;
    return $this;
  }
  
}