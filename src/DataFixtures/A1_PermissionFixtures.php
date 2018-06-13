<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class A1_PermissionFixtures extends Fixture
{
  public function load(ObjectManager $manager)
  {
    $permissions = [
      [Permission::LIST_APPS, 'Retrieves the collection of App resources.'],
      [Permission::POST_APPS, 'Creates a App resource.'],
      [Permission::GET_APPS, 'Retrieves a App resource.'],
      [Permission::DELETE_APPS, 'Removes the App resource.'],
      
      [Permission::POST_USERS, 'Creates a User resource.'],
      [Permission::LIST_USERS, 'Retrieves the collection of User resources.'],
      [Permission::GET_USERS, 'Retrieves a User resource.'],
      [Permission::DELETE_USERS, 'Removes the User resource.'],
      [Permission::PUT_USERS, 'Replaces the User resource.'],
      
      [Permission::POST_MANUFACTURERS, 'Creates a Manufacturer resource.'],
      [Permission::LIST_MANUFACTURERS, 'Retrieves the collection of Manufacturer resources.'],
      [Permission::GET_MANUFACTURERS, 'Retrieves a Manufacturer resource.'],
      [Permission::PUT_MANUFACTURERS, 'Replaces the Manufacturer resource.'],
      [Permission::DELETE_MANUFACTURERS, 'Removes the Manufacturer resource.'],
      
      [Permission::POST_CATEGORIES, 'Creates a Category resource.'],
      [Permission::GET_CATEGORIES, 'Retrieves a Category resource.'],
      [Permission::PUT_CATEGORIES, 'Replaces the Category resource.'],
      [Permission::DELETE_CATEGORIES, 'Removes the Category resource.'],
      
      [Permission::POST_PRODUCTS, 'Creates a Product resource.'],
      [Permission::LIST_PRODUCTS, 'Retrieves the collection of Product resources.'],
      [Permission::GET_PRODUCTS, 'Retrieves a Product resource.'],
      [Permission::PUT_PRODUCTS, 'Replaces the Product resource.'],
      [Permission::DELETE_PRODUCTS, 'Removes the Product resource.']
    ];
    
    foreach ($permissions as $permissionData) {
      
      $permission = new Permission();
      $permission
        ->setId($permissionData[0])
        ->setDescription($permissionData[1])
      ;
      $manager->persist($permission);
    }
    
    $manager->flush();
  }
}