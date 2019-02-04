@ui @admin @special_prices
Feature: Validating special prices
    In order to offer time based specials
    As an administrator
    I want to be able to add special prices to my products

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator
        And the store has a product "Some Product" priced at "$10.00"
        And the product "Some Product" has "Test" variant priced at "$20.00"
        And the "Test" variant has a special price for channel "United States" priced at 1000 thats valid from "now" till "tomorrow"

    Scenario: Adding special price leaving it blank
        Given I want to modify the "Test" product variant
        When I add a special price for channel "United States"
        And I save my changes
        Then I should be notified that the price field cannot be empty

    Scenario: Adding special price with invalid date range
        Given I want to modify the "Test" product variant
        When I add a special price for channel "United States"
        And I set the start date to "tomorrow" for channel "United States"
        And I set the end date to "now" for channel "United States"
        And I save my changes
        Then I should be notified that the start date must be smaller than the end date

    Scenario: Adding multiple special prices with overlapping dates
        Given I want to modify the "Test" product variant
        When I add a special price for channel "United States"
        And I set the start date to "now" for channel "United States"
        And I set the end date to "tomorrow" for channel "United States"

        And I add a special price for channel "United States"
        And I set the start date to "yesterday" for channel "United States"
        And I set the end date to "tomorrow +1" for channel "United States"

        And I save my changes
        Then I should be notified that the dates cannot overlap
