<?php
/**
 * Plugin Name: Quiz
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

        wp_enqueue_script('quiz',
                          plugins_url('/js/quiz.min.js', __FILE__),
                          array('jquery-ui-core', 'jquery-ui-widget',
                                'jquery-ui-mouse', 'jquery-ui-button',
                                'jquery-ui-slider', 'jquery-effects-core',
                                'jquery'));

        // Add the shortcodes and action to insert the code into the page
        // and add the javascript data
        add_shortcode('quiz-questions', 'questions_shortcode');
        add_shortcode('quiz-results', 'results_shortcode');

        add_action('wp_footer', 'quiz_footer', 11);
    }
}

// Add the content if the shortcode is found.
function questions_shortcode($atts) {

    // Buffer the output
    ob_start();

    $custom = get_post_custom();

    // Check there's at least one question defined, no point else
    if ($custom['question']) {

        // Debug output if defined
        if ($custom['debug'])
            the_meta();
    }

    // Show this message if no questions defined
    else
        echo "<p>No quiz questions defined, you need to define some custom fields.</p>";

    // Return the output
    return ob_get_clean();
}

// Add the content if the shortcode is found.
function results_shortcode($atts) {

    // Buffer the output
    ob_start();

    $custom = get_post_custom();

    // Check there's at least one result defined, no point else
    if ($custom['result']) {

        // Debug output if defined
        if ($custom['debug'])
            the_meta();
    }

    // Show this message if no questions defined
    else
        echo "<p>No results defined, you need to define some custom fields.</p>";

    // Return the output
    return ob_get_clean();
}

// Output javascript structure defining the custom variables for the
// whatever-o-meter.js script to use
function quiz_footer() {

    $custom = get_post_custom();

    }
}
