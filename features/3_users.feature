Feature: Users

  Scenario: Create an user

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
    And I send a "post" request to "/users" with body:
    """
    {
      "username": "Erwan",
      "emailAddress": "myemail@gmail.com",
      "plainPassword": "123",
      "plainPasswordConfirm": "123"
    }
    """
    Then the response status code should be 201
    And the JSON node "id" should exist
    And I save it into "BILMO_USER_ID"
    And the JSON node "username" should exist
    And the JSON node "emailAddress" should exist
    And the JSON node "password" should not exist

  # ------------------------------

  Scenario: Get my users (list)

    When I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I add "accept" header equal to "application/ld+json"
    And I send a "get" request to "/users"
    Then the response status code should be 200
    And the JSON node "hydra:totalItems" should be equal to 1

  # ------------------------------

  Scenario: Make sure users are well-compartimentalized

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

    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I add "accept" header equal to "application/ld+json"
    And I send a "get" request to "/users"
    Then the response status code should be 200
    And the JSON node "hydra:totalItems" should be equal to 0

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "post" request to "/users" with body:
    """
    {
      "username": "User1",
      "emailAddress": "user1@gmail.com",
      "plainPassword": "123",
      "plainPasswordConfirm": "123"
    }
    """

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "post" request to "/users" with body:
    """
    {
      "username": "User2",
      "emailAddress": "user2@gmail.com",
      "plainPassword": "123",
      "plainPasswordConfirm": "123"
    }
    """

    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I add "accept" header equal to "application/ld+json"
    And I send a "get" request to "/users"
    Then the response status code should be 200
    And the JSON node "hydra:totalItems" should be equal to 2

  # ------------------------------
    
  Scenario: Get my User
    
    When I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I add "accept" header equal to "application/ld+json"
    And I send a "get" request to "/users/<<BILMO_USER_ID>>"
    Then the response status code should be 200

  # ------------------------------
    
  Scenario: User deletion
    
    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "post" request to "/users" with body:
    """
    {
      "username": "User3",
      "emailAddress": "user3@gmail.com",
      "plainPassword": "123",
      "plainPasswordConfirm": "123"
    }
    """
    And the JSON node "id" should exist
    And I save it into "USER3_ID"
    
    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "delete" request to "/users/<<USER3_ID>>"
    Then the response status code should be 404

    When I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "delete" request to "/users/<<USER3_ID>>"
    Then the response status code should be 204

  # ------------------------------

  Scenario: Put an user

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "post" request to "/users" with body:
    """
    {
      "username": "User4",
      "emailAddress": "user4@gmail.com",
      "plainPassword": "123",
      "plainPasswordConfirm": "123"
    }
    """
    And the JSON node "id" should exist
    And I save it into "USER4_ID"

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "put" request to "users/<<USER4_ID>>" with body:
    """
    {
      "username": "USER444"
    }
    """
    Then the response status code should be 404

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "put" request to "users/<<USER4_ID>>" with body:
    """
    {
      "username": "USER444"
    }
    """
    Then the response status code should be 200
    And the JSON node "username" should be equal to "USER444"