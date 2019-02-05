@ui @admin @special_prices @javascript
Feature: Managing special prices
    In order to offer time based specials
    As an administrator
    I want to be able to add special prices to my products

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator
        And the store has a product "Some Product" priced at "$20.00"
        And the "Some Product" variant has a special price for channel "United States" priced at 1000 thats valid from "now" till "tomorrow"

    Scenario: Add special price
        Given I want to modify the "Some Product" product variant
        When I add a special price for channel "United States"
        And I set the price to "$5.00" for channel "United States"
        And I set the start date to "yesterday" for channel "United States"
        And I set the end date to "now" for channel "United States"
        And I save my changes

        Then I should be notified that it has been successfully edited
        And the "Some Product" variant should have 2 special prices

    Scenario: Add special price for different channel
        Given the store operates on another channel named "Germany"
        And I want to modify the "Some Product" product variant
        When I add a special price for channel "Germany"
        And I set the price to "$5.00" for channel "Germany"
        And I set the start date to "yesterday" for channel "Germany"
        And I set the end date to "now" for channel "Germany"
        And I save my changes

        Then I should be notified that it has been successfully edited
        And the "Some Product" variant should have 2 special prices
        And the "Some Product" variant should have 1 special price for channel "United States"
        And the "Some Product" variant should have 1 special price for channel "Germany"

    Scenario: Remove special price
        Given I want to modify the "Some Product" product variant
        When I remove a special price for channel "United States"
        And I save my changes

        Then I should be notified that it has been successfully edited
        And the "Some Product" variant should have 0 special prices
