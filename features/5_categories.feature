Feature: Categories

  Scenario: Create a manufacturer and give it categories

    Given I add "content-type" header equal to "application/json"
    And I send a "post" request to "/authentication" with body:
    """
    {
      "app_id" : "f4573a81-4c53-499f-96d8-333e290e7474",
      "app_secret" : "04e0b5837255551a446979564cb74db99377c26b"
    }
    """
    Then the JSON node "token" should exist
    And I save it into "AUTH_HEADER"

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "post" request to "/manufacturers" with body:
    """
    {
      "name":"Mobistar"
    }
    """
    Then the response status code should be 201
    And the JSON node "@id" should exist
    And I save it into "MANUFACTURER_IRI"

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "post" request to "/categories" with body:
    """
    {
      "name":"Categorie 1",
      "manufacturer": "<<MANUFACTURER_IRI>>"
    }
    """
    Then the response status code should be 201

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "post" request to "/categories" with body:
    """
    {
      "name":"Categorie 2",
      "manufacturer": "<<MANUFACTURER_IRI>>"
    }
    """
    Then the response status code should be 201

    Given I add "content-type" header equal to "application/json"
    And I send a "post" request to "/authentication" with body:
    """
    {
      "app_id" : "d3452bb9-d8cc-4d67-a4de-8b3eb78c5ad7",
      "app_secret" : "46979564cb74db99377c26b04e0b5837255551a4"
    }
    """
    Then the JSON node "token" should exist
    And I save it into "PARTNER_AUTH_HEADER"

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "post" request to "/categories" with body:
    """
    {
      "name":"Categorie 3",
      "manufacturer": "<<MANUFACTURER_IRI>>"
    }
    """
    Then the response status code should be 403