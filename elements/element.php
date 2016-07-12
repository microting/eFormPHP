<?php
/*
The MIT License (MIT)

Copyright (c) 2016 microting

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/


class Element 
{

function arg( $arg='' )
{
  if( !$arg ) $arg = $this;
    foreach( (array)$arg as $key => $value ){
      if( is_bool( $value ) ){
        $value = $value?'true':'false';
      }elseif( is_array( $value ) ){
        $value = $this->xml_list( $value );
      }elseif( $key == 'color' ){
        $value = $this->color_code( $value );               
      }elseif( strstr($key,'_date')  ){
        $value = $this->date($value,'Y-m-d' );
      }elseif( get_class()=='DateElement' && strstr($key,'value')  ){
        $value = $this->date($value,'d.m.Y G:i:s' );
      }elseif( $key != 'element_list' && $key != 'keyValuePairList' ){
        $value = htmlspecialchars( $value );
      }
      $res[ $key ] = $value; 
    }
  return $res;
}

function xml_format()
{
  return $this->format($this->xml());
}

function xml()
{
  return '';
}


static function color_code( $color )
{
    $colors = array( 
      'e2f4fb', // 0 Blue
      'f5eafa', // 1 Purple
      'f0f8db', // 2 Green
      'fff6df', // 3 Yellow
      'ffe4e4'  // 4 Red
     );          // 5 None
  return @$colors[$color]?:'None';
}

static function xml_list( $arr )
{
    $xml = '';
    foreach($arr as $a) {
        $xml .= method_exists($a,'xml') ? $a->xml() : $a;
    }
  return $xml;
}

static function format( $xml )
{
    $doc = new DOMDocument();
    $doc->preserveWhiteSpace = false;
    $doc->formatOutput = true;
    $doc->loadXML($xml);
    $doc->encoding = 'utf-8';
  return $doc->saveXML();
}

static function date($start_date='',$format='',$add_days=0)
{
    if($format=='') $format='Y-m-d';
    if($start_date=='') $start_date=date($format);
  return date( $format, strtotime($start_date)+$add_days*24*3600 );
} 

} // The end of the Element class 
