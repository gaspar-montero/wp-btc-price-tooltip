<?php
/*
Plugin Name: Bitcoin Price Tooltip
Description: Plugin will find mentions of Bitcoin in your texts and automatically add a toltip to it with actual price in USD and EUR.
Version: 1.0
Author: Gaspar Montero
Author URI: http://freedomfinance.es/
*/

function add_tooltip_to_btc($the_content)
{
    static $btc_in_text = array(
        '0' => 'BTC',
        '1' => 'Bitcoin',
    );

    $url = "https://bitpay.com/api/rates";
    $json = json_decode(file_get_contents($url));
    $dollar = $btc = 0;
    foreach ($json as $obj)
    {
        if ($obj->code == "USD")
        {
            $btcAnswerUsd = $obj->rate . ' ' . $obj->code . ' ';
        }
        if ($obj->code == "EUR")
        {
            $btcAnswerEUR = $obj->rate . ' ' . $obj->code . ' ';
        }
    }

    for ($i = 0, $c = count($btc_in_text);$i < $c;$i++)
    {
        //echo $btc_in_text[$i];
        $the_content = preg_replace('#' . $btc_in_text[$i] . '#iu', '<span class="tooltip" data-tooltip="' . $btcAnswerUsd . '/ ' . $btcAnswerEUR . '">' . $btc_in_text[$i] . '</span>', $the_content);
    }

    return $the_content;
}

function add_btc_tooltip_css()
{
    wp_enqueue_style('tooltip-style', plugins_url('tooltip-style.css', __FILE__) , false, '1.0.0', 'all');
}

add_action('wp_enqueue_scripts', "add_btc_tooltip_css");
add_filter('the_content', 'add_tooltip_to_btc');
