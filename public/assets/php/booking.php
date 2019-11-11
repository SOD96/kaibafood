<?php
/**********************************************************************
 * Theme Name: FoodKing - Restaurant, Food & Cafe HTML Template
 * Version: 1.0.0
 * Author: Httpcoder Team
 * Email: httpcoder.info@gmail.com
 * Website: https://www.httpcoder.com/, http://csinfotechbd.com/
 * Copyright: All rights reserved by httpcoder.com
 **********************************************************************/


define( 'DS', DIRECTORY_SEPARATOR );

//include mailchimp
require_once( 'mailchimp/vendor/autoload.php' );
use \DrewM\MailChimp\MailChimp;

//include the php emailer library
require_once 'phpmailer/PHPMailerAutoload.php';


//email template
require_once 'emogrifier.php';
require_once 'class.emailtemplate.php';
//html2text
require_once 'mailchimp/vendor/autoload.php';


//csv
$save_in_csv = 1; // make it true if you want to save in a csv file, the csv file name should be bookings.csv and must be located in same dir of this file


//mailchimp
$save_in_mailchimp = false; //true = save in mailchimp, false =  ignore //if true, then list id and key needs to be set
$api_key           = null; //mailchimp api key
$list_id           = null; //set mailchimp list id api
$status            = 'pending'; //pending = user will get email to confirm his subscription, subscribed = force subscribe the email


$admin_email_to        = 'admin@yourdomain.com'; // admin email who will get the contact email alert
$admin_email_to_name   = "Company Name"; // Admin Name/Company name who will get the email alert
$admin_email_from      = 'noreply@yourdomain.com';  // admin email from which email address email will be sent
$admin_email_from_name = 'System'; //admin name from which email will be sent
$admin_send_subject    = 'Booking form alert'; //email subject what the admin will get as contact email alert
$user_send_subject     = 'Thanks for booking request, your copy'; //email subject what the user will get if the user agreed or select "copy me"

$site_name			   = 'Example Site Name'; //change this as need
$header_image		   = ''; //header image
$footer_text		   = 'Copyright @example.com'; //change this as need
$email_heading	   	   = 'New Booking Notifiction';
//end options parameter for user

$send_copy  = true; // true = sends an email to visitor who submits the contact form
$html_email = true; //true, sends html email, false = send plain text email

//end options parameter for user


$list               = array();
$validation_message = array(
	'error'       => false,
	'error_field' => array(),
	'message'     => array()
);

$rules = array(
	'fb_name'   => 'trim|required',
	'fb_email'  => 'trim|required|email',
	'fb_time'   => 'required',
	'fb_date'   => 'required',
	'fb_person' => 'required',
	'fb_msg'    => 'trim',
);

if ( $_POST ) {
	require_once( __DIR__ . DS . 'class.validationbook.php' );
	$frm_val = new validation;

	foreach ( $rules as $post_key => $rule ) {
		$frm_val->validate( $post_key, $rule );
	}

	$validation_info             = $frm_val->validation_info();
	$validation_message['error'] = ! $validation_info['validation'];

	foreach ( $validation_info['error_list'] as $error_field => $message ) {
		$validation_message['error_field'][]         = $error_field;
		$validation_message['message'][$error_field] = $message;
	}

	$name    = $frm_val->get_value( 'fb_name' );
	$email   = $frm_val->get_value( 'fb_email' );
	$phone   = $frm_val->get_value( 'fb_phone' );
	$time    = $frm_val->get_value( 'fb_time' );
	$date    = $frm_val->get_value( 'fb_date' );
	$person  = $frm_val->get_value( 'fb_person' );
	$message = $frm_val->get_value( 'fb_msg' );


	//if save in csv true
	if ( $save_in_csv && $validation_info['validation'] ) {
		$list[] = $name;
		$list[] = $email;
		$list[] = $phone;
		$list[] = $time;
		$list[] = $date;
		$list[] = $person;
		$list[] = $message;

		$fp = fopen( 'bookings.csv', 'a' );
		fputcsv( $fp, $list );
		fclose( $fp );
	}
	//end if save is csv true

	//maillchimp
	if ($save_in_mailchimp && $api_key != null && $list_id != null) {

		$MailChimp = new MailChimp($api_key);


		$mailchimp_responsse = $MailChimp->post("lists/$list_id/members", [
			'email_address' => $email,
			'status'        => $status,
		]);

		if ($MailChimp->success()) {
			//print_r($mailchimp_responsse);

		} else {
			//echo $MailChimp->getLastError();

			$error = $MailChimp->getLastError();

		}
	}
	//end mailchimp


	//send email

	if ( $validation_info['validation'] ) {
		//now prepare for sending email



		//create an instance of phpmailer class
		$mail = new PHPMailer;


		//some config if you need help based on your server configuration
		/*
		$mail->isSMTP();
		$mail->Host = 'mailtrap.io';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 2525;                                    // TCP port to connect to
		$mail->Username = '';                 // SMTP username
		$mail->Password = '';                    // SMTP password
		*/

		//add admin from email
		$mail->From = $admin_email_from;
		//add admin from name
		$mail->FromName = $admin_email_from_name;
		//add admin to email and name
		$mail->addAddress( $admin_email_to, $admin_email_to_name );

		//add more if you need more to recipient
		//$mail->addAddress('ellen@example.com');               // Name is optional

		//add if you need reply to
		//$mail->addReplyTo('info@example.com', 'Information');
		//add if you need cc
		//$mail->addCC('cc@example.com');

		//add if you need bcc
		// $mail->addBCC('bcc@example.com');

		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML( $html_email );


		$template_params['site_name'] 		= $site_name;
		$template_params['header_image'] 	= $header_image;
		$template_params['footer_text'] 	= $footer_text;
		$template_params['email_heading'] 	= $email_heading;


		$html_message = '';
		$html_message .= '<p>Name: ' . $name . '</p>';
		$html_message .= '<p>Email: ' . $name . '</p>';
		$html_message .= '<p>Telephone: ' . $phone . '</p>';
		$html_message .= '<p>Time: ' . $time . '</p>';
		$html_message .= '<p>Date: ' . $date . '</p>';
		$html_message .= '<p>Person: ' . $person . '</p>';
		$html_message .= '<p>Message:</p>';
		$html_message .= '<p>' . $message . '</p>';


		$emailTemplate 	= new EmailTemplate($template_params);
		$emailBody     	= $emailTemplate->getHtmlTemplate();
		$emailBody     	= str_replace('{mainbody}', $html_message, $emailBody); //replace mainbody
		$emailBody 	   	= str_replace('{emailheading}', $email_heading, $emailBody); //replace email heading
		$emailBody 		= $emailTemplate->htmlEmeilify($emailBody);// Set email format to HTML

		$mail->Subject = $admin_send_subject;


		if($html_email == false){
			$emailBody_plain = new \Html2Text\Html2Text($emailBody);
			$emailBody = $emailBody_plain->getText();
		}


		$mail->Body    = $emailBody;
		//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


		if ( $mail->send() === true ) {

			$validation_message['successmessage'] = 'Message has been sent successfully !';
		} else {
			$validation_message['successmessage'] = 'Sorry, Mail could not be sent. Please contact server admin.';
		}

		//send email to user if user agreed or selected "copy me"

		if($send_copy){
			$mail2 = new PHPMailer;

			/*
			//some config if you need help based on your server configuration
			$mail2->isSMTP();
			$mail2->Host = 'mailtrap.io';  // Specify main and backup SMTP servers
			$mail2->SMTPAuth = true;
			$mail2->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail2->Port = 2525;                                    // TCP port to connect to
			$mail2->Username = '';                 // SMTP username
			$mail2->Password = '';                    // SMTP password
			*/


			//some config if you need help based on your server configuration
			//$mail2->Host = 'localhost';  // Specify main and backup SMTP servers

			// $mail->Username = 'user@example.com';                 // SMTP username
			// $mail->Password = 'secret';                           // SMTP password
			// $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			// $mail2->Port = 25;                                    // TCP port to connect to

			//add admin from email
			$mail2->From = $admin_email_from;
			//add admin from name
			$mail2->FromName = $admin_email_from_name;
			//now send to user
			//$mail->From = $admin_email_from;
			// $mail->FromName = $admin_email_from_name;
			//$mail->all_recipients = array();
			$mail2->addAddress( $email, $name );     // Add a recipient, user who fillted the contact form
			//$mail->addAddress('ellen@example.com');               // Name is optional
			//$mail->addReplyTo('info@example.com', 'Information');
			//$mail->addCC('cc@example.com');
			// $mail->addBCC('bcc@example.com');

			//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			$mail2->isHTML( $html_email );                                  // Set email format to HTML

			$mail2->Subject = 'Copy Mail:' . $admin_send_subject;
			$mail2->Body    = $emailBody;


			//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			if ( $mail2->send() === true ) {

				$validation_message['successmessage'] = 'Message has been sent successfully !';

			} else {
				$validation_message['successmessage'] = 'Sorry, Mail could not be sent. Please contact server admin.';

			}
		}


	} else {

	}

	//end send email

	echo json_encode( $validation_message );
	die( 1 );
}