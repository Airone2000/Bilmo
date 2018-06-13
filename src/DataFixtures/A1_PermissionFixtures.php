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
      [Permission::PUT_USERS, 'Replaces the User resource.']
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