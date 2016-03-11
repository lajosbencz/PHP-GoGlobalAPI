<?php

namespace Travelhood\Library\Provider\GoGlobal;

use SimpleXMLElement;

class Helper
{

    public static function wrapTag($tag, $content, $attributes=[]) {
        $r = "<{$tag}";
        foreach($attributes as $ak=>$av) {
            $r.= " {$ak}=\"{$av}\"";
        }
        if($content===false) {
            $r.= " />";
        }
        else {
            $r.= ">";
            $r .= "{$content}";
            $r .= "</{$tag}>";
        }
        return $r;
    }

    public static function map($map, SimpleXMLElement $node, &$array=null) {
        if(!$array || !is_array($array)) $array = [];
        foreach($map as $k=>$v) {
            if(is_array($v)) {
                self::map($v, $node->$k, $array);
            }
            else {
                $s = trim((string)$node->$k);
                $array[$v] = strlen($s)>0?$s:null;
            }
        }
        return $array;
    }

    public static function fixCxlDate($date) {
        if(preg_match('/([\d]{1,2})\/([a-z\d]+)\/([\d]{4})/i',$date,$m)) {
            switch($m[2]) {
                default:
                case 'Jan': $m[2] = '01'; break;
                case 'Feb': $m[2] = '02'; break;
                case 'Mar': $m[2] = '03'; break;
                case 'Apr': $m[2] = '04'; break;
                case 'May': $m[2] = '05'; break;
                case 'Jun': $m[2] = '06'; break;
                case 'Jul': $m[2] = '07'; break;
                case 'Aug': $m[2] = '08'; break;
                case 'Sep': $m[2] = '09'; break;
                case 'Oct': $m[2] = '10'; break;
                case 'Nov': $m[2] = '11'; break;
                case 'Dec': $m[2] = '12'; break;
            }
            $date = $m[3].'-'.$m[2].'-'.$m[1];
        }
        if($date==='0000-00-00' || $date=='1970-01-01' || $date=='0001-01-01') {
            return null;
        }
        return $date;
    }

}