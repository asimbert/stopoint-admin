<?php

/*
  BEANSTREAM REQUEST/RESPONSE PROCESSING CLASS
  (c) copyright 2007 - All Rights Reserved.
  WebSite: http://www.wildwestcode.com/
  Email: contact@wildwestcode.com
 */

class beanstream {

    var $cad_merchant_id = '159220001';   // DEBUG ID: '175950000'; //
    var $usd_merchant_id = '159230001';   // DEBUG ID:'175960000'; //
    var $merchant_id = '';  //populated with value based on currency type
    var $curType; //currency type - used to switch between merchant accounts
    /* required fields and error messages */
    var $response_message = '';
    var $error_messages = array();
    var $error_fields_messages = array(
        'trnCardOwner' => 'You have not entered a valid Card Holder Name.',
        'trnCardNumber' => 'The Credit Card Number is invalid.',
        'trnExpMonth' => 'The Credit Card Expire Month is invalid.',
        'trnExpYear' => 'The Credit Card Expire Year is invalid.',
        'trnAmount' => 'The Transaction Amount is invalid.',
        'ordEmailAddress' => 'The Email Address entered is invalid',
        'ordName' => 'The Name provided is invalid.',
        'ordPhoneNumber' => 'The Phone Number provided is invalid.',
        'ordAddress1' => 'The Street Address provided is invalid.',
        'ordAddress2' => 'The Second Line of the Street Address provided is invalid.',
        'ordCity' => 'The City provided is invalid.',
        'ordProvince' => 'The State/Province provided is invalid.',
        'ordPostalCode' => 'The Postal Code provided is invalid.',
        'ordCountry' => 'The Country provided is invalid.'
    );
    var $avs_code_messages = array(
        '0' => 'Address Verification not performed for this transaction.',
        '5' => 'Invalid AVS Repsonse.',
        '9' => 'Address Verification Data contains edit error.',
        'A' => 'Street address matches, Postal/ZIP does not match.',
        'B' => 'Street address matches, Postal/ZIP not verified.',
        'C' => 'Street address matches and Postal/Zip match.',
        'D' => 'Transaction ineligible.',
        'E' => 'Non AVS participant. Information not verified.',
        'G' => 'Address information not verified for international transaction.',
        'I' => 'Street address and Postal/ZIP match.',
        'M' => 'Street address and Postal/ZIP do not match.',
        'N' => 'Postal/ZIP matches. Street address not verified.',
        'P' => 'System unavailable or timeout.',
        'S' => 'AVS not supported at this time.',
        'U' => 'Address information is unavailable.',
        'W' => 'Postal/ZIP matches, Street address does not match.',
        'X' => 'Street address and Postal/ZIP match.',
        'Y' => 'Street address and Postal/ZIP match.',
        'Z' => 'Postal/ZIP matches, Street address does not match.'
    );
    var $response_fields_list = array(
        'trnId',
        'messageId',
        'messageText',
        'trnApproved',
        'authCode',
        'responseType',
        'trnAmount',
        'trnDate',
        'trnOrderNumber',
        'trnCustomerName',
        'trnEmailAddress',
        'trnPhoneNumber',
        'avsProcessed',
        'avsId',
        'avsResult',
        'avsAddrMatch',
        'avsPostalMatch',
        'avsMessage',
        'paymentMethod',
        'ref1',
        'ref2',
        'ref3',
        'ref4',
        'ref5',
        'errors',
        'errorType',
        'errorFields'
    );
    /* error response format */
    var $error_fields_list = array('errorType',
        'errorMessage',
        'errorFields'
    );

    function clean_values($value) {
        $value = stripslashes(strip_tags($value));
        return $value;
    }

    function process_transaction($order_values) {
        $order_values = array_map(array('beanstream', 'clean_values'), $order_values);
        /* get currency type and change to correct merchant id */
        $this->curType = $order_values['currencyType'];
        $this->merchant_id = ($this->curType == 'US') ? $this->usd_merchant_id : $this->cad_merchant_id;
        $transaction_values = array(
            'requestType' => 'BACKEND',
            'merchant_id' => $this->merchant_id,
            'trnOrderNumber' => $order_values['trnOrderNumber'],
            'trnCardOwner' => $order_values['trnCardOwner'],
            'trnCardNumber' => $order_values['trnCardNumber'],
            'trnExpMonth' => $order_values['trnExpMonth'],
            'trnExpYear' => $order_values['trnExpYear'],
            'trnAmount' => $order_values['trnAmount'],
            'ordEmailAddress' => $order_values['ordEmailAddress'],
            'ordName' => $order_values['ordName'],
            'ordPhoneNumber' => $order_values['ordPhoneNumber'],
            'ordAddress1' => $order_values['ordAddress1'],
            'ordAddress2' => $order_values['ordAddress2'],
            'ordCity' => $order_values['ordCity'],
            'ordProvince' => $order_values['ordProvince'],
            'ordPostalCode' => $order_values['ordPostalCode'],
            'ordCountry' => $order_values['ordCountry'],
                //'errorPage'      => 'http://www.chrisleader.com/bean/bean-error.php', //not used - error processing transaction page
                //'approvedPage'   => '', //not used - successful transaction page
                //'declinedPage'   => ''  //not used - declined transaction page
        );
        $transaction_array = array();
        foreach ($transaction_values as $key => $value) {
            $transaction_array[] = "{$key}={$value}";
        }
        $transaction_string = implode('&', $transaction_array);
        //print('<h2>Transaction String:</h2>'.$transaction_string);
        return $this->send_transaction($transaction_string);
    }

    function send_transaction($transaction_string) {
        $ch = curl_init();
        /* disable CertAuth verification */
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, 'https://www.beanstream.com/scripts/process_transaction.asp');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $transaction_string);
        $result = curl_exec($ch);
        /* cleanup */
        curl_close($ch);
        unset($ch);
        return $result;
    }

    function process_response($response_text) {
        $response_array = array();
        $tmp_response_array = explode('&', urldecode($response_text));
        foreach ($tmp_response_array as $item) {
            list($key, $value) = explode('=', $item);
            $response_array[$key] = $value;
        }
        if ($response_array['errorType'] != 'N') {
            $this->response_message = $response_array['messageText'];
            $error_list = $response_array['errorFields'];
            $this->process_error($error_list);
            $response_array['errors'] = true;
            return $response_array;
        } else {
            /*
              no system/user errors
              process transaction response
             */
			 /*
            if (intval($response_array['avsProcessed']) != 0 && (intval($response_array['avsResult']) != 1 || intval($response_array['trnApproved']) != 1)) {
                // found AVS Error
                $response_array['errors'] = true;
                $this->response_message = 'AVS Verification Error: ' . $response_array['avsMessage'];
                $this->response_message .= "<br>\r\n" . $response_array['messageText'];
                // process error
                $error_list = $response_array['errorFields'];
                $this->process_error($error_list);
                return $response_array;
            } else
			*/
			if (intval($response_array['trnApproved']) == 1 &&
                    $response_array['responseType'] == 'T') {
                /* transaction successful */
                $response_array['errors'] = false;
                return $this->process_success($response_array);
            } elseif (trim($response_array['messageText']) == 'DECLINE') {
                /* transaction was declined */
                //print('<h3>transaction declined</h3>');
                $response_array['messageText'] = 'The transaction was declined.';
                $response_array['errors'] = true;
                $this->response_message = $response_array['messageText'];
                /* process error */
                $error_list = $response_array['errorFields'];
                $this->process_error($error_list);
                return $response_array;
            } else {
                /* unknown error in transaction, message displayed to user */
                // START DEBUG OUTPUT
                /*
                  print('The following message was received while processing this transaction:<br>');
                  print($response_array['messageText']);
                  print('<hr><pre>');
                  print_r($response_array);
                  print('</pre>');
                 */
                // END DEBUG
                $response_array['errors'] = true;
                $this->response_message = $response_array['messageText'];
                /* process error */
                $error_list = $response_array['errorFields'];
                $this->process_error($error_list);
                return $response_array;
            }
        }
    }

    function process_success($response_array) {
        $response_results = array();
        foreach ($this->response_fields_list as $fieldName) {
            if (isset($response_array[$fieldName])) {
                $response_results[$fieldName] = $response_array[$fieldName];
            }
        }
        return $response_results;
    }

    function add_failed_transaction($order_number, $transaction_array) {
        return $this->update_db_with_transaction($order_number, $transaction_array);
    }

    function complete_transaction($order_number, $transaction_array) {
        return $this->update_db_with_transaction($order_number, $transaction_array);
    }

    function update_db_with_transaction($order_number, $transaction_array) {
        $db = new Database();
        /* build db array */
        $db_array = array('order_number' => $order_number,
            'remote_addr' => $_SERVER['REMOTE_ADDR']
        );
        $db_array = array_merge($db_array, $transaction_array);
        $db_array = array_merge(array_slice($db_array, 0, 2), array_slice($db_array, 3));
        //print_r($db_array);
        //$tmp_id = $db->insert('transactions',$db_array);
        $db->insert('transactions', array_keys($db_array), $db_array);
        $tmp_id = $db->insert_id();
        return $tmp_id;
    }

    function process_error($error_list) {
        $error_array = explode(',', $error_list);
        $error_array = array_unique($error_array);
        $this->error_messages = $this->return_invalid_fields($error_array);
        //$this->output_invalid_fields($error_array);
        if (count($this->error_messages) < 1 ||
                $this->error_messges[0] == '') {
            $this->error_messages[] = $this->response_message;
        }
    }

    function get_error_messages() {
        return $this->error_messages;
    }

    function return_invalid_fields($fields_list) {
        $tmp_array = array();
        for ($i = 0; $i < count($fields_list); $i++) {
            $tmp_array[$i] = $this->error_fields_messages[$fields_list[$i]];
        }
        return $tmp_array;
    }

    function output_invalid_fields($fields_list) {
        print("The following fields where found to be invalid:<br>\r\n");
        for ($i = 0; $i < count($fields_list); $i++) {
            print(($i + 1) . ") " . $this->error_fields_messages[$fields_list[$i]] . "<br>\r\n");
        }
    }

}

/* end beanstream class */
?>