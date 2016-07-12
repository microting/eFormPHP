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


class NumberElement extends element
{

  public $id;
  public $label;
  public $description;
  public $mandatory;
  public $minValue;
  public $maxValue;
  public $value;
  public $decimalCount;
  public $unitName;
  public $color;
  public $element_id;

function __construct( $id, $label, $description, $mandatory, $minValue, $maxValue, $value, $decimalCount, $unitName, $color, $element_id )
{
  $this->id = $id;
  $this->label = $label;
  $this->description = $description;
  $this->mandatory = $mandatory;
  $this->minValue = $minValue;
  $this->maxValue = $maxValue;
  $this->value = $value;
  $this->decimalCount = $decimalCount;
  $this->unitName = $unitName;
  $this->color = $color;
  $this->element_id = $element_id;
}

public function xml()
{
  extract( $this->arg() );
  return <<<XML
    <DataItem type="number">
      <Value>$value</Value>
      <MinValue>$minValue</MinValue>
      <MaxValue>$maxValue</MaxValue>
      <DecimalCount>$decimalCount</DecimalCount>
      <UnitName>$unitName</UnitName>
      <Id>$id</Id>
      <Label>$label</Label>
      <Description>$description</Description>
      <Mandatory>$mandatory</Mandatory>
      <Color>$color</Color>
    </DataItem>
XML;
} 

} // The end of the NumberElement class 
