<?php

namespace GoGlobal_Tests;

use GoGlobal\Helper;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function provideWrapTagData()
    {
        return [
            ['tag',null,['a1'=>1,'a2'=>2],'<tag a1="1" a2="2"></tag>'],
            ['tag',null,null,'<tag></tag>'],
            ['tag','',null,'<tag></tag>'],
            ['tag',false,null,'<tag />'],
            ['tag',false,['a1'=>1,'a2'=>2],'<tag a1="1" a2="2" />'],
            ['tag','content',null,'<tag>content</tag>'],
            ['tag','content',['a1'=>1,'a2'=>2],'<tag a1="1" a2="2">content</tag>'],
        ];
    }

    public function provideStripAccentData()
    {
        return [
            ['Árvíztűrő Tükörfúrógép','Arvizturo Tukorfurogep'],
        ];
    }

    public function provideFixCxlDateData()
    {
        return [
            [null,null],
            ['',null],
            ['0000-00-00',null],
            ['1970-01-01',null],
            ['0001-01-01',null],
            ['1/Jan/2015','2015-01-01'],
            ['02/Feb/2015','2015-02-02'],
            ['2015-03-03','2015-03-03'],
        ];
    }

    /**
     * @dataProvider provideWrapTagData
     * @param $tag
     * @param $content
     * @param $attributes
     * @param $xml
     */
    public function testWrapTag($tag, $content, $attributes, $xml)
    {
        $this->assertEquals($xml,Helper::wrapTag($tag, $content, $attributes));
    }

    public function testMap()
    {
        $xml = new \SimpleXMLElement("<root><item><name>title</name><value>content</value></item></root>");
        foreach($xml->root as $item) {
            $a = Helper::map([
                'name' => 'title',
                'value' => 'content',
            ], $item, $b);
            $this->assertEquals(['title'=>'title','content'=>'content'],$a);
            $this->assertEquals($a,$b);
        }
    }

    /**
     * @dataProvider provideStripAccentData
     * @param $accents
     * @param $expected
     */
    function testStripAccent($accents,$expected)
    {
        $this->assertEquals($expected,Helper::stripAccent($accents));
    }

    /**
     * @dataProvider provideFixCxlDateData
     * @param $date
     * @param $expected
     */
    function testFixCxlDate($date, $expected)
    {
        $this->assertEquals($expected, Helper::fixCxlDate($date));
    }
}
