<?php

namespace GoGlobal;

use SimpleXMLElement;

class Helper
{
    /**
     * @param string $tag
     * @param string $content
     * @param array $attributes
     * @return string
     */
    public static function wrapTag($tag, $content, $attributes=[]) {
        $r = "<{$tag}";
        if(is_array($attributes)) {
            foreach ($attributes as $ak => $av) {
                $r .= " {$ak}=\"{$av}\"";
            }
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

    /**
     * @param array $map
     * @param SimpleXMLElement $node
     * @param array $array (optional)
     * @return array
     */
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

    /**
     * @param string $string
     * @return string
     */
    public static function stripAccent($string) {
        return transliterator_transliterate('Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove', $string);
    }

    /**
     * @param string $date
     * @return null|string
     */
    public static function fixCxlDate($date) {
        $date = trim($date);
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
            $date = $m[3].'-'.$m[2].'-'.sprintf("%02d",$m[1]);
        }
        if(!preg_match('/^[\d]{4}\-[\d]{2}\-[\d]{2}$/',$date) || $date=='0000-00-00' || $date=='1970-01-01' || $date=='0001-01-01') {
            return null;
        }
        return $date;
    }

}
