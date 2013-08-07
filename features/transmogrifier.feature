Feature: Test the transmogrifier from Gherkin
  In order to test databases in a known state
  As an tester / Gherkin .feature file writer
  I want to apply datasets to databases using Gherkin sentences

  Scenario: Applying a yml dataset to the `test` database
    Given I connect to database "test"
     When I apply dataset "user.yml"
     Then I should have "2" records in the "user" table
