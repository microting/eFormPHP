
THE DESCRIPTION OF THE ELEMENT CLASSES LIBRARY
==============================================


The file test.php contains the text of these classes. Below is the description
of the classes.

Class HTTP and its methods.

    Constructor:
    HTTP( $ServerAddress, $ServerToken,  $SiteUuid, 
        $ElementId=0, $CheckUuid = 0, $Protocol = 5 )

    Propeties:
    public $ServerAddress;
    public $ServerToken; 
    public $Protocol; 
    public $SiteUuid;
    public $ElementId;
    public $CheckUuid;
    public $UrlSend;
    public $UrlCheck;
    public $UrlFetch;
    public $UrlDelete;


    Metods: 

    ElementValue post( $xmlData, $SiteUuid='' )     
            --  Post elements XML to the Server.
    ElementValue status( $ElementId='',  $SiteUuid='' ) 
            -- Get the status from the Server.
    ElementValue retrieve( $ElementId='',  $SiteUuid='' ) 
            -- Retrive from the Server.     
    ElementValue delete( $ElementId='',  $SiteUuid='' )   
            -- Delete elements from Server.
    string curl( $url, $post='' )       
            -- The cURL request to the server.

    The methods post, status, retrieve and delete return the object of the 
    class ElementValue. The method curl return the server returned string 
    or curl error.

Class ElementsValues.

    Constructor:
    ElementsValues( $XML, $ElementId = '')

    Propeties:
    public $ElementId;
    public $Value;
    public $Status;
    public $Check;
    public $XML;


Class Elements and its methods.

    Metods:
    arg( $arg='' )              -- Prepare arguments to pass in xml.
    xml_format()                -- Prepare the formatted xml.
    xml()                       -- Prepares the draft xml.   
    static color_code( $color ) -- Replaces the color number to the color code.
    static xml_list( $arr )     -- Creates the array from the xml.
    static format( $xml )       -- Format any xml code.
    static date( $start_date='',$format='',$add_days=0 ) 
        -- Formats any date and adds thereto days if necessary.

The next classes are inherited from the class Element. Their constructors are
shown below. The list of properties of these classes corresponds to the 
names of their constructors. All properties of these classes available to 
change at any time. All elements of the lists can take an array of other 
items. The arrays content is also available to change at any time.

MainElement( $id, $label, $repeated, $start_date, $end_date, $language, 
    $multi_approval, $fast_navigation, $parent_id, $review_enabled, 
    $approved_enabled, $done_button_enabled, $extra_data_elements_enabled, 
    $manual_sync, $folder_name, $download_entities, $pink_bar_text, 
    $description, $element_list); 

SubElement( $id, $label, $repeated, $start_date, $end_date, $language, 
    $multi_approval, $fast_navigation, $parent_id, $review_enabled, 
    $approved_enabled, $done_button_enabled, $extra_data_elements_enabled, 
    $manual_sync, $folder_name, $download_entities, $pink_bar_text, 
    $description, $element_list );

KeyValuePair( $key, $valueOfKeyValuePair, $selected, $displayOrder ); 

AudioElement( $id, $label, $description, $mandatory, $multi, $color, 
    $element_id );

CheckBoxElement( $id, $label, $description, $mandatory, $value, $selected, 
    $color, $element_id );

CommentElement( $id, $label, $description, $mandatory, $value, $maxLength, 
    $splitScreen, $color, $element_id );

DateElement( $id, $label, $description, $mandatory, $minValue, $maxValue, 
    $value, $color, $element_id );

MultiSelectElement( $id, $label, $description, $mandatory, $keyValuePairList, 
    $color, $element_id ); 

NoneElement( $id, $label, $description, $mandatory, $readOnly, $color, 
    $element_id );

NumberElement( $id, $label, $description, $mandatory, $minValue, $maxValue, 
    $value, $decimalCount, $unitName, $color, $element_id ); 

PdfElement( $id, $label, $description, $path_value, $mandatory, $color, 
    $element_id );

PictureElement( $id, $label, $description, $mandatory, $multi, $color, 
    $element_id );

SignatureElement( $id, $label, $description, $mandatory, $color, $element_id );

SingleSelectElement( $id, $label, $description, $mandatory, $keyValuePairList, 
    $color, $element_id ); 

TextElement( $id, $label, $description, $element_id, $mandatory, $value, 
    $maxLength, $geolocationEnabled, $geolocationForced, $geolocationhidden, 
    $color );

TimerElement( $id, $label, $description, $mandatory, $color, $element_id );




The MIT License (MIT)
======================

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



