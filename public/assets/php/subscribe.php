<?php
/* ..............................................................................
 * Author:--------------- Themearth Team
 * AuthorEmail:-----------themearth.info@gmail.com
 * Technical Support:---- http://themearth.com/
 * Websites:------------- http://themearth.com/
 * Copyright:------------ Copyright (C) 2015 logichunt.com. All Rights Reserved.
 * License:-------------- http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * ..............................................................................
 * File:- subscribe.php
 ................................................................................ */

    require 'subscribe-config.php';

	$ajax_response  = array(
		'success' => false,
		'message' => 'Email is not valid.',
	);

	function is_already_subscribe($email) {
		if (is_file('subscribe.csv')) {
			$fp = fopen('subscribe.csv', 'r');
			while (!feof($fp) ) {
			    $subscribe_list = fgetcsv($fp);
			    if ($subscribe_list[0] == $email) {
			    	fclose($fp);
			    	return true;
			    }
			}
	    	fclose($fp);
	    	return false;
		}
		return false;
	}

	if ($_POST['email']) {
		$email = strtolower($_POST['email']);
		if ($email != null && filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$ajax_response = array(
				'success' => true,
				'message' => '<i class="fa fa-check"></i> <strong>Congratulation!</strong> To complete the Subscription Process, Please Check Your Email and Follow the Instruction. ',
			);

			//saving in csv file
			if ($save_in_csv && !is_already_subscribe($email)) {
				$fp = fopen('subscribe.csv', 'a');
				$list = array ($email);
				fputcsv($fp, $list);
				fclose($fp);
			}

            if ( $send_email && $api_key != null && $list_id != null ) {

	            require_once( 'Mailchimp.php' );
                $cbx_mailchimp = new Mailchimp( $api_key );

                try {
                    $subscriber = $cbx_mailchimp->lists->subscribe( $list_id, array( 'email' => htmlentities($_POST['email'] ) ) );
                    if ( empty( $subscriber['euid'] ) || empty( $subscriber['leid'] ) ) {
                        throw new Exception( 'Oops. Something went wrong. Please try again later.' );
                    }
                } catch( Exception $Exp) {
                    $ajax_response = array (
                        'success' => false,
                        'message' => $Exp->getMessage()
                    );
                }
            }
			echo json_encode($ajax_response);
		} else {
			echo json_encode($ajax_response);
		}
	}