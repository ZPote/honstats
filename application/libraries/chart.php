<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chart
{
    private $CI;

    public function __construct()
    {
        $this->CI = get_instance();
        
        require_once 'chart/GoogChart.class.php';
    }
    
    public function pie($title, $data, $legend, $colors, $size = array( 450, 300 ))
    {
        /** Create chart */
        $chart = new GoogChart();

        $chart->setChartAttrs( array(
        'type' => 'pie',
        'title' => $title,
        'data' => $data,
        'legend' => $legend,
        'size' => $size,
        'color' => $colors,
        'background' => 'FFFFFF00'
        ));
        
        // Print chart
        return $chart;
    }
}