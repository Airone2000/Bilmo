<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class HttpNotFoundAction
 *
 * This class triggers a NotFoundHttpException useful when we must keep
 * GET (collection/item operations) for IRI generation but we do not want
 * any user to go further.
 *
 * It finally results in a 404 Error (HttpNotFound)
 */
class HttpNotFoundAction extends AbstractController
{
  function __invoke()
  {
    throw new NotFoundHttpException();
  }
}