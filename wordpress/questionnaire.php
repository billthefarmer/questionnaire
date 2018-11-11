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

// Show errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include TCPDF, if present
// $tcpdf_present = include_once 'tcpdf/tcpdf.php';

// Include libraries
$vendor_present = include_once 'vendor/autoload.php';

// Start session
if (empty(session_id()))
    session_start();

// Add scripts hook, also adds shortcodes and further action
add_action('wp_enqueue_scripts', 'questionnaire_enqueue_scripts', 11);

// Queue scripts and styles. Wordpress includes jquery-ui script files,
// but not all the styles
function questionnaire_enqueue_scripts()
{
    // Check on a page
    if (is_page()) {

        // Styles
        wp_enqueue_style('jquery-ui',
                         plugins_url('/css/jquery-ui.min.css', __FILE__));
        wp_enqueue_style('questionnaire',
                         plugins_url('/css/questionnaire.css', __FILE__));

        // Javascript
        wp_enqueue_script('empty', 
                          plugins_url('/js/empty.js', __FILE__),
                          array('jquery-ui-button', 'jquery-ui-progressbar',
                                'jquery'));

        // Add the shortcodes
        add_shortcode('questionnaire-questions',
                      'questionnaire_questions_shortcode');
        add_shortcode('questionnaire-report',
                      'questionnaire_report_shortcode');
    }
}

// Add the content if the shortcode is found.
function questionnaire_questions_shortcode($atts)
{
    // Buffer the output
    ob_start();

    ?>
<div class="questionnaire">
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
        </label>
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
        </label>
      </div>
      <input type="button" id="questionnaire-prev"
             class="questionnaire-button" value="Back" />
    </fieldset>
  </div>
  <div class="questionnaire-contact">
    <fieldset>
      <h3>Results</h3>
      <fieldset>
        <table>
          <tr><td><label for="arch">Archetype: </label></td>
            <td><input type="text" id="arch"
                       name="arch" readonly /></td></tr>
          <tr><td><label for="brain">Brain: </label></td>
            <td><input type="text" id="brain"
                       name="brain" readonly /></td></tr>
          <tr><td><label for="arch">Communication: </label></td>
            <td><input type="text" id="comm"
                       name="comm" readonly /></td></tr>
          <tr><td><label for="arch">Direction: </label></td>
            <td><input type="text" id="direct"
                       name="direct" readonly /></td></tr>
          <tr><td><label for="arch">Execution: </label></td>
            <td><input type="text" id="exec"
                       name="exec" readonly /></td></tr>
          <tr><td><label for="arch">Focus: </label></td>
            <td><input type="text" id="focus"
                       name="focus" readonly /></td></tr>
          <tr><td><label for="stage">Stage: </label></td>
            <td><input type="text" id="stage"
                       name="stage" readonly /></td></tr>
        </table>
      </fieldset>
      <form action="report" method="get" class="questionnaire-result">
        <input type="hidden" id="A" name="A" />
        <input type="hidden" id="B" name="B" />
        <input type="hidden" id="C" name="C" />
        <input type="hidden" id="D" name="D" />
        <input type="hidden" id="E" name="E" />
        <input type="hidden" id="F" name="F" />
        <input type="hidden" id="S" name="S" />
        <fieldset>
          <h3>Contact Information</h3>
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
          <br />
          <input type="button" id="questionnaire-again"
                 class="questionnaire-button" value="Again" />
          <input type="submit" id="questionnaire-submit"
                 class="questionnaire-button" value="Report" />
        </fieldset>
      </form>
    </fieldset>
  </div>
</div>
<?php

    $questions = plugins_url('/js/questions.min.js', __FILE__);
    $questionnaire = plugins_url('/js/questionnaire.min.js', __FILE__);

    ?>
<script type="text/javascript" src="<?php echo $questions ?>"></script>
<script type="text/javascript" src="<?php echo $questionnaire ?>"></script>
<?php

    // Return the output
    return ob_get_clean();
}

// Add the content if the shortcode is found.
function questionnaire_report_shortcode($atts)
{
    global $vendor_present;
    global $report_instance;

    // Create report
    function create_report($filename, $forename, $lastname)
    {
        global $report_instance;

        // ?A=10%2C12&B=10&C=8&D=6&E=12&F=8&S=10
        // &forename=Jeremiah&lastname=Fundament
        // &email=jerry%40fundament.com

        // Get parameters
        $A = filter_input(INPUT_GET, 'A', FILTER_SANITIZE_STRING);
        $B = filter_input(INPUT_GET, 'B', FILTER_SANITIZE_NUMBER_INT);
        $C = filter_input(INPUT_GET, 'C', FILTER_SANITIZE_NUMBER_INT);
        $D = filter_input(INPUT_GET, 'D', FILTER_SANITIZE_NUMBER_INT);
        $E = filter_input(INPUT_GET, 'E', FILTER_SANITIZE_NUMBER_INT);
        $F = filter_input(INPUT_GET, 'F', FILTER_SANITIZE_NUMBER_INT);
        $S = filter_input(INPUT_GET, 'S', FILTER_SANITIZE_NUMBER_INT);

        // Check parameters present
        if (empty($B) or empty($C) or empty($D) or empty($E) or empty($F))
            return;

        // Generate code
        $report_instance = md5($B . $C . $D . $E . $F);

        // Get data
        $path = plugin_dir_path(__FILE__);
        $json = file_get_contents($path . 'js/answers.min.json');
        $data = json_decode($json);

        $pages = $data->pages;
        $answers = $data->answers;
        $last = $data->last;

        // Create document
        $pdf = new TCPDF();
        $pdf->setPageUnit('pt');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $margin = 72; // 1"
        $pageWidth = $pdf->getPageWidth();
        $pageHeight = $pdf->getPageHeight();
        $textWidth = $pageWidth - ($margin * 2);

        // Set margins
        $pdf->SetMargins($margin, $margin, $margin);
        $pdf->SetAutoPageBreak(true, $margin);

        // New line
        function new_text_line($pdf)
        {
            $pdf->Ln($pdf->getCellHeight($pdf->getFontSize()));
        };

        // Add text
        function add_text_object($pdf, $text, $forename = '', $lastname = '')
        {
            if (!empty($text->type))
                $pdf->SetFont('', $text->type);

            else
                $pdf->SetFont('', '');

            if (!empty($text->size))
                $pdf->SetFontSize($text->size);

            if (!empty($text->color))
                $pdf->SetTextColor($text->color);

            if (!empty($text->y))
                $pdf->SetY($text->y);

            $subst = str_replace(['~forename~', '~lastname~'],
                                      [$forename, $lastname], $text->text);

            $pdf->MultiCell(0, 0, $subst, 0, 'L');
        };

        // Add report entry
        function add_entry($pdf, $entry, $value, $margin, $textWidth, $path)
        {
            $desc = $entry->desc;
            $type = $entry->$value->type;
            $text = $entry->$value->text;
            $image = $entry->$value->image;

            $y = $pdf->GetY();
            $pdf->Image($path . $image, $margin, $y, $textWidth, 0,
                        'png', '', 'N');

            $pdf->MultiCell(0, 0, $desc, 0, 'L');
            new_text_line($pdf);
            $pdf->SetFont('', 'B');
            $pdf->MultiCell(0, 0, $type, 0, 'L');
            new_text_line($pdf);
            $pdf->SetFont('', '');
            $pdf->MultiCell(0, 0, $text, 0, 'L');
            // new_text_line($pdf);
        };

        // Add report stage
        function add_stage($pdf, $entry, $value, $margin, $textWidth, $path)
        {
            $stage = $entry->$value->stage;
            $steps = $entry->$value->steps;
            $class = $entry->$value->class;
            $link = $entry->$value->link;
            $image = $entry->$value->image;
            $text = $entry->$value->text;

            new_text_line($pdf);
            $pdf->SetFont('', 'B');
            $pdf->MultiCell(0, 0, "NEXT STEPS", 0, 'L');
            $pdf->SetFont('', '');
            new_text_line($pdf);
            $pdf->MultiCell(0, 0, $stage, 0, 'L');
            new_text_line($pdf);
            $pdf->MultiCell(0, 0, $steps, 0, 'L');
            new_text_line($pdf);
            $pdf->SetFont('', 'B');
            $pdf->SetTextColor(175, 189, 53);
            $pdf->Cell(0, 0, $class, 0, 0, 'L', false, $link);
            $pdf->SetFont('', '');
            $pdf->SetTextColor(0);
            new_text_line($pdf);
            new_text_line($pdf);
            $pdf->MultiCell(0, 0, $text, 0, 'L');
            new_text_line($pdf);

            $y = $pdf->GetY();
            $pdf->Image($path . $image, $margin, $y, $textWidth, 0,
                        'jpg', $link, 'N');
            new_text_line($pdf);
        };

        // Add image
        function add_image_object($pdf, $image, $margin, $textWidth,
                                  $pageHeight, $pageWidth, $path)
        {
            $width = (!empty($image->width))? $image->width: $textWidth;
            $height = (!empty($image->height))? $image->height: 0;
            $x = (!empty($image->x))? ($image->x < 0)?
               $pageWidth - $margin - $width: $image->x: $margin;
            $y = (!empty($image->y))? ($image->y < 0)?
               $pageHeight - $margin - $height: $image->y: $margin;
            $type = (!empty($image->type))? $image->type: '';
            $link = (!empty($image->link))? $image->link: '';
            $pdf->Image($path . $image->src, $x, $y, $width, $height,
                        $type, $link);
        };

        // Preamble
        foreach ($pages as $page)
        {            
            $pdf->AddPage();

            // Text
            foreach ($page->text as $text)
                add_text_object($pdf, $text, $forename, $lastname);

            // Images
            foreach ($page->images as $image)
                add_image_object($pdf, $image, $margin, $textWidth,
                                 $pageHeight, $pageWidth, $path);
        }

        // Report
        $pdf->AddPage();

        if ($B)
            add_entry($pdf, $answers->B, $B, $margin, $textWidth, $path);

        if ($C)
            add_entry($pdf, $answers->C, $C, $margin, $textWidth, $path);

        if ($pdf->GetY() >= $pageHeight - ($margin * 3))
            $pdf->AddPage();

        if ($D)
            add_entry($pdf, $answers->D, $D, $margin, $textWidth, $path);

        if ($pdf->GetY() >= $pageHeight - ($margin * 3))
            $pdf->AddPage();

        if ($E)
            add_entry($pdf, $answers->E, $E, $margin, $textWidth, $path);

        if ($pdf->GetY() >= $pageHeight - ($margin * 3))
            $pdf->AddPage();

        if ($F)
            add_entry($pdf, $answers->F, $F, $margin, $textWidth, $path);

        if ($S)
            add_stage($pdf, $answers->S, $S, $margin, $textWidth, $path);

        // $pdf->AddPage();

        // Last page text
        foreach ($last->text as $text)
            add_text_object($pdf, $text);

        // Last page images
        // foreach ($last->images as $image)
        //     add_image_object($pdf, $image, $margin, $textWidth,
        //                      $pageHeight, $pageWidth, $path);

        // Output document
        $pdf->Output($path . 'report/' . $filename, 'F');

        // Return file uri
        return plugins_url('report/' . $filename, __FILE__);
    };

    function send_email($usermail, $forename, $lastname, $username, $filename)
    {
        // Get data
        $path = plugin_dir_path(__FILE__);
        $json = file_get_contents($path . 'js/email.min.json');
        $data = json_decode($json);

        // Set fields
        $to = "$username <$usermail>";
        $from_email = $data->from_email;
        $from_name = $data->from_name;
        $from = "$from_name <$from_email>";
        $subject = str_replace('~forename~', $forename, $data->subject);
        $message = str_replace('~forename~', $forename, $data->message);
        $content = "Content-Type: text/html";
        $headers = ["From: $from",
                    $content];

        // Get attachment path
        $path = plugin_dir_path(__FILE__);
        $attachments = $path . "report/" . $filename;

        // Send mail
        $to = "Me <williamjfarmer@yahoo.co.uk>";
        // $to = "Herself <catherinejleblanc@yahoo.co.uk>";
        wp_mail($to, $subject, $message, $headers,
                $attachments);
    };

    $forename = filter_input(INPUT_GET, 'forename', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_GET, 'lastname', FILTER_SANITIZE_STRING);
    $usermail = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);

    if (!$forename)
        $forename = "Cat";
    if (!$lastname)
        $lastname = "LeBlanc";

    $forename = str_replace("+", " ", $forename);
    $lastname = str_replace("+", " ", $lastname);
    $username = $forename . " " . $lastname;
    $filename = str_replace(" ", "_", $username .
                            "_Entrepreneurial_Design_Profile.pdf");

    // Buffer the output
    ob_start();

    // Check TCPDF
    if ($vendor_present)
        $fileuri = create_report($filename, $forename, $lastname);

    else
        echo "<p>TCPDF not found - please install php-tcpdf: <code>'sudo apt install php-tcpdf', or <code>'php compose.phar require tecnickcom/tcpdf'</code></p>";

    // Send email, if not already sent
    if (empty($_SESSION[$report_instance]))
    {
        send_email($usermail, $forename, $lastname, $username, $filename);
        $_SESSION[$report_instance] = 1;
    }

    ?>
<div class="report-content">
  <fieldset>
    <h2 id="user-name" class="user-name"><?php echo $username ?></h2>
    <a href="<?php echo $fileuri ?>" download>
      <input type="button" id="download-report" name="download-report"
             class="questionnaire-button" value="Download Report" />
    </a>
    <br /><br />
  </fieldset>
</div>
<?php

    if (!wp_is_mobile())
    {
        ?>
<div class="report-preview">
  <object id="report-preview" class="report-preview" type="application/pdf"
          data="<?php echo $fileuri ?>" width="640" height="878">
  </object>
</div>
<?php

    }

    // Add javascript
    $report = plugins_url('/js/report.min.js', __FILE__);

    ?>
<script type="text/javascript" src="<?php echo $report ?>"></script>
<?php

    // Return the output
    return ob_get_clean();
}

?>
