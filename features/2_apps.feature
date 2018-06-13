Feature: Apps

  Scenario: Authentication as authorized App
    To SCRUD Apps, I need to signin as authorized App.
    Authorized Apps are Apps which are granted with the
    permission "apps_list". Bilmo is one of them ...

    When I add "content-type" header equal to "application/json"
    And I send a "post" request to "/authentication" with body:
    """
    {
      "app_id" : "f4573a81-4c53-499f-96d8-333e290e7474",
      "app_secret" : "04e0b5837255551a446979564cb74db99377c26b"
    }
    """
    Then the JSON node "token" should exist
    And I save it into "AUTH_HEADER"

  # ------------------------------
    
  Scenario: Get Apps
    Without authorization header ...
    
    When I add "accept" header equal to "application/ld+json"
    And I send a "get" request to "/apps"
    Then the response status code should be 401
    And the JSON node "message" should be equal to "JWT Token not found"

  # ------------------------------

  Scenario: Get Apps

    When I add "accept" header equal to "application/ld+json"
    And I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "get" request to "/apps"
    Then the response status code should be 200

  # ------------------------------
  
  Scenario: Post Apps
    
    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "post" request to "/apps" with body:
    """
    {}
    """
    Then the response status code should be 201
    And the JSON node "id" should exist
    And I save it into "APP1_ID"

  # ------------------------------
  
  Scenario: Get App
    
    When I add "accept" header equal to "application/ld+json"
    And I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "get" request to "/apps/<<APP1_ID>>"
    Then the response status code should be 200
    And the JSON node "permissions" should exist

   # ------------------------------
  
  Scenario: Delete App
    
    When I add "authorization" header equal to "Bearer <<AUTH_HEADER>>"
    And I send a "delete" request to "/apps/<<APP1_ID>>"
    Then the response status code should be 204
