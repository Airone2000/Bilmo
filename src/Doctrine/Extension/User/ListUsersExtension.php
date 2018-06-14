<?php declare(strict_types=1);

namespace App\Doctrine\Extension\User;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\App;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class ListUsersExtension
 *
 * @package App\Doctrine\Extension\User
 *
 * This Doctrine extension ensures that querying on user(s) only retrieves those who belong
 * to the same current user's App.
 *
 * For instance, as Bilmo, querying my users returns only Bilmo's users.
 *
 * It applies on both item and collection.
 */
class ListUsersExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
  /**
   * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
   */
  private $tokenStorage;
  
  function __construct(TokenStorageInterface $tokenStorage)
  {
    $this->tokenStorage = $tokenStorage;
  }
  
  public function applyToCollection(
    QueryBuilder $queryBuilder,
    QueryNameGeneratorInterface $queryNameGenerator,
    string $resourceClass,
    string $operationName = null
  ) {
    $this->limitToApp($queryBuilder, $resourceClass);
  }
  
  public function applyToItem(
    QueryBuilder $queryBuilder,
    QueryNameGeneratorInterface $queryNameGenerator,
    string $resourceClass,
    array $identifiers,
    string $operationName = null,
    array $context = []
  ) {
    $this->limitToApp($queryBuilder, $resourceClass);
  }
  
  private function limitToApp(QueryBuilder $queryBuilder, string $resourceclass): void
  {
    $app = $this->tokenStorage->getToken()->getUser();
    if($app instanceof App && $resourceclass === User::class) {
     $rootAlias = $queryBuilder->getRootAliases()[0];
     $queryBuilder
       ->andWhere( sprintf('%s.app = :app', $rootAlias) )
       ->setParameter('app', $app)
     ;
    }
  }
}