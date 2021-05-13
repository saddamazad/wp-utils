<?php

/*
 * Template Name: Lorislending
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
        vertical-align: top;
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
    #checkmark {
        vertical-align: top;
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

<table autosize="1">
	<tr>
		<td align="left" width="50%">
			<img src="https://lorislending.com/wp-content/uploads/2018/11/logo.png">
		</td>		
		<td align="right" width="50%">
            info@lorislending.com<br>
            Tel: 416-629-2157 / 1-877-633-8005<br>
            Fax: 416-352-568
		</td>
	</tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
<table autosize="1" id="info-container">
    <tr>
        <td valign="top" width="33%"><strong>First Name</strong><br>{First Name:1}</td>
        <td valign="top" width="33%"><strong>Last Name</strong><br>{Last Name:2}</td>
        <td valign="top"><strong>Business Name</strong><br>{Business Name:3}</td>
    </tr>
    <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr> -->
    <tr>
        <td valign="top" width="33%"><strong>Date of Birth</strong><br>{Date of Birth:20}</td>
        <td valign="top" width="33%"><strong>Email Address</strong><br>{Email Address:5}</td>
        <td valign="top"><strong>Business Phone Number</strong><br>{Business Phone Number:61}</td>
    </tr>
    <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr> -->
    <tr>
        <td valign="top"><strong>Mobile Phone Number</strong><br>{Mobile Phone Number:6}</td>
        <td valign="top" width="33%">
            <strong>Industry</strong><br>{Industry:8}
            [gravityforms action="conditional" merge_tag="{Industry:8}" condition="is" value="Other"]
                <br>- {Industry Other:29}
            [/gravityforms]
        </td>
        <td valign="top" width="33%"><strong>Business Address</strong><br>{Business Address:12} {Suite:13}, {City:15}, <br>{Province:16} {Postal Code:17}</td>
    </tr>
    <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr> -->
    <tr>
        <td valign="top"><strong>Home Address</strong><br>{Home Address:43} {Suite:44}, {City:45}, <br>{Province:46} {Postal Code:47}</td>
        <td valign="top" width="33%"><strong>Business Legal Name</strong><br>{Business Legal Name:4}</td>
        <td valign="top"><strong>Date Business Started</strong><br>{Date Business Started:38}</td>
    </tr>
    <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr> -->
    <tr>
        <td valign="top" width="33%"><strong>Monthly Sales</strong><br>${Monthly Sales:54}</td>
        <td valign="top" width="33%"><strong>Business Landlord or Business Mortgage Bank</strong><br>{Business Landlord or Business Mortgage Bank:49}</td>
        <td valign="top" width="33%"><strong>Monthly Rent or Mortgage Payment</strong><br>${Monthly Rent or Mortgage Payment:55}</td>
    </tr>
    <tr>
        <td valign="top" width="33%"><strong>Contact Name and/or Account #</strong><br>{Contact Name and/or Account #:51}</td>
        <td valign="top" width="33%">
            <strong>Have you ever had a cash <br>advance?</strong><br>{Have you ever had a cash advance?:42}
            [gravityforms action="conditional" merge_tag="{Have you ever had a cash advance?:42}" condition="is" value="Yes"]
                <br>Current Balance: ${Current Balance:56}
            [/gravityforms]
        </td>
        <td valign="top" width="33%">&nbsp;</td>
        <!--<strong>Phone Number</strong><br>{Phone Number:53}-->
    </tr>
    <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr> -->
    <tr>
        <td valign="top" width="33%"><strong>Tax Number (GST/HST Number)</strong><br>{Tax Number (GST/HST Number):34}</td>
        <td valign="top" width="33%"><strong>Social Insurance Number</strong><br>{Social Insurance Number:31}</td>
        <td valign="top" width="33%">
            <strong>% Ownership</strong><br>{% Ownership:32}
        </td>
    </tr>
    [gravityforms action="conditional" merge_tag="{% Ownership:32}" condition="greater_than" value="49"]
    [gravityforms action="conditional" merge_tag="{% Ownership:32}" condition="less_than" value="100"]
    <tr class="nonborder-row">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="nonborder-row">
        <td colspan="3" valign="top">
                {Since it\'s less than 50%, please tell us the name and percentage of the additional owners:26:}
        </td>
    </tr>
    [/gravityforms]
    [/gravityforms]
    <tr class="nonborder-row">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="nonborder-row">
        <td valign="top" colspan="3"><strong>Disclaimer</strong><br><img src="https://lorislending.com/wp-content/uploads/2019/02/checkbox.png" id="checkmark" /> <small>By Signing below you are Agreeing to the following Terms & Conditions.  You certify that all information and documents submitted in connection with this application are true, correct and complete. Additionally the owner(s) authorize 2516208 Ontario Corporation or any of its agents, partners and affiliates to contact the landlord, suppliers and contacts as well as obtain and use business consumer credit reports from credit reporting agencies and any other information regarding the merchant and its owner(s) from third parties at the time of the initial funding application and at any time after the merchant has received funding.</small></td>
    </tr>
    <!-- <tr class="nonborder-row">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr> -->
    <tr class="nonborder-row">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="nonborder-row">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="nonborder-row">
        <td valign="top" width="37%">&nbsp;</td>
        <td valign="top" width="30%"><strong>Name</strong><br>{Name (First):28.3}</td>
        <td valign="top">
            <!-- <strong>Signature</strong><br><img src="{Signature (use mouse to sign):35}"> -->
            <table autosize="1" id="faw-tbl">
                <tr>
                    <td align="center"><strong>Signature</strong><br><img src="{Signature (use mouse to sign):35}"><br><?php echo date("F j, Y"); ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>