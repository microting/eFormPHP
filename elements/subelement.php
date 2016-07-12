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


class SubElement extends element
{

  public $id;
  public $label;
  public $repeated;
  public $start_date;
  public $end_date;
  public $language;
  public $multi_approval;
  public $fast_navigation;
  public $parent_id;
  public $review_enabled;
  public $approved_enabled;
  public $done_button_enabled;
  public $extra_data_elements_enabled;
  public $manual_sync;
  public $folder_name;
  public $download_entities;
  public $pink_bar_text;
  public $description;
  public $element_list;

function __construct( $id, $label, $repeated, $start_date, $end_date, $language, $multi_approval, $fast_navigation, $parent_id, $review_enabled, $approved_enabled, $done_button_enabled, $extra_data_elements_enabled, $manual_sync, $folder_name, $download_entities, $pink_bar_text, $description, $element_list )
{
  $this->id = $id;
  $this->label = $label;
  $this->repeated = $repeated;
  $this->start_date = $start_date;
  $this->end_date = $end_date;
  $this->language = $language;
  $this->multi_approval = $multi_approval;
  $this->fast_navigation = $fast_navigation;
  $this->parent_id = $parent_id;
  $this->review_enabled = $review_enabled;
  $this->approved_enabled = $approved_enabled;
  $this->done_button_enabled = $done_button_enabled;
  $this->extra_data_elements_enabled = $extra_data_elements_enabled;
  $this->manual_sync = $manual_sync;
  $this->folder_name = $folder_name;
  $this->download_entities = $download_entities;
  $this->pink_bar_text = $pink_bar_text;
  $this->description = $description;
  $this->element_list = $element_list;
}

public function xml()
{
  extract( $this->arg() );
  return <<<XML
    <Element type="DataElement">
      <Id>$id</Id>
      <Label>$label</Label>
      <Description>$description</Description>
      <ReviewEnabled>$review_enabled</ReviewEnabled>
      <ApprovedEnabled>$approved_enabled</ApprovedEnabled>
      <DoneButtonEnabled>$done_button_enabled</DoneButtonEnabled>
      <ExtraFieldsEnabled>$extra_data_elements_enabled</ExtraFieldsEnabled>
      <DataItemList>$element_list</DataItemList>
    </Element>
XML;
} 

} // The end of the SubElement class 
