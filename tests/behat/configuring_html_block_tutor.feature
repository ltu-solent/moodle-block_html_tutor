@block @block_html_tutor @core_block
Feature: Adding and configuring HTML blocks
  In order to have custom blocks on a page
  As admin
  I need to be able to create, configure and change HTML blocks

  @javascript
  Scenario: Configuring the HTML block with Javascript on
    Given I log in as "admin"
    And I am on site homepage
    When I turn editing mode on
    And I add the "HTML tutor" block
    And I configure the "(new HTML tutor block)" block
    And I set the field "Content" to "Static text without a header"
    And I press "Save changes"
    Then I should not see "(new HTML tutor block)"
    And I configure the "block_html_tutor" block
    And I set the field "Block title" to "The HTML tutor block header"
    And I set the field "Content" to "Static text with a header"
    And I press "Save changes"
    And "block_html_tutor" "block" should exist
    And "The HTML tutor block header" "block" should exist
    And I should see "Static text with a header" in the "The HTML tutor block header" "block"

  Scenario: Configuring the HTML block with Javascript off
    Given I log in as "admin"
    And I am on site homepage
    When I turn editing mode on
    And I add the "HTML tutor" block
    And I configure the "(new HTML tutor block)" block
    And I set the field "Content" to "Static text without a header"
    And I press "Save changes"
    Then I should not see "(new HTML tutor block)"
    And I configure the "block_html_tutor" block
    And I set the field "Block title" to "The HTML tutor block header"
    And I set the field "Content" to "Static text with a header"
    And I press "Save changes"
    And "block_html_tutor" "block" should exist
    And "The HTML tutor block header" "block" should exist
    And I should see "Static text with a header" in the "The HTML tutor block header" "block"
