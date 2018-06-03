<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="permission")
 * @ApiResource(
 *   collectionOperations={
 *      "GET"={
 *          "controller"=App\Controller\HttpNotFoundAction::class
 *      }
 *   },
 *   itemOperations={
 *      "GET"={
 *          "controller"=App\Controller\HttpNotFoundAction::class
 *      }
 *   }
 * )
 */
class Permission
{
  /**
   * List of available permissions
   */
  const
    LIST_APPS = 'list_apps',
    POST_APPS = 'post_apps',
    GET_APPS = 'get_apps',
    DELETE_APPS = 'delete_apps',
  
    POST_USERS = 'post_users',
    LIST_USERS = 'list_users',
    GET_USERS = 'get_users',
    DELETE_USERS = 'delete_users',
    PUT_USERS = 'put_users',
    
    POST_MANUFACTURERS = 'post_manufacturers',
    LIST_MANUFACTURERS = 'list_manufacturers',
    GET_MANUFACTURERS = 'get_manufacturers',
    DELETE_MANUFACTURERS = 'delete_manufacturers',
    PUT_MANUFACTURERS = 'put_manufacturers',
  
    POST_CATEGORIES = 'post_categories',
    GET_CATEGORIES = 'get_categories',
    PUT_CATEGORIES = 'put_categories',
    DELETE_CATEGORIES = 'delete_categories',
  
    POST_PRODUCTS = 'post_products',
    LIST_PRODUCTS = 'list_products',
    GET_PRODUCTS = 'get_products',
    PUT_PRODUCTS = 'put_products',
    DELETE_PRODUCTS = 'delete_products'
  ;
  
  /**
   * @var null|string
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="NONE")
   * @ORM\Column(type="string", length=50)
   */
  private $id;
  
  /**
   * @var null|string
   * @ORM\Column(type="string", nullable=true)
   */
  private $description;
  
  /**
   * @return null|string
   */
  public function getId(): ?string
  {
    return $this->id;
  }
  
  /**
   * @param string $id
   * @return \App\Entity\Permission
   */
  public function setId(string $id): self
  {
    $this->id = $id;
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
   * @return \App\Entity\Permission
   */
  public function setDescription(?string $description): self
  {
    $this->description = $description;
    return $this;
  }
  
  
}