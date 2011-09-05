<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function parseColors($string)
{
    $result = '<span>';
    if (preg_match('/^([^\^.]+)/', $string, $start))
        $result .= $start[0];

    $string = preg_replace('/\^w/i', '^999', $string);
    $string = preg_replace('/\^r/i', '^900', $string);
    $string = preg_replace('/\^y/i', '^990', $string);
    $string = preg_replace('/\^g/i', '^090', $string);
    $string = preg_replace('/\^k/i', '^000', $string);
    $string = preg_replace('/\^c/i', '^099', $string);
    $string = preg_replace('/\^b/i', '^009', $string);
    $string = preg_replace('/\^m/i', '^909', $string);
    $string = preg_replace('/\^n/i', '^320', $string);
    $string = preg_replace('/\^p/i', '^505', $string);
    $string = preg_replace('/\^v/i', '^444', $string);
    $string = preg_replace('/\^t/i', '^156', $string);
    $string = preg_replace('/\^:/i', '', $string);

    preg_match_all('/\^([0-9])([0-9])([0-9])([^\^.]+)/', $string, $parsed, PREG_PATTERN_ORDER);

    $i = 0;
    foreach($parsed[0] as $osef)
    {
        $result .= '</span><span style="color:rgb('.round(28.3*$parsed[1][$i],0).','.round(28.3*$parsed[2][$i],0).','.round(28.3*$parsed[3][$i],0).');">'.$parsed[4][$i];
        $i++;
    }

    $result .= '</span>';
    return $result;
}

function colorScale($value, $min, $max)
{
    if($value < $min)
        $color = 'low';
    else if($value >= $max)
        $color = 'high';
    else
        $color = 'avg';
    
    return '<span class='.$color.'>'.$value.'</span>';
}

function dieBots()
{
    if(isset($_SERVER['HTTP_USER_AGENT']))
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        $bot_strings = array(
        "google", "bot", "yahoo", "spider", "archiver", "curl",
        "python", "nambu", "twitt", "perl", "sphere", "PEAR",
        "java", "wordpress", "radian", "crawl", "yandex", "eventbox",
        "monitor", "mechanize", "facebookexternal", "bingbot"
        );

        foreach($bot_strings as $bot)
        {
            if(strpos($agent, $bot) !== false)
            {
                die('bot');
            }
        }
    }
}

function TSR($array)
{
    $TSR = (($array['kd']/1.1)*0.65)+(($array['ad']/1.5)*1.20)+((($array['winpr'])/0.55)*0.9)+((($array['gpm'])/190)*0.35)
        +(((($array['expm'])/420))*0.40)+(((((($array['cd'])/12))*0.70)+(((($array['ck'])/93))*0.50)+(($array['wards'])/0.55*0.30))*(38.5/($array['game_length'])));
    return round($TSR, 2);
}

function rTSR($array)
{
    $rTSR = (($array['kd']/1.15)*0.65)+(($array['ad']/1.55)*1.20)+((($array['winpr'])/0.55)*0.9)+((($array['gpm'])/230)*0.35)+(((($array['expm'])/380))*0.40)
        +(((((($array['cd'])/12))*0.70)+(((($array['ck'])/93))*0.50)+(($array['wards'])/1.45*0.30))*(37.5/($array['game_length'])));
    return round($rTSR, 2);
}