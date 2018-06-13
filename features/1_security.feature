Feature: Security

  Scenario: Authenticate as Manager (Bilmo)
    When I add "content-type" header equal to "application/json"
    And I add "accept" header equal to "application/ld+json"
    And I send a "post" request to "/authentication" with body:
    """
    {
      "app_id" : "f4573a81-4c53-499f-96d8-333e290e7474",
      "app_secret" : "04e0b5837255551a446979564cb74db99377c26b"
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "token" should exist
