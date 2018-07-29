<?php
/**
 * Plugin Name: Quizzer
 * Plugin URI: https://github.com/billthefarmer/quiz/
 * Description: Lets you create a quiz using your data.
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
add_action('wp_enqueue_scripts', 'quiz_enqueue_scripts', 11);

// Queue scripts and styles. Wordpress includes jquery-ui script files,
// but not all the styles
function quiz_enqueue_scripts() {

    // Check on a page

    if (is_page()) {

        wp_enqueue_style('jquery-ui',
                         plugins_url('/css/jquery-ui.min.css', __FILE__));
        wp_enqueue_style('quiz',
                         plugins_url('/css/quiz.css', __FILE__));

        wp_enqueue_script('quiz',
                          plugins_url('/js/quiz.js', __FILE__),
                          array('jquery-ui-core', 'jquery-ui-widget',
                                'jquery-ui-mouse', 'jquery-ui-button',
                                'jquery-ui-progressbar', 'jquery-effects-core',
                                'jquery'));

        // Add the shortcodes and actions to insert the code into the
        // page
        add_shortcode('quiz-questions', 'quiz_questions_shortcode');
        add_shortcode('quiz-results', 'quiz_results_shortcode');
        add_action('wp_footer', 'quiz_footer', 11);
    }
}

// Add the content if the shortcode is found.
function quiz_questions_shortcode($atts) {

    // Buffer the output
    ob_start();

    ?>
<div class="quiz-intro">
  <fieldset>
    <h4 id="quiz-intro"></h4>
    <input type="button" id="quiz-start" class="quiz-button" value="Begin!" />
  </fieldset>
</div>
<div class="quiz-question">
  <fieldset>
    <div id="quiz-progress"></div>
    <h4 id="quiz-question"></h4>
    <input type="radio" id="quiz-radio-1" name="quiz-answer"
           class="quiz-answer" value="quiz-answer-1" />
    <label for="quiz-radio-1" class="quiz-label" id="quiz-label-1">
    </label><br />
    <input type="radio" id="quiz-radio-2" name="quiz-answer"
           class="quiz-answer" value="quiz-answer-2" />
    <label for="quiz-radio-2" class="quiz-label" id="quiz-label-2">
    </label><br /><br />
    <input type="button" id="quiz-back" class="quiz-button" value="Back" />
  </fieldset>
</div>
<div class="quiz-last">
  <fieldset>
    <div id="quiz-progress-max"></div>
    <h4 id="quiz-last"></h4>
    <input type="radio" id="quiz-radio-3" name="quiz-last"
           class="quiz-last" value="quiz-answer-3" />
    <label for="quiz-radio-3" class="quiz-label" id="quiz-label-3">
    </label><br />
    <input type="radio" id="quiz-radio-4" name="quiz-last"
           class="quiz-last" value="quiz-answer-4" />
    <label for="quiz-radio-4" class="quiz-label" id="quiz-label-4">
    </label><br />
    <input type="radio" id="quiz-radio-5" name="quiz-last"
           class="quiz-last" value="quiz-answer-5" />
    <label for="quiz-radio-5" class="quiz-label" id="quiz-label-5">
    </label><br />
    <input type="radio" id="quiz-radio-6" name="quiz-last"
           class="quiz-last" value="quiz-answer-6" />
    <label for="quiz-radio-6" class="quiz-label" id="quiz-label-6">
    </label><br /><br />
    <input type="button" id="quiz-prev" class="quiz-button" value="Back" />
  </fieldset>
</div>
<div class="quiz-result">
  <form action="" method="get" class="quiz-result">
    <fieldset>
      <h3>Results</h3>
      <fieldset>
        <table>
          <tr><td><label for="quiz-arch">Archetype: </label></td>
            <td><input type="text" id="quiz-arch"
                       name="quiz-arch" readonly></td></tr>
          <tr><td><label for="quiz-brain">Brain: </label></td>
            <td><input type="text" id="quiz-brain"
                       name="quiz-brain" readonly></td></tr>
          <tr><td><label for="quiz-arch">Communication: </label></td>
            <td><input type="text" id="quiz-comm"
                       name="quiz-comm" readonly></td></tr>
          <tr><td><label for="quiz-arch">Direction: </label></td>
            <td><input type="text" id="quiz-direct"
                       name="quiz-direct" readonly></td></tr>
          <tr><td><label for="quiz-exec">Execution: </label></td>
            <td><input type="text" id="quiz-exec"
                       name="exec" readonly></td></tr>
          <tr><td><label for="arch">Focus: </label></td>
            <td><input type="text" id="focus"
                       name="focus" readonly></td></tr>
          <tr><td><label for="journey">Journey: </label></td>
            <td><input type="quiz-text" id="quiz-journey"
                       name="quiz-journey" readonly></td></tr>
        </table>
      </fieldset>
      <h3>Contact Information</h3>
      <fieldset>
        <table>
          <tr><td><label for="quiz-forename">First name: </label></td>
            <td><input type="text" id="quiz-forename"
                       name="quiz-forename" required></td></tr>
          <tr><td><label for="quiz-lastname">Last name: </label></td>
            <td><input type="text" id="quiz-lastname"
                       name="quiz-lastname" required></td></tr>
          <tr><td><label for="quiz-email">Email: </label></td>
            <td><input type="email" id="quiz-email"
                       name="quiz-email" required></td></tr>
        </table>
      </fieldset>
      <br />
      <input type="button" id="quiz-again" class="quiz-button" value="Again" />
      <input type="submit" id="quiz-submit" class="quiz-button" value="Results" />
    </fieldset>
  </form>
</div>
<?php

    // Return the output
    return ob_get_clean();
}

// Add the content if the shortcode is found.
function quiz_results_shortcode($atts) {

    // Buffer the output
    ob_start();

    // Return the output
    return ob_get_clean();
}

// Quiz footer
function quiz_footer() {

}
