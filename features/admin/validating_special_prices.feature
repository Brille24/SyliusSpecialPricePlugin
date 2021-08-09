@ui @admin @special_prices @javascript
Feature: Validating special prices
    As an administrator
    I want the special prices to be validated

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator
        And the store has a product "Some Product" priced at "$20.00"

    Scenario: Adding special price leaving it blank
        Given I want to modify the "Some Product" product variant
        When I add a special price for channel "United States"
        And I save my changes
        Then I should be notified that the price field cannot be empty

    Scenario: Adding special price with invalid date range
        Given I want to modify the "Some Product" product variant
        When I add a special price for channel "United States"
        And I set the price to "$10.00" for channel "United States"
        And I set the start date to "tomorrow" for channel "United States"
        And I set the end date to "now" for channel "United States"
        And I save my changes
        Then I should be notified that the start date must be smaller than the end date

    Scenario: Adding multiple special prices with overlapping dates
        Given I want to modify the "Some Product" product variant
        When I add a special price for channel "United States"
        And I set the price to "$10.00" for channel "United States"
        And I set the start date to "now" for channel "United States"
        And I set the end date to "tomorrow" for channel "United States"

        And I add a special price for channel "United States"
        And I set the price to "$5.00" for channel "United States"
        And I set the start date to "yesterday" for channel "United States"
        And I set the end date to "tomorrow +1" for channel "United States"

        And I save my changes
        Then I should be notified that the dates cannot overlap

    Scenario: Adding special price with open start date
        Given I want to modify the "Some Product" product variant
        When I add a special price for channel "United States"
        And I set the price to "$10.00" for channel "United States"
        And I set the end date to "tomorrow" for channel "United States"
        And I save my changes

        Then I should be notified that it has been successfully edited
        And the "Some Product" variant should have 1 special price


    Scenario: Adding special price with open end date
        Given I want to modify the "Some Product" product variant
        When I add a special price for channel "United States"
        And I set the price to "$10.00" for channel "United States"
        And I set the start date to "tomorrow" for channel "United States"
        And I save my changes

        Then I should be notified that it has been successfully edited
        And the "Some Product" variant should have 1 special price

    Scenario: Adding special price with open start and end date
        Given I want to modify the "Some Product" product variant
        When I add a special price for channel "United States"
        And I set the price to "$10.00" for channel "United States"
        And I save my changes

        Then I should be notified that it has been successfully edited
        And the "Some Product" variant should have 1 special price

