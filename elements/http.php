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

class HTTP
{

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



function __construct( $ServerAddress, $ServerToken,  $SiteUuid, $ElementId=0, $CheckUuid = 0, $Protocol = 5 )
{ 
    $this->ServerAddress = $ServerAddress; 
    $this->ServerToken = $ServerToken; 
    $this->SiteUuid  = $SiteUuid;  
    $this->ElementId  = $ElementId;  
    $this->CheckUuid = $CheckUuid; 
    $this->Protocol = $Protocol; 
    $this->UrlSend = "%s/gwt/inspection_app/integration/?token=%s&protocol=$Protocol&site_id=%s";
    $this->UrlCheck = "%s/gwt/inspection_app/integration/%s?token=%s&protocol=$Protocol&site_id=%s&download=false&delete=false"; 
    $this->UrlFetch = "%s/gwt/inspection_app/integration/%s?token=%s&protocol=$Protocol&site_id=%s&download=true&delete=false&last_check_id=%s";
    $this->UrlDelete = "%s/gwt/inspection_app/integration/%s?token=%s&protocol=$Protocol&site_id=%s&download=false&delete=true";
}

function post( $xmlData, $SiteUuid='' )
{   
    if( !$SiteUuid ) $SiteUuid = $this->SiteUuid;
    $url = sprintf( $this->UrlSend, $this->ServerAddress,  
        $this->ServerToken, $SiteUuid ); 
    $post = $xmlData;
    $fetch = $this->curl( $url, $post );
    return new ElementValues( $fetch );
}

function status( $ElementId='',  $SiteUuid='' )
{   
    if( !$ElementId ) $ElementId = $this->ElementId;
    if( !$SiteUuid ) $SiteUuid = $this->SiteUuid;
    $url = sprintf( $this->UrlCheck, $this->ServerAddress, 
        $this->ElementId, $this->ServerToken, $this->SiteUuid );
    return new ElementValues( $this->curl( $url ), $ElementId );
}

function retrieve( $ElementId='',  $SiteUuid='' )
{   
    if( !$ElementId ) $ElementId = $this->ElementId;
    if( !$SiteUuid ) $SiteUuid = $this->SiteUuid;
    $url = sprintf( $this->UrlFetch, $this->ServerAddress, $this->ElementId, 
        $this->ServerToken, $this->SiteUuid, $this->CheckUuid );
    return new ElementValues( $this->curl( $url ), $ElementId );
 }

function delete( $ElementId='',  $SiteUuid='' )
{   
    if( !$ElementId ) $ElementId = $this->ElementId;
    if( !$SiteUuid ) $SiteUuid = $this->SiteUuid;
    $url = sprintf( $this->UrlDelete, $this->ServerAddress, $this->ElementId, 
        $this->ServerToken, @$this->SiteUuid );
    return new ElementValues( $this->curl( $url ), $ElementId );
}

static function curl( $url, $post='' )
{
    if( !function_exists('curl_init') ) return '<b>No CURL</b>';
    $ch  = curl_init( $url);            
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER    ,true  );
    curl_setopt( $ch, CURLOPT_ENCODING          ,'UTF8');            
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER    ,false );     
    if( $post ) curl_setopt( $ch, CURLOPT_POST  ,true  );     
    if( $post ) curl_setopt( $ch, CURLOPT_POSTFIELDS, $post );       
    $contents = curl_exec( $ch );//. curl_error($ch);
    curl_close( $ch );                 
    return $contents;
}


} // The end of the HTTP class 

