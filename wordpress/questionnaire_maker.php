<?php
/**
 * Plugin Name: Questionnaire Maker
 * Plugin URI: https://github.com/billthefarmer/questionnaire/
 * Description: Lets you create a questionnaire using your data.
 * Version: 0.5
 * Author: Bill Farmer
 * Author URI: https://github.com/billthefarmer/
 * License: MIT
 */

/*
 * Created by Bill Farmer
 * Licensed under the MIT.
 * Copyright (C) 2018 Bill Farmer
 */

// Add scripts hook, also adds shortcodes and further action
add_action('wp_enqueue_scripts', 'questionnaire_enqueue_scripts', 11);

// Queue scripts and styles. Wordpress includes jquery-ui script files,
// but not all the styles
function questionnaire_enqueue_scripts() {

    // Check on a page
    if (is_page()) {

        // Styles
        wp_enqueue_style('jquery-ui',
                         plugins_url('/css/jquery-ui.min.css', __FILE__));
        wp_enqueue_style('questionnaire',
                         plugins_url('/css/questionnaire.css', __FILE__));

        // Javascript
        wp_enqueue_script('jquery-ui-all',
                          plugins_url('/js/jquery-ui.min.js', __FILE__),
                          array('jquery'));

        // Add the shortcodes
        add_shortcode('questionnaire-questions',
                      'questionnaire_questions_shortcode');
        add_shortcode('questionnaire-report',
                      'questionnaire_report_shortcode');
    }

    if (is_page('Report')) {

        // Styles
        wp_enqueue_style('jquery-ui',
                         plugins_url('/css/jquery-ui.min.css', __FILE__));
        wp_enqueue_style('questionnaire',
                         plugins_url('/css/questionnaire.css', __FILE__));

        // Javascript
        wp_enqueue_script('answers',
                          plugins_url('/js/answers.js', __FILE__));
        wp_enqueue_script('report',
                          plugins_url('/js/report.js', __FILE__));
        wp_enqueue_script('jquery-ui-all',
                          plugins_url('/js/jquery-ui.min.js', __FILE__),
                          array('jquery'));

        // Add the shortcodes and actions to insert the code into the
        // page
    }
}

// Add the content if the shortcode is found.
function questionnaire_questions_shortcode($atts) {

    // Buffer the output
    ob_start();

    ?>
<div class="questionnaire-intro">
  <fieldset>
    <h4 id="questionnaire-intro"></h4>
    <input type="button" id="questionnaire-start"
           class="questionnaire-button" value="Begin!" />
  </fieldset>
</div>
<div class="questionnaire-question">
  <fieldset>
    <div id="questionnaire-progress"></div>
    <div class="questionnaire-centre">
      <h4 id="questionnaire-question"></h4>
      <input type="radio" id="question-radio-1" name="question-answer"
             class="question-radio" value="question-answer-1" />
      <label for="question-radio-1" class="question-label"
             id="question-label-1">
      </label><br />
      <input type="radio" id="question-radio-2" name="question-answer"
             class="question-radio" value="question-answer-2" />
      <label for="question-radio-2" class="question-label"
             id="question-label-2">
      </label><br /><br />
    </div>
    <input type="button" id="questionnaire-back"
           class="questionnaire-button" value="Back" />
  </fieldset>
</div>
<div class="questionnaire-last">
  <fieldset>
    <div id="questionnaire-progress-max"></div>
    <div class="questionnaire-centre">
      <h4 id="questionnaire-last"></h4>
      <input type="radio" id="last-radio-1" name="question-answer"
             class="question-last" value="last-answer-1" />
      <label for="last-radio-1" class="question-label"
             id="last-label-1">
      </label><br />
      <input type="radio" id="last-radio-2" name="question-answer"
             class="question-last" value="last-answer-2" />
      <label for="last-radio-2" class="question-label"
             id="last-label-2">
      </label><br />
      <input type="radio" id="last-radio-3" name="question-answer"
             class="question-last" value="last-answer-3" />
      <label for="last-radio-3" class="question-label"
             id="last-label-3">
      </label><br />
      <input type="radio" id="last-radio-4" name="question-answer"
             class="question-last" value="last-answer-4" />
      <label for="last-radio-4" class="question-label"
             id="last-label-4">
      </label><br />
      <input type="radio" id="last-radio-5" name="question-answer"
             class="question-last" value="last-answer-5" />
      <label for="last-radio-5" class="question-label"
             id="last-label-5">
      </label><br />
    </div>
    <input type="button" id="questionnaire-prev"
           class="questionnaire-button" value="Back" />
  </fieldset>
</div>
<div class="questionnaire-contact">
  <form action="" method="get" class="questionnaire-result">
    <input type="hidden" id="A" name="A" />
    <input type="hidden" id="B" name="B" />
    <input type="hidden" id="C" name="C" />
    <input type="hidden" id="D" name="D" />
    <input type="hidden" id="E" name="E" />
    <input type="hidden" id="F" name="F" />
    <input type="hidden" id="J" name="J" />
    <fieldset>
      <h3>Contact Information</h3>
      <table>
        <tr><td><label for="questionnaire-forename">First name: </label></td>
          <td><input type="text" id="questionnaire-forename"
                     name="questionnaire-forename" required></td></tr>
        <tr><td><label for="questionnaire-lastname">Last name: </label></td>
          <td><input type="text" id="questionnaire-lastname"
                     name="questionnaire-lastname" required></td></tr>
        <tr><td><label for="questionnaire-email">Email: </label></td>
          <td><input type="email" id="questionnaire-email"
                     name="questionnaire-email" required></td></tr>
      </table>
      <br />
      <input type="button" id="questionnaire-again"
             class="questionnaire-button" value="Again" />
      <input type="submit" id="questionnaire-submit"
             class="questionnaire-button" value="Results" />
    </fieldset>
  </form>
</div>
<?php

    $questions = plugins_url('/js/questions.js', __FILE__);
    $questionnaire = plugins_url('/js/questionnaire.js', __FILE__);

    echo "<script type=\"text/javascript\" src=\"$questions\"></script>
<script type=\"text/javascript\" src=\"$questionnaire\"></script>";

    // Return the output
    return ob_get_clean();
}

// Add the content if the shortcode is found.
function questionnaire_results_shortcode($atts) {

    // Buffer the output
    ob_start();

    ?>

<div class="report-content">
  <fieldset>
    <h2 id="user-name" class="user-name"></h2>
    <input type="button" id="update-preview" name="update-preview"
           class="questionnaire-button" value="Update Preview" />
    <input type="button" id="download-report" name="download-report"
           class="questionnaire-button" value="Download Report" />
    <br /><br />
  </fieldset>
</div>

<?php

    $answers = plugins_url('/js/answers.js', __FILE__);
    $report = plugins_url('/js/report.js', __FILE__);

    echo "<script type=\"text/javascript\" src=\"$answers\"></script>
<script type=\"text/javascript\" src=\"$report\"></script>";

    // Return the output
    return ob_get_clean();
}
