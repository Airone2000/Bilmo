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

