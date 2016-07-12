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


class KeyValuePair extends element
{

  public $key;
  public $valueOfKeyValuePair;
  public $selected;
  public $displayOrder;

function __construct( $key, $valueOfKeyValuePair, $selected, $displayOrder )
{
  $this->key = $key;
  $this->valueOfKeyValuePair = $valueOfKeyValuePair;
  $this->selected = $selected;
  $this->displayOrder = $displayOrder;
}

public function xml()
{
  extract( $this->arg() );
  return <<<XML
    <KeyValuePair>
      <Key>$key</Key>
      <Value>$valueOfKeyValuePair</Value>
      <Selected>$selected</Selected>
      <DisplayOrder>$displayOrder</DisplayOrder>
    </KeyValuePair>
XML;
} 

} // The end of the KeyValuePair class 
