<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\App;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class A1_AppFixtures extends Fixture
{
  /**
   * @param \Doctrine\Common\Persistence\ObjectManager $manager
   */
  public function load(ObjectManager $manager): void
  {
    $app = new App();
    $app
      ->setId('f4573a81-4c53-499f-96d8-333e290e7474')
      ->setSecret('04e0b5837255551a446979564cb74db99377c26b')
      ->setRoles(['ROLE_MANAGER'])
    ;
    $manager->persist($app);
    $manager->flush();
  }
}