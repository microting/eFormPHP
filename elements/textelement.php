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


class TextElement extends element
{

  public $id;
  public $label;
  public $description;
  public $element_id;
  public $mandatory;
  public $value;
  public $maxLength;
  public $geolocationEnabled;
  public $geolocationForced;
  public $geolocationhidden;
  public $color;

function __construct( $id, $label, $description, $element_id, $mandatory, $value, $maxLength, $geolocationEnabled, $geolocationForced, $geolocationhidden, $color )
{
  $this->id = $id;
  $this->label = $label;
  $this->description = $description;
  $this->element_id = $element_id;
  $this->mandatory = $mandatory;
  $this->value = $value;
  $this->maxLength = $maxLength;
  $this->geolocationEnabled = $geolocationEnabled;
  $this->geolocationForced = $geolocationForced;
  $this->geolocationhidden = $geolocationhidden;
  $this->color = $color;
}

public function xml()
{
  extract( $this->arg() );
  return <<<XML
    <DataItem type="text">
      <Value>$value</Value>
      <MaxLength>$maxLength</MaxLength>
      <GeolocationEnabled>$geolocationEnabled</GeolocationEnabled>
      <GeolocationForced>$geolocationForced</GeolocationForced>
      <GeolocationHidden>$geolocationhidden</GeolocationHidden>
      <Id>$id</Id>
      <Label>$label</Label>
      <Description>$description</Description>
      <Mandatory>$mandatory</Mandatory>
      <Color>$color</Color>
    </DataItem>
XML;
} 

} // The end of the TextElement class 
