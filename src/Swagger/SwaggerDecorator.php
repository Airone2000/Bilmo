<?php declare(strict_types=1);

namespace App\Swagger;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class SwaggerDecorator
 *
 * @package App\Swagger
 *
 * This decorator demonstrated how to decorate the ApiDoc.
 *
 * /!\ Before decorating for hiding, we MUST ensure that hidden operations
 * result in a 404.
 *
 * Otherwise, it represents a potential security issue.
 */
final class SwaggerDecorator implements NormalizerInterface
{
  private $decorated;
  
  public function __construct(NormalizerInterface $decorated)
  {
    $this->decorated = $decorated;
  }
  
  public function normalize($object, $format = null, array $context = [])
  {
    $docs = $this->decorated->normalize($object, $format, $context);
    
    // Following routes (actions) are enabled only for IRI generation.
    // They're definitely not designed to be any useful action and thus
    // they're not meant to appear in the doc.
    unset($docs['paths']['/permissions']);
    unset($docs['paths']['/permissions/{id}']);
    
    unset($docs['paths']['/categories']['get']);
    
    // Adding authentication route
    $docs['paths']['/authentication'] = [
      'post' => [
        'summary' => 'Signin to utilize the API.',
        'tags' => ['Security'],
        'parameters' => [
          [
            'name' => 'app_id',
            'in' => 'body',
            'description' => 'The APP ID you received from Bilmo.'
          ],
          [
            'name' => 'app_secret',
            'in' => 'body',
            'description' => 'The APP SECRET you recevied from us.'
          ]
        ]
      ]
    ];
    
    
    return $docs;
  }
  
  public function supportsNormalization($data, $format = null)
  {
    return $this->decorated->supportsNormalization($data, $format);
  }
}