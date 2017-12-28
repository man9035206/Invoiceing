<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * InvoicePlane
 *
 * @author		InvoicePlane Developers & Contributors
 * @copyright	Copyright (c) 2012 - 2017 InvoicePlane.com
 * @license		https://invoiceplane.com/license.txt
 * @link		https://invoiceplane.com
 */

/**
 * Output the amount as a currency amount, e.g. 1.234,56 €
 *
 * @param $amount
 * @return string
 */
function format_currency($amount)
{
    global $CI;
    $currency_symbol = $CI->mdl_settings->setting('currency_symbol');
    $currency_symbol_placement = $CI->mdl_settings->setting('currency_symbol_placement');
    $thousands_separator = $CI->mdl_settings->setting('thousands_separator');
    $decimal_point = $CI->mdl_settings->setting('decimal_point');

    if ($currency_symbol_placement == 'before') {
        //for rupees
        $amount = number_format($amount, ($decimal_point) ? 2 : 0, $decimal_point,'');
        //end for rupees
        return numberToCurrency($amount);
    } elseif ($currency_symbol_placement == 'afterspace') {
        return number_format($amount, ($decimal_point) ? 2 : 0, $decimal_point, $thousands_separator) . '&nbsp;' . $currency_symbol;
    } else {
        return number_format($amount, ($decimal_point) ? 2 : 0, $decimal_point, $thousands_separator) . $currency_symbol;
    }
}

/**
 * Output the amount as a currency amount, e.g. 1.234,56
 *
 * @param null $amount
 * @return null|string
 */
function format_amount($amount = null)
{
    if ($amount) {
        $CI =& get_instance();
        $thousands_separator = $CI->mdl_settings->setting('thousands_separator');
        $decimal_point = $CI->mdl_settings->setting('decimal_point');

        return number_format($amount, ($decimal_point) ? 2 : 0, $decimal_point, $thousands_separator);
    }
    return null;
}

/**
 * Standardize an amount based on the system settings
 *
 * @param $amount
 * @return mixed
 */
function standardize_amount($amount)
{
    $CI =& get_instance();
    $thousands_separator = $CI->mdl_settings->setting('thousands_separator');
    $decimal_point = $CI->mdl_settings->setting('decimal_point');

    $amount = str_replace($thousands_separator, '', $amount);
    $amount = str_replace($decimal_point, '.', $amount);

    return $amount;
}

/**
 * Standardize an amount based on the system settings
 *
 * @param $amount
 * @return mixed
 */
function numTowords($amount) {
    $number = str_replace( ',', '', $amount );
    if (!is_numeric($number)) {
        return "Not a number";
    } 
    // $no = round($number);
    // $point = round($number - $no, 2) * 100;
    $no = explode('.', $number)[0];
    $point = explode('.', $number)[1];
    $hundred = null;
    $digits_1 = strlen($no);
    $digits_2 = strlen($point);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? '' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
        } else $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $i = 0;
    while ($i < $digits_2) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($point % $divider);
        $no = floor($point / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($point) {
            $plural = (($counter = count($str)) && $point > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str_1[] = ($point < 21) ? $words[$point] .
            " " 
            :
            $words[floor($point / 10) * 10]
            . " " . $words[$point % 10] . " "
             ;
        } else $str_1[] = null;
    }
    $str_1 = array_reverse($str_1);
    $points = implode('', $str_1);


    if ($points == "") {
        if($result != "") {
            $word = $result . "Rupees  ";
        } else {
            $word = $result;
        }
    } else {
       $word = $result . "Rupees  ". $points . " Paise";
    }
    return strtoUpper($word);
 }

function numberToCurrency($num)
{
    $num = explode('.', $num);
    $dec  = $num[1];
    $num = $num[0];
      $explrestunits = "" ;
      if(strlen($num)>3){
          $lastthree = substr($num, strlen($num)-3, strlen($num));
          $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
          $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
          $expunit = str_split($restunits, 2);
          for($i=0; $i<sizeof($expunit); $i++){
              // creates each of the 2's group and adds a comma to the end
              if($i==0)
              {
                  $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
              }else{
                  $explrestunits .= $expunit[$i].",";
              }
          }
          $thecash = $explrestunits.$lastthree;
      } else {
          $thecash = $num;
      }
      return '₹ ' . $thecash .'.'. $dec;
}