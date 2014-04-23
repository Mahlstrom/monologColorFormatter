<?php
/**
 * Created by PhpStorm.
 * User: mahlstrom
 * Date: 23/04/14
 * Time: 09:47
 */

namespace mahlstrom\monolog;

use Monolog\Logger;
use Monolog\Formatter\NormalizerFormatter;

class monologColorFormatter extends NormalizerFormatter
{
    public $colors = array(
        // 0 BLACK
        // 1 RED
        // 2 GREEN
        // 3 YELLOW
        // 4 BLUE
        // 5 PURPLE
        // 6 CYAN
        // 7 WHITE
        LOGGER::DEBUG => array(7, 0, 0),
        LOGGER::INFO => array(6, 0, 0),
        LOGGER::NOTICE => array(2, 7, 0),
        LOGGER::WARNING => array(3, 4, 1),
        LOGGER::ERROR => array(1, 7, 0, true),
        LOGGER::CRITICAL => array(6, 0, 0, true),
        LOGGER::ALERT => array(5, 7, 0, true),
        LOGGER::EMERGENCY => array(1, 7, 1, true),
    );

    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
#        var_dump($record);
        $level = $record['level'];
        $colors = $this->colors[$level];
        $ret = $record['datetime']->format('Y-m-d H:i:s') . ' ';
        $I = $colors[2];
        $FG = $colors[0];
        $BG = $colors[1];
        $this->checkMemory($record['extra'],$ret);
        $ret .= "\033[" . $I . ';3' . $BG . "m\033[4" . $FG . "m " . strtoupper(substr($record['level_name'], 0, 1));
        $scolor = "\033[" . $I . ';3' . $FG . "m";
        if(isset($colors[3]) && $colors[3]==true){
            $scolor .= "\033[" . $I . ';3' . $BG . "m\033[4" . $FG . "m ";
        }
        $ret .= " \033[0m" . ' ';
        $ret .= "[" . $record['channel'] . "] ";
        $ret .= $scolor . $record['message'];
        $ret .= " \033[0m" . PHP_EOL;
        return $ret;
    }
    private function checkMemory(array $recordExtra,&$ret){
        if(array_key_exists('memory_usage',$recordExtra)){
            $ret.='[ '.sprintf("%7s",$recordExtra['memory_usage']).' ] ';
        }
    }
}
