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
    And the JSON node "id" should exist
    And I save it into "CATEGORY_ID"

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

  Scenario: Get all categories
    This action only exists for IRI generation but throws a 404

    When I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I add "accept" header equal to "application/json"
    And I send a "get" request to "/categories"
    Then the response status code should be 404

    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I add "accept" header equal to "application/json"
    And I send a "get" request to "/categories"
    Then the response status code should be 404

  Scenario: Get a category

    When I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I add "accept" header equal to "application/ld+json"
    And I send a "get" request to "/categories/<<CATEGORY_ID>>"
    Then the response status code should be 200

    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I add "accept" header equal to "application/ld+json"
    And I send a "get" request to "/categories/<<CATEGORY_ID>>"
    Then the response status code should be 200

  Scenario: Put a category

    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I add "content-type" header equal to "application/json"
    And I send a "put" request to "/categories/<<CATEGORY_ID>>" with body:
    """
    {
      "name":"NAME MODIFIED"
    }
    """
    Then the response status code should be 403

    When I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I add "content-type" header equal to "application/json"
    And I send a "put" request to "/categories/<<CATEGORY_ID>>" with body:
    """
    {
      "name":"NAME MODIFIED"
    }
    """
    Then the response status code should be 200
    And the JSON node "name" should be equal to "NAME MODIFIED"

  Scenario: Delete a category

    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "delete" request to "/categories/<<CATEGORY_ID>>"
    Then the response status code should be 403

    When I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "delete" request to "/categories/<<CATEGORY_ID>>"
    Then the response status code should be 204