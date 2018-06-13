Feature: Manufacturers

  Scenario: New manufacturer
    Only Bilmo has the permission to add manufacturers.

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
      "name":"Samsung"
    }
    """
    Then the response status code should be 201
    And the JSON node "id" should exist
    And I save it into "MANUFACTURER_ID"

  Scenario: New manufacturer
    as unauthorized App

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
    And I send a "post" request to "/manufacturers" with body:
    """
    {
      "name":"Samsung"
    }
    """
    Then the response status code should be 403

  Scenario: New manufacturer
    With an existing name

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "post" request to "/manufacturers" with body:
    """
    {
      "name":"Samsung"
    }
    """
    Then the response status code should be 400
    And the JSON node "hydra:description" should be equal to "name: This value is already used."

    
  Scenario: GET manufacturers
    as authorized app
    
    Given I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I add "accept" header equal to "application/json"
    When I send a "get" request to "/manufacturers"
    Then the response status code should be 200

    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I add "accept" header equal to "application/json"
    When I send a "get" request to "/manufacturers"
    Then the response status code should be 200

  Scenario: GET one manufacturer

    Given I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I add "accept" header equal to "application/json"
    When I send a "get" request to "/manufacturers/<<MANUFACTURER_ID>>"
    Then the response status code should be 200

    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I add "accept" header equal to "application/json"
    When I send a "get" request to "/manufacturers/<<MANUFACTURER_ID>>"
    Then the response status code should be 200

  Scenario: Update a manufacturer
    as authorized

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "put" request to "/manufacturers/<<MANUFACTURER_ID>>" with body:
    """
    {
      "name":"Samsung 2"
    }
    """
    Then the response status code should be 200
    And the JSON node "name" should be equal to "Samsung 2"

  Scenario: Update a manufacturer
    as unauthorized app

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "put" request to "/manufacturers/<<MANUFACTURER_ID>>" with body:
    """
    {
      "name":"Samsung 3"
    }
    """
    Then the response status code should be 403

  Scenario: Delete a manufacturer
    as unauthorized

    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "delete" request to "/manufacturers/<<MANUFACTURER_ID>>"
    Then the response status code should be 403

  Scenario: Delete a manufacturer
    as authorized

    Given I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "delete" request to "/manufacturers/<<MANUFACTURER_ID>>"
    Then the response status code should be 204

  Scenario: Create a manufacturer (for testing)
    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "post" request to "/manufacturers" with body:
    """
    {
      "name":"Apple"
    }
    """
    Then the response status code should be 201

