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
    And the JSON node "username" should exist
    And the JSON node "emailAddress" should exist
    And the JSON node "password" should not exist

