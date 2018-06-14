<?php declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\App;
use App\Entity\Category;
use App\Entity\Permission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class CategoryVoter
 *
 * @package App\Security\Voter
 *
 * This voter ensures that operations on Category are authorized based on the App permissions.
 * It is called when the is_granted() method is called, through ExpressionLanguage, in the
 * ApiResource annotation.
 */
class CategoryVoter extends Voter
{
  
  protected function supports($attribute, $subject): bool
  {
    if( in_array($attribute, [Permission::POST_CATEGORIES]) ) {
      return true;
    }
    
    if( in_array($attribute, [Permission::GET_CATEGORIES, Permission::PUT_CATEGORIES, Permission::DELETE_CATEGORIES]) && $subject instanceof Category) {
      return true;
    }
    
    return false;
  }
  
  protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
  {
      switch ($attribute)
      {
        case Permission::POST_CATEGORIES:
          return $this->canPost($token->getUser());
          break;
          
        case Permission::GET_CATEGORIES:
          return $this->canGet($token->getUser(), $subject);
          break;
          
        case Permission::PUT_CATEGORIES:
          return $this->canPut($token->getUser(), $subject);
          break;
          
        case Permission::DELETE_CATEGORIES:
          return $this->canDelete($token->getUser(), $subject);
          break;
      }
      
      return false;
  }
  
  private function canDelete(App $app, Category $category): bool
  {
    return $app->hasPermission(Permission::DELETE_CATEGORIES);
  }
  
  private function canPut(App $app, Category $category): bool
  {
    return $app->hasPermission(Permission::PUT_CATEGORIES);
  }
  
  private function canGet(App $app, Category $category): bool
  {
    return $app->hasPermission(Permission::GET_CATEGORIES);
  }
  
  private function canPost(App $app): bool
  {
    return $app->hasPermission(Permission::POST_CATEGORIES);
  }
}