@ui @shop @special_prices
Feature: Special prices are applied
    As a customer
    I want to benefit from special prices

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "Some Product" priced at "$20.00"

    Scenario: Active special price is applied
        Given the "Some Product" variant has a special price for channel "United States" priced at 1000 thats valid from "now" till "tomorrow"
        When I view product "Some Product"
        Then I should see the product price "$10.00"

        When I add this product to the cart
        And I see the summary of my cart
        Then my cart's total should be "$10.00"

    Scenario: Inactive special price is not applied
        Given the "Some Product" variant has a special price for channel "United States" priced at 1000 thats valid from "2 days ago" till "yesterday"
        When I view product "Some Product"
        Then I should see the product price "$20.00"

        When I add this product to the cart
        And I see the summary of my cart
        Then my cart's total should be "$20.00"
