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


    error_reporting(-1);

    include_once 'elements.php';
    
    // Auto start the test_run function if this file is not included.

    if( $_SERVER['SCRIPT_FILENAME'] == str_replace('\\','/',__FILE__)) test_run();  

function test_run()
{
    $xml1 =  test_generate('20.04.2016 8:52:12');
    file_put_contents('new.xml',$xml1);
    $xml0 = file_get_contents('sample.xml');
    $ok =  $xml1 === Element::format($xml0);
    print "The test has ".($ok?'succeeded':'failed').".<br>"; 
    print "The XML code is: <br>". highlight_string($xml1,1);
}

function test_generate( $date='' )
{
    $startDate = $date;  
    $endDate = Element::date( $date,'',365 );
    $testDate = $date;  

    $singleKeyValuePairList = array (
        new KeyValuePair( "1", "option 1", true, "1" ),
        new KeyValuePair( "2", "option 2", false, "2" ),
        new KeyValuePair( "3", "option 3", false, "3" )
    );

    $multiKeyValuePairList = array (
        new KeyValuePair( "1", "option 1", true, "1" ),
        new KeyValuePair( "2", "option 2", true, "2" ),
        new KeyValuePair( "3", "option 3", false, "3" )
    );

    $mainElement = new MainElement( "Check list 20", "Check list", 1, $startDate, $endDate, "en", true, false, "0", false, false, true, false, false, "Odense", false, "", "bla bla bla",'');

    $Element1 =   new SubElement( "check list colors", "Check list colors", 0, $startDate, $endDate, "en", false, false, "Check list 20", false, false, false, true, false, "", false, "", "This is the description for the color fields",'');

    $Element1->element_list = array(    
        new SingleSelectElement( "2", "Single select search field", "this is a description", false, $singleKeyValuePairList, 0, "check list colors" ),
        new MultiSelectElement( "3", "Multi select field", "this is a description", false, $multiKeyValuePairList, 0, "check list colors" ),
        new NumberElement( "4", "Number field", "this is a description", false, 0, 10000, 0, 2, "", 0, "check list colors" ),
        new TextElement( "5", "Text field", "this is a description", "check list colors", false, "", 50, true, false, false, 0 ),
        new CommentElement( "6", "Comment field", "this is a description", false, "", 10000, false, 5, "check list colors" ),
        new PictureElement( "7", "Picture field", "this is a description", false, 1, 5, "check list colors" ),          
        new CheckBoxElement( "8", "Check box field", "this is a description", false, false, false, 4, "check list colors" ),            
        new AudioElement( "9", "Audio field", "this is a description", false, 1, 4, "check list colors" ),          
        // new PdfElement( "10", "PDF field", "this is a description", "path/to/pdf,pdf", false, 3, "check list colors" ),  
        new DateElement( "11", "Date field", "this is a description", false, $testDate, $testDate, $testDate, 3, "check list colors" ),
        new NoneElement( "12", "None field, only shows text", "this is a description", false, false, 2, "check list colors" ),
        new TimerElement( "13", "Timer", "this is a description", false, 2, "check list colors" ),
        new SignatureElement( "14", "Signature", "this is a description", false, 1, "check list colors" )
     );

    $Element2 =  new SubElement( "check list 1", "Check list 1", 0, $startDate, $endDate, "en", false, false, "Check list 20", false, false, false, true, false, "", false, "", "this is the description 1",'');

    $Element2->element_list = array(    
        new SingleSelectElement( "2", "Single select search field", "this is a description", false, $singleKeyValuePairList, 0, "check list 1" ),
        new MultiSelectElement( "3", "Multi select field", "this is a description", false, $multiKeyValuePairList, 0, "check list 1" ),
        new NumberElement( "4", "Number field", "this is a description", false, 0, 10000, 0, 2, "", 0, "check list 1" ),
        new TextElement( "5", "Text field", "this is a description", "check list 1", false, "", 50, true, false, false, 0 ),
        new CommentElement( "6", "Comment field", "this is a description", false, "", 10000, false, 5, "check list 1" ),
        new PictureElement( "7", "Picture field", "this is a description", false, 1, 5, "check list 1" ),
        new CheckBoxElement( "8", "Check box field", "this is a description", false, false, false, 4, "check list 1" ),
        new AudioElement( "9", "Audio field", "this is a description", false, 1, 4, "check list 1" ),
        //  new PdfElement( "10", "PDF field", "this is a description", "path/to/pdf,pdf", false, 3, "check list 1" ),
        new DateElement( "11", "Date field", "this is a description", false, $testDate, $testDate, $testDate, 3, "check list 1" ),
        new NoneElement( "12", "None field, only shows text", "this is a description", false, false, 2, "check list 1" ),
        new TimerElement( "13", "Timer", "this is a description", false, 2, "check list 1" ),
        new SignatureElement( "14", "Signature", "this is a description", false, 1, "check list 1" )      
     );

    $mainElement->element_list[] = $Element1;
    $mainElement->element_list[] = $Element2;

    return $mainElement->xml_format();
}

