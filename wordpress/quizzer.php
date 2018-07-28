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
add_action('wp_enqueue_scripts', 'quizzer_enqueue_scripts', 11);

// Queue scripts and styles. Wordpress includes jquery-ui script files,
// but not all the styles
function quizzer_enqueue_scripts() {

    // Check on a page

    if (is_page()) {

        wp_enqueue_style('jquery-ui',
                         plugins_url('/css/jquery-ui.min.css', __FILE__));

        wp_enqueue_script('quiz',
                          plugins_url('/js/quiz.min.js', __FILE__),
                          array('jquery-ui-core', 'jquery-ui-widget',
                                'jquery-ui-mouse', 'jquery-ui-button',
                                'jquery-ui-progressbar', 'jquery-effects-core',
                                'jquery'));

        // Add the shortcodes and action to insert the code into the page
        // and add the javascript data
        add_shortcode('quizzer-questions', 'quizzer_questions_shortcode');
        add_shortcode('quizzer-results', 'quizzer_results_shortcode');

        add_action('wp_footer', 'quizzer_footer', 11);
    }
}

// Add the content if the shortcode is found.
function quizzer_questions_shortcode($atts, $content = null) {

    // Buffer the output
    ob_start();

    // Get the data
    $data = $atts["data"];

    // Check for data
    if (!$data)
    {
        echo "<p>No quiz data defined, you need to add some quiz data.</p>";
        return;
    }

    ?>
<div class="intro">
  <fieldset>
    <h4 id="intro"></h4>
    <input type="button" value="Begin!" class="start" id="start" />
  </fieldset>
</div>
<div class="question">
  <fieldset>
    <progress id="progress" value="6.25" max="100"></progress>
    <h4 id="question"></h4>
    <input type="radio" id="radio-1" name="answer"
           class="answer" value="answer-1" />
    <label for="radio-1" id="label-1"></label><br />
    <input type="radio" id="radio-2" name="answer"
           class="answer" value="answer-2" />
    <label for="radio-2" id="label-2"></label><br />
    <br />
    <input type="button" id="back" class="back" value="Back" />
  </fieldset>
</div>
<div class="last">
  <fieldset>
    <progress value="100" max="100"></progress>
    <h4 id="last"></h4>
    <input type="radio" id="radio-3" name="last"
           class="last" value="answer-3" />
    <label for="radio-3" id="label-3"></label><br />
    <input type="radio" id="radio-4" name="last"
           class="last" value="answer-4" />
    <label for="radio-4" id="label-4"></label><br />
    <input type="radio" id="radio-5" name="last"
           class="last" value="answer-5" />
    <label for="radio-5" id="label-5"></label><br />
    <input type="radio" id="radio-6" name="last"
           class="last" value="answer-6" />
    <label for="radio-6" id="label-6"></label><br />
    <br />
    <input type="button" id="prev" class="back" value="Back" />
  </fieldset>
</div>
<div class="result">
  <form action="" method="get" class="result">
    <fieldset>
      <h3>Results</h3>
      <fieldset>
        <table>
          <tr><td><label for="arch">Archetype: </label></td>
            <td><input type="text" id="arch"
                       name="arch" readonly></td></tr>
          <tr><td><label for="brain">Brain: </label></td>
            <td><input type="text" id="brain"
                       name="brain" readonly></td></tr>
          <tr><td><label for="arch">Communication: </label></td>
            <td><input type="text" id="comm"
                       name="comm" readonly></td></tr>
          <tr><td><label for="arch">Direction: </label></td>
            <td><input type="text" id="direct"
                       name="direct" readonly></td></tr>
          <tr><td><label for="arch">Execution: </label></td>
            <td><input type="text" id="exec"
                       name="exec" readonly></td></tr>
          <tr><td><label for="arch">Focus: </label></td>
            <td><input type="text" id="focus"
                       name="focus" readonly></td></tr>
          <tr><td><label for="journey">Journey: </label></td>
            <td><input type="text" id="journey"
                       name="journey" readonly></td></tr>
        </table>
      </fieldset>
      <h3>Contact Information</h3>
      <fieldset>
        <table>
          <tr><td><label for="forename">First name: </label></td>
            <td><input type="text" id="forename"
                       name="forename" required></td></tr>
          <tr><td><label for="lastname">Last name: </label></td>
            <td><input type="text" id="lastname"
                       name="lastname" required></td></tr>
          <tr><td><label for="email">Email: </label></td>
            <td><input type="email" id="email"
                       name="email" required></td></tr>
        </table>
      </fieldset>
      <br />
      <input type="button" id="again" value="Again" />
      <input type="submit" id="submit" value="Results" />
    </fieldset>
  </form>
</div>
<?php

    // Return the output
    return ob_get_clean();
}

// Add the content if the shortcode is found.
function quizzer_results_shortcode($atts, $content = null) {

    // Buffer the output
    ob_start();

    // Return the output
    return ob_get_clean();
}

// Quiz footer
function quizzer_footer() {

}
