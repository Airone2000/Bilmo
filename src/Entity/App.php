<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\EntityListeners("App\Listener\AppListener")
 * @ORM\Table(name="app")
 * @ApiResource()
 */
class App implements UserInterface
{
  /**
   * @var null|\Ramsey\Uuid\UuidInterface
   *
   * @ORM\Id()
   * @ORM\Column(type="string", length=50)
   * @ORM\GeneratedValue(strategy="NONE")
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
   * App constructor.
   */
  function __construct()
  {
    $this->id = Uuid::uuid4();
    $this->secret = sha1(uniqid('3"az.]##Y8#[/YbY'));
    $this->roles = ['ROLE_CONSUMER'];
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
}