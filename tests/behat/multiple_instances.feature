@block @block_html_tutor @javascript
Feature: Adding and configuring HTML blocks
  In order to have one or multiple HTML blocks on a page
  As admin
  I need to be able to create, configure and change HTML blocks

  Background:
    Given I log in as "admin"
    And the following "users" exist:
    | username | firstname | lastname | email                | department |
    | teacher1 | Terry1    | Teacher1 | teacher1@example.com | academic   |
    | student1 | Sam1      | Student1 | student1@example.com | student    |
    And I am on site homepage
    When I turn editing mode on
    And I add the "HTML tutor" block

  Scenario: Other users can not see HTML block that has not been configured
    Then "(new HTML tutor block)" "block" should exist
    And I log out
    When I log in as "student1"
    And I am on site homepage
    Then "(new HTML tutor block)" "block" should not exist
    And "block_html_tutor" "block" should not exist
    And I log out
    When I log in as "teacher1"
    And I am on site homepage
    Then "(new HTML tutor block)" "block" should not exist
    And "block_html_tutor" "block" should not exist

  Scenario: Other users can see HTML block that has been configured even when it has no header
    And I configure the "(new HTML tutor block)" block
    And I set the field "Content" to "Static text without a header"
    And I press "Save changes"
    Then I should not see "(new HTML tutor block)"
    And I log out
    When I log in as "teacher1"
    And I am on site homepage
    Then "block_html_tutor" "block" should exist
    And I should see "Static text without a header" in the "block_html_tutor" "block"
    And I should not see "(new HTML tutor block)"
    And I log out
    When I log in as "student1"
    And I am on site homepage
    Then "block_html_tutor" "block" should not exist
    And I should not see "Static text without a header"

  Scenario: Adding multiple instances of HTML block on a page
    And I configure the "block_html_tutor" block
    And I set the field "Block title" to "The HTML tutor block header"
    And I set the field "Content" to "Static text with a header"
    And I press "Save changes"
    And I add the "HTML tutor" block
    And I configure the "(new HTML tutor block)" block
    And I set the field "Block title" to "The second HTML tutor block header"
    And I set the field "Content" to "Second block contents"
    And I press "Save changes"
    And I log out
    When I log in as "teacher1"
    And I am on site homepage
    Then I should see "Static text with a header" in the "The HTML tutor block header" "block"
    And I should see "Second block contents" in the "The second HTML tutor block header" "block"
    And I log out
    When I log in as "student1"
    And I am on site homepage
    Then I should not see "Static text with a header"
    And I should not see "Second block contents"
