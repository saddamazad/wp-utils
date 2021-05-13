<?php

/*
 * Template Name: City Academy
 * Version: 1.2
 * Description: A print-friendly template focusing solely on the user-submitted data. Through the Template tab you can control the PDF header and footer, change the background color or image, and show or hide the form title, page names, HTML fields and the Section Break descriptions.
 * Author: Saddam Hossain Azad
 * Author URI: https://github.com/saddamazad
 * Group: Core
 * License: GPLv2
 * Required PDF Version: 4.0-alpha
 * Tags: Header, Footer, Background, Optional HTML Fields, Optional Page Fields
 */

/* Prevent direct access to the template */
if ( ! class_exists( 'GFForms' ) ) {
    return;
}

/*
 * All Gravity PDF 4.x templates have access to the following variables:
 *
 * $form (The current Gravity Form array)
 * $entry (The raw entry data)
 * $form_data (The processed entry data stored in an array)
 * $settings (the current PDF configuration)
 * $fields (an array of Gravity Form fields which can be accessed with their ID number)
 * $config (The initialised template config class – eg. /config/blank-slate.php)
 * $gfpdf (the main Gravity PDF object containing all our helper classes)
 * $args (contains an array of all variables - the ones being described right now - passed to the template)
 */

?>

<!-- Include styles needed for the PDF -->
<style>
    table tr {
        vertical-align: top;
    }
    td {
        vertical-align: bottom;
    }
    #info-container td {
        border: 1px solid #222222;
        padding: 12px;
    }
    #info-container #faw-tbl td {
        border: none;
    }
    #info-container .nonborder-row td {
        border: none;
        padding: 0;
    }
</style>

<!-- Output our HTML markup -->
<?php

/*
 * Load our core-specific styles from our PDF settings which will be passed to the PDF template $config array
 */
$show_form_title      = ( ! empty( $settings['show_form_title'] ) && $settings['show_form_title'] == 'Yes' )            ? true : false;
$show_page_names      = ( ! empty( $settings['show_page_names'] ) && $settings['show_page_names'] == 'Yes' )            ? true : false;
$show_html            = ( ! empty( $settings['show_html'] ) && $settings['show_html'] == 'Yes' )                        ? true : false;
$show_section_content = ( ! empty( $settings['show_section_content'] ) && $settings['show_section_content'] == 'Yes' )  ? true : false;
$enable_conditional   = ( ! empty( $settings['enable_conditional'] ) && $settings['enable_conditional'] == 'Yes' )      ? true : false;
$show_empty           = ( ! empty( $settings['show_empty'] ) && $settings['show_empty'] == 'Yes' )                      ? true : false;
?>

<div style="max-width: 750px; margin: 0 auto; text-align: center;">
    <img src="/wp-content/uploads/2021/01/header.png" alt="">
    <h2 style="text-align: center;">Consent to Transfer O.S.R.</h2>
    <br><br><br>        
    To <strong>{Name of School:54}</strong> (Name of School)
    <br><br><br>
    I give my consent to transfer my Ontario Student Record to City Academy.

    <table border="0" style="width: 90%; margin: 0 auto; font-size: 20px;">
        <tr>
            <td width="50%">{Parent Name:86}<br><strong>Parent/Guardian Name</strong></td>
            <td width="50%"><img src="{Parent Signature:85}"><br><strong>Parent/Guardian Signature</strong></td>
        </tr>
        <tr>
            <td width="50%" style="padding-top: 40px;">{Student Name:73}<br><strong>Student Name</strong></td>
            <td width="50%" style="padding-top: 40px;"><img src="{Student Signature:74}"><br><strong>Student Signature</strong></td>
        </tr>
        <tr>
            <td width="50%" style="padding-top: 70px;">&nbsp;</td>
            <td width="50%" style="padding-top: 70px;">{DOB:22}<br><strong>DATE OF BIRTH</strong></td>
        </tr>
    </table>
</div>