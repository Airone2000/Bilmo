@allinone
Feature: BILMO API

  Scenario: Authenticate as Bilmo
    When I add "content-type" header equal to "application/json"
    And I send a "post" request to "/authentication" with body:
    """
    {
        "app_id" : "f4573a81-4c53-499f-96d8-333e290e7474",
        "app_secret" : "04e0b5837255551a446979564cb74db99377c26b"
    }
    """
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "token" should exist
    And I save it into "BILMO_AUTH_HEADER"

  Scenario: Authenticate as third Partner
    When I add "content-type" header equal to "application/json"
    And I send a "post" request to "/authentication" with body:
    """
    {
        "app_id" : "d3452bb9-d8cc-4d67-a4de-8b3eb78c5ad7",
        "app_secret" : "46979564cb74db99377c26b04e0b5837255551a4"
    }
    """
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "token" should exist
    And I save it into "PARTNER_AUTH_HEADER"

  Scenario: Create a new App
    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "post" request to "/apps" with body:
    """
    {}
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON node "id" should exist
    And I save it into "JUST_CREATED_APP_ID"

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "post" request to "/apps" with body:
    """
    {}
    """
    Then the response status code should be 403

  Scenario: Get Apps
    When I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "get" request to "/apps"
    Then the response status code should be 200

    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "get" request to "/apps"
    Then the response status code should be 403

  Scenario: Get an App
    When I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "get" request to "/apps/<<JUST_CREATED_APP_ID>>"
    Then the response status code should be 200

    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "get" request to "/apps/<<JUST_CREATED_APP_ID>>"
    Then the response status code should be 403

  Scenario: Delete an App
    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "delete" request to "/apps/<<JUST_CREATED_APP_ID>>"
    Then the response status code should be 403

    When I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "delete" request to "/apps/<<JUST_CREATED_APP_ID>>"
    Then the response status code should be 204

  Scenario: Create a manufacturer (and testing unique constraint)
    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "post" request to "/manufacturers" with body:
    """
    {}
    """
    Then the response status code should be 403

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "post" request to "/manufacturers" with body:
    """
    {
        "name": "Manufacturer 1"
    }
    """
    Then the response status code should be 201
    And the JSON node "id" should exist
    And I save it into "JUST_CREATED_MANUFACTURER"

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "post" request to "/manufacturers" with body:
    """
    {
        "name": "Manufacturer 1"
    }
    """
    Then the response status code should be 400

  Scenario: Get manufacturers
    When I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "get" request to "/manufacturers"
    Then the response status code should be 200

    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "get" request to "/manufacturers"
    Then the response status code should be 200

  Scenario: Get a manufacturer
    When I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "get" request to "/manufacturers/<<JUST_CREATED_MANUFACTURER>>"
    Then the response status code should be 200

    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "get" request to "/manufacturers/<<JUST_CREATED_MANUFACTURER>>"
    Then the response status code should be 200

  Scenario: Update a manufacturer
    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "put" request to "/manufacturers/<<JUST_CREATED_MANUFACTURER>>" with body:
    """
    {}
    """
    Then the response status code should be 403

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "put" request to "/manufacturers/<<JUST_CREATED_MANUFACTURER>>" with body:
    """
    {
        "name": "Manufacturer 1 MODIFIED"
    }
    """
    Then the response status code should be 200
    And the JSON node "name" should be equal to "Manufacturer 1 MODIFIED"

  Scenario: Delete a manufacturer
    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "delete" request to "/manufacturers/<<JUST_CREATED_MANUFACTURER>>"
    Then the response status code should be 403

    When I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "delete" request to "/manufacturers/<<JUST_CREATED_MANUFACTURER>>"
    Then the response status code should be 204

  Scenario: Create a manufacturer for the remaining tests
    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "post" request to "/manufacturers" with body:
    """
    {
        "name": "Mobilexe"
    }
    """
    Then the response status code should be 201
    And the JSON node "@id" should exist
    And I save it into "MANUFACTURER_IRI"

  Scenario: Create a category
    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "post" request to "/categories" with body:
    """
    {}
    """
    Then the response status code should be 403

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "post" request to "/categories" with body:
    """
    {
        "name":"Petits smartphones",
        "manufacturer": "<<MANUFACTURER_IRI>>"
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON node "@id" should exist
    And I save it into "CATEGORY_IRI"
    And the JSON node "id" should exist
    And I save it into "CATEGORY_ID"

  Scenario: Get categories
    When I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "get" request to "/categories"
    Then the response status code should be 404

    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "get" request to "/categories"
    Then the response status code should be 404

  Scenario: Get a category
    When I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "get" request to "/categories/<<CATEGORY_ID>>"
    Then the response status code should be 200

    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "get" request to "/categories/<<CATEGORY_ID>>"
    Then the response status code should be 200

  Scenario: Update a category
    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "put" request to "/categories/<<CATEGORY_ID>>" with body:
    """
    {}
    """
    Then the response status code should be 403

    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "put" request to "/categories/<<CATEGORY_ID>>" with body:
    """
    {
        "name":"Smartphones"
    }
    """
    Then the response status code should be 200
    And the JSON node "name" should be equal to "Smartphones"

  Scenario: Delete a category
    When I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "delete" request to "/categories/<<CATEGORY_ID>>"
    Then the response status code should be 403

    When I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "delete" request to "/categories/<<CATEGORY_ID>>"
    Then the response status code should be 204

  Scenario: Create a category for the remaining tests
    When I add "content-type" header equal to "application/json"
    And I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "post" request to "/categories" with body:
    """
    {
        "name":"Téléphones tactiles",
        "manufacturer": "<<MANUFACTURER_IRI>>"
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON node "@id" should exist
    And I save it into "CATEGORY_IRI"

  Scenario: Create a product
    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I add "content-type" header equal to "application/json"
    And I send a "post" request to "/products" with body:
    """
    {}
    """
    Then the response status code should be 403

    Given I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I add "content-type" header equal to "application/json"
    And I send a "post" request to "/products" with body:
    """
    {
        "name": "Samphone 5P",
        "manufacturer": "<<MANUFACTURER_IRI>>",
        "categories": ["<<CATEGORY_IRI>>"],
        "description": "Good phone!"
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON node "id" should exist
    And I save it into "PRODUCT_ID"

  Scenario: Get products
    Given I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "get" request to "/products"
    Then the response status code should be 200

    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "get" request to "/products"
    Then the response status code should be 200

  Scenario: Get a product
    Given I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "get" request to "/products/<<PRODUCT_ID>>"
    Then the response status code should be 200

    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "get" request to "/products/<<PRODUCT_ID>>"
    Then the response status code should be 200

  Scenario: Update a product
    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I add "content-type" header equal to "application/json"
    And I send a "put" request to "/products/<<PRODUCT_ID>>" with body:
    """
    {}
    """
    Then the response status code should be 403

    Given I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I add "content-type" header equal to "application/json"
    And I send a "put" request to "/products/<<PRODUCT_ID>>" with body:
    """
    {
        "description": "Very very good phone!"
    }
    """
    Then the response status code should be 200
    And the JSON node "description" should be equal to "Very very good phone!"

  Scenario: Delete a product
    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "delete" request to "/products/<<PRODUCT_ID>>"
    Then the response status code should be 403

    Given I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "delete" request to "/products/<<PRODUCT_ID>>"
    Then the response status code should be 204

  Scenario: Create users (testing username unicity globally/locally)
    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I add "content-type" header equal to "application/json"
    When I send a "post" request to "/users" with body:
    """
    {
        "username": "Utilisateur1",
        "emailAddress": "utilisateur1@email.com",
        "plainPassword": "123",
        "plainPasswordConfirm": "123"
    }
    """

    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I add "content-type" header equal to "application/json"
    When I send a "post" request to "/users" with body:
    """
    {
        "username": "Utilisateur2",
        "emailAddress": "utilisateur2@email.com",
        "plainPassword": "123",
        "plainPasswordConfirm": "123"
    }
    """

    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I add "content-type" header equal to "application/json"
    When I send a "post" request to "/users" with body:
    """
    {
        "username": "Utilisateur2",
        "emailAddress": "utilisateur-double@email.com",
        "plainPassword": "123",
        "plainPasswordConfirm": "123"
    }
    """
    Then the response status code should be 400
    And the JSON node "hydra:description" should be equal to "username: This value is already used."

    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I add "content-type" header equal to "application/json"
    When I send a "post" request to "/users" with body:
    """
    {
        "username": "Utilisateur3",
        "emailAddress": "utilisateur3@email.com",
        "plainPassword": "123",
        "plainPasswordConfirm": "123"
    }
    """
    Then the JSON node "id" should exist
    And I save it into "PARTNER_USER_ID"

    Given I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I add "content-type" header equal to "application/json"
    When I send a "post" request to "/users" with body:
    """
    {
        "username": "Utilisateur3",
        "emailAddress": "utilisateur3@email.com",
        "plainPassword": "123",
        "plainPasswordConfirm": "123"
    }
    """
    Then the response status code should be 201

  Scenario: Get users
    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    When I send a "get" request to "/users"
    Then the response status code should be 200
    And the JSON node "hydra:totalItems" should be equal to 3

    Given I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    When I send a "get" request to "/users"
    Then the response status code should be 200
    And the JSON node "hydra:totalItems" should be equal to 1

  Scenario: Get User
    Given I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    When I send a "get" request to "/users/<<PARTNER_USER_ID>>"
    Then the response status code should be 404

    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    When I send a "get" request to "/users/<<PARTNER_USER_ID>>"
    Then the response status code should be 200

  Scenario: Update an User
    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I add "content-type" header equal to "application/json"
    When I send a "put" request to "/users/<<PARTNER_USER_ID>>" with body:
    """
    {
        "username": "Utilisateur3 MODIFIED"
    }
    """
    Then the response status code should be 200
    And the JSON node "username" should be equal to "Utilisateur3 MODIFIED"

    Given I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I add "content-type" header equal to "application/json"
    When I send a "put" request to "/users/<<PARTNER_USER_ID>>" with body:
    """
    {}
    """
    Then the response status code should be 404

  Scenario: Delete an user
    Given I add "authorization" header equal to "Bearer <<BILMO_AUTH_HEADER>>"
    And I send a "delete" request to "/users/<<PARTNER_USER_ID>>"
    Then the response status code should be 404

    Given I add "authorization" header equal to "Bearer <<PARTNER_AUTH_HEADER>>"
    And I send a "delete" request to "/users/<<PARTNER_USER_ID>>"
    Then the response status code should be 204