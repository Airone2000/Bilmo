<?php declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\App;
use App\Entity\Permission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AppVoter extends Voter
{
  /**
   * @param string $attribute
   * @param mixed $subject
   * @return bool
   */
  protected function supports($attribute, $subject): bool
  {
    
    if( in_array($attribute, [Permission::LIST_APPS, Permission::POST_APPS]) ) {
      return true;
    }
    
    if( in_array($attribute, [Permission::GET_APPS, Permission::DELETE_APPS]) && $subject instanceof App) {
      return true;
    }
    
    return false;
    
  }
  
  protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
  {
    switch ($attribute)
    {
      case Permission::LIST_APPS:
        return $this->canList($token->getUser());
        break;
        
      case Permission::POST_APPS:
        return $this->canPost($token->getUser());
        break;
        
      case Permission::GET_APPS:
        return $this->canGet($token->getUser());
        break;
        
      case Permission::DELETE_APPS:
        return $this->canDelete($token->getUser(), $subject);
        break;
    }
  }
  
  /**
   * @param \App\Entity\App $app
   * @return bool
   */
  private function canList(App $app): bool
  {
    return $app->hasPermission(Permission::LIST_APPS);
  }
  
  /**
   * @param \App\Entity\App $app
   * @return bool
   */
  public function canPost(App $app): bool
  {
    return $app->hasPermission(Permission::POST_APPS);
  }
  
  /**
   * @param \App\Entity\App $app
   * @return bool
   */
  public function canGet(App $app): bool
  {
    return $app->hasPermission(Permission::GET_APPS);
  }
  
  /**
   * @param \App\Entity\App $app
   * @param \App\Entity\App $appToDelete
   * @return bool
   */
  public function canDelete(App $app, App $appToDelete): bool
  {
    /**
     * An App cannot delete itself
     */
    return
      $app->hasPermission(Permission::DELETE_APPS) &&
      $app !== $appToDelete
    ;
  }
  
}