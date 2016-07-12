<?//  SQL-View-Controller FrameWork     
////  Version 2.0
////  (C) 2007-2017, Vadim Romanko
////  Zhytomyr, Ukraine
////////////////////////////////////////

/// SQL SESION ////////////////////////

//SQL base
function sql_db($base='',$host='localhost',$user='root',$pass='') {
    if(strstr($base,'/')){      /// new
        @extract(parse_url("mysql://$base"));
        @$base = str_replace('/','',$path);
    }   
    if($ret = @mysql_pconnect($host,$user,$pass)){
        $ret = @mysql_select_db($base) or  
            @sql_query("CREATE DATABASE $base") and $ret = @mysql_select_db($base);
    }else{
       sql_debug() && mysql_error() and view( mysql_error());
    }
return $ret;}

function sql_exists($table){
    $tables=sql_col_assoc("SHOW TABLES");
return array_key_exists($table, $tables);}

function sql_query($sql) { 
    $query = @mysql_unbuffered_query($sql); 
    if (sql_debug()==2 or sql_debug() && mysql_error()) 
        view_pre( mysql_error(),"\r\nSQL: $sql");
return $query;}

function sql_id($sql) { //view($sql);
    $query = sql_query($sql); 
return mysql_insert_id();}

function sql_replace($table_name,$data, $TYPE='REPLACE'){
    $fields = sql_col_cash("SHOW FIELDS FROM `$table_name`");
    foreach($fields as $field){
        is_array(@$data[$field]) and $data[$field]=@join(',',$data[$field]);
        isset($data[$field]) and $SET[]="`$field`='$data[$field]'";
    }
    @$SET and $id=sql_id($q="$TYPE `$table_name` SET ".join(',',$SET));
return @$id;}

function sql_debug($set='get') {static $debug; 
    isset($debug) or $debug=error_reporting()?1:0;
    $set === 'get' or $debug = $set; 
return @$debug;}

//Select data from DB
function sql_table($sql) { //SELECT name1, name2, ... FROM table
    $query = sql_query($sql);
    for($table = array(); $rec = @mysql_fetch_assoc($query); $table[] = $rec);
    sql_debug()==3 and view($sql) . view_table($table);
return (array)$table;}

function sql_table_assoc($sql) { //SELECT id, name1, name2, ... FROM table
    $query = sql_query($sql);
    for($table = array(); $rec = @mysql_fetch_assoc($query);) 
        $table[array_shift($rec)] = $rec;
    sql_debug()==3 and view($sql) . view_table($table);
return (array)$table;}

function sql_table_group($sql) { //SELECT group, name1, name2, ... FROM table
    $query = sql_query($sql);
    for($table = array(); $rec = @mysql_fetch_assoc($query);) 
        $table[array_shift($rec)][] = $rec;
    sql_debug()==3 and view($sql) . view_table($table);
return (array)$table;}

function sql_row($sql) { //SELECT name1, name2, ... FROM table WHERE id = row
    $query = sql_query("$sql LIMIT 1"); 
    $row = mysql_fetch_assoc($query);
    sql_debug()==3 and view($sql,$row);
return ctrl_set($row,array());}

function sql_col($sql) { //SELECT name FROM table
    $query = sql_query($sql);
    $table = array();
    for(;$row = @mysql_fetch_row($query);) $table[] = $row[0];
    sql_debug()==3 and view($sql,$table);
return (array)$table;}

function sql_col_assoc($sql) { //SELECT id, name FROM table
    $query = sql_query($sql);
    $table= array();
    for(;$row = @mysql_fetch_row($query);) @$table[$row[0]] = $row[1];
    sql_debug()==3 and view($sql,$table);
return (array)$table;}

function sql_value($sql) { //SELECT name FROM table WHERE id = row
    $query = sql_query("$sql LIMIT 1");
    $value_arr = @mysql_fetch_row($query);
    $value = @$value_arr[0].'';
    sql_debug()==3 and view($sql,$value);
return $value;}

function sql_table_cash($sql) { 
    static $cash;
    if( isset($cash[md5($sql)])){
        $value =  $cash[md5($sql)];
    }else{ 
        $value = sql_table($sql); 
        $cash[md5($sql)] = $value;
    }
return (array)$value;}

function sql_row_cash($sql) { 
    static $cash;
    if( isset($cash[md5($sql)])){
        $value =  $cash[md5($sql)];
    }else{ 
        $value = sql_row($sql); 
        $cash[md5($sql)] = $value;
    }
return (array)$value;}

function sql_col_cash($sql) { 
    static $cash;
    if( isset($cash[md5($sql)])){
        $value =  $cash[md5($sql)];
    }else{ 
        $value = sql_col($sql); 
        $cash[md5($sql)] = $value;
    }
return (array)$value;}

function sql_col_assoc_cash($sql) { 
    static $cash;
    if( isset($cash[md5($sql)])){
        $value =  $cash[md5($sql)];
    }else{ 
        $value = sql_col_assoc($sql); 
        $cash[md5($sql)] = $value;
    }
return (array)$value;}

function sql_value_cash($sql) { 
    static $cash;
    if( isset($cash[md5($sql)])){
        $value =  $cash[md5($sql)];
    }else{ 
        $value = sql_value($sql); 
        $cash[md5($sql)] = $value;
    }
return $value;}

//Tansaction
function sql_start_transaction($sql) {
    sql_query('START TRANSACTION');
    $query = sql_query($sql);
return @$query;}

function sql_commit($sql) {
    if ($query = sql_query($sql))
        sql_query('COMMIT'); 
    else  
        sql_query('ROLLBACK');
return @$query;}

//Define fields
function sql_database_define($fields_show='') {
    $out = '';
    $tables= sql_col("SHOW TABLES");
    foreach ($tables as $table) :
        $out .= sql_table_define($table,$fields_show);
    endforeach;        
return $out;}

function sql_table_define($table,$fields_show=''){
    if($fields_show=="fields")    $out= "<br>//table $table<br>";
    if($fields_show=="define")    $out= "<br>//table $table<br>";
    if($fields_show=="show")      $out= "<br>$table<br>";
    $fields = sql_col("SHOW FIELDS FROM $table");
    foreach($fields as $field):
        @define($field,$field);
        $name = str_replace('_',' ',$field);
        if($fields_show=="define") $out.= "  @define('$field','$field');<br>";
        if($fields_show=="fields") $out.= "  \$field_names['$table']['$field'] = '$name';<br>";
        if($fields_show=="show")   $out.= "$field<br>";
    endforeach; 
return $out;}

//Export & import
function sql_export($table='',$limit='') {
    if($table)   
        return sql_export_table($table).sql_export_data($table,$limit);
    $tables = sql_col('SHOW TABLES');
    foreach($tables as $table) 
        @$sql .= sql_export_table($table).sql_export_data($table,$limit);
return $sql;}

function sql_export_table($table) {
    // fields
    $fields  = sql_table("SHOW FIELDS FROM `$table`");
    $rows = array();
    foreach ($fields as $field) {
        $row = "  `$field[Field]` $field[Type]";
        if ($field['Type'] == 'timestamp' && @$field['Default'] == 'CURRENT_TIMESTAMP') 
            $row .= ' default CURRENT_TIMESTAMP';
        elseif (isset($field['Default'])) $row .= " default '$field[Default]'";
        $field['Null'] == 'YES' or $row .= ' not null';
        @$field[Extra] and $row .= " $field[Extra]";
        $rows[] = $row;
    }
    // keys
    $keys = sql_table("SHOW KEYS FROM $table");
    $index = array();
    foreach ($keys as $key) {
        $kname = $key['Key_name'];
        if (!isset($index[$kname])) {
          $index[$kname] = array(
             'unique'   => !$key['Non_unique'],
             'fulltext' => ($key['Index_type'] == 'FULLTEXT' ? '1' : '0'),
             'columns'  => array());
        }
        $index[$kname]['columns'][] = $key['Column_name'];
    }
    foreach ($index as $kname => $info) {
        $columns = '`'.implode($info['columns'], '`, `').'`';
        if ($kname == 'PRIMARY')           $rows[]="  primary key ($columns)";
        elseif ( $info['fulltext'] == '1' )$rows[]="  fulltext  `$kname` ($columns)";
        elseif ($info['unique'])           $rows[]="  unique  `$kname` ($columns)";
        else                               $rows[]="  key `$kname` ($columns)";
    }
    // make
    @$sql .= 
    "drop table if exists `$table`;\r\n" .
    "create table `$table` (\r\n".
    join(",\r\n", $rows).
    "\r\n);\r\n\r\n";
return $sql;}

function sql_export_data($table,$limit='') {
    $LIMIT = $limit? ' LIMIT '.+$limit:'';
    $fields = sql_col("SHOW FIELDS FROM `$table`");
    $data = sql_table("SELECT * FROM `$table` $LIMIT");
    $lines = array();
    foreach ($data as $rec) {
        $cols = array();
        foreach ($rec as $field) {
            $cols[] = !isset($field)?  'NULL': 
                "'". str_replace( 
                    array("\n","\r","\0","\t"), 
                    array('\n','\r','\0','\t'),
                    addslashes($field)
                )."'";
        }
        $lines[] = join(', ', $cols);
    }
    count($lines) and  @$sql .= 
        "insert into $table (`" . join("`, `", $fields) . "`) values \r\n".
        "  (".join("),\r\n  (", $lines).");\r\n\r\n";
return @$sql;}

function sql_import($sql){
    strlen($sql)<255 and file_exists($sql) and $sql=file_get_contents($sql);
    $sql=str_replace(";\r\n",";\n",$sql);
    $sqls = explode(";\n",$sql);
    foreach($sqls as $sql) {
        trim($sql) and sql_query($sql) and @$count++;
    }
return @$count;}

//// VIEW SESION ///////////////////////////////////////////////////////////
function view() {
    view_style('one');
    $ret =func_num_args()? '': view_str($GLOBALS);
    foreach((array)func_get_args() as $A) $ret.=view_str($A);
    print "<table class=svc border=1><td> $ret </table>";
return @func_get_arg(0);}

function view_pre() {
    view_style('one');
    $ret =func_num_args()? '': view_str($GLOBALS);
    foreach((array)func_get_args() as $A) $ret.=view_str($A);
    print "<table class=svc border=1><td><pre> $ret </table>";
return @func_get_arg(0);}

function view_str ($A) { $k0=$out='';
    if(is_array($A) || is_object($A)) 
        foreach ((array)$A as $k=>$v) 
            @"HTTP{$k}_VARS" === $k0 or "HTTP{$k0}_VARS" === ($k0=$k) or 
            $out.= "<tr><td><b> $k <td>". ("$k"=='GLOBALS'? $v: view_str ($v));
    else return "".htmlspecialchars((string)$A)."";
    $str = @"<table class=svc border=1>$out</table>";
return $str;}

function ctrl_arr2xml($A) {
    if(is_array($A) || is_object($A)) 
        foreach ((array)$A as $k=>$v) 
            @"HTTP{$k}_VARS" === $k0 or "HTTP{$k0}_VARS" === ($k0=$k) or 
            $out.= "<$k>". ("$k"=='GLOBALS'? $v: ctrl_arr2xml ($v))."</$k>\n";
    else return "".htmlspecialchars((string)$A)."";
    $str = @"\n$out";
return $str;}

function view_style( $new_style){
    static $second;
    $style =$new_style!== 'one'?$new_style:
        'table.svc{color:#333;border:0} .svc.td{color:#00F} .svc b{color:#A33} 
        .svc th,.svc td{text-align:center;border:0;border-left:dotted 1px;border-top:dotted 1px}';
    if(!@$second && $style!=='one') print $second = "<style>$style</style>";
}   

function view_log($message,$debug_level='',$file='log.html'){
    if(!is_int($debug_level)&&$debug_level!='') $file=$debug_level; 
    if(!is_int($debug_level) or $debug_level&ctrl_var('ctrl_debug'))  
        ctrl_file(ctrl_ext($file,'html'),view_str(array(@date('Y-md:Hi')=>$message)),'a+');
return $message;}

function view_table_str($table,$caption=''){ 
  $captin = $caption?'<caption>$caption</caption>':'';  
  $out = '<tr><th>'.@join('<th>',ctrl_labels(array_keys($table[0])));
  foreach((array)$table as $row){
    foreach ((array)$row as $i=>$col)
        $row[$i]=view_str($col); 
    $out .= '<tr><td>'.@join('<td>',$row); 
  }     
return "<table class=svc>$caption$out</table>";}

function view_table($table,$caption=''){ 
print view_table_str($table,$caption);}

function view_html_table($table,$caption=''){ 
  view_style('one');
  $out = '<tr><th>'.@join('<th>',ctrl_labels(array_keys($table[0])));
  foreach((array)$table as $row){
    foreach ((array)$row as $i=>$col)
        $row[$i]=@$col; 
    $out .= '<tr><td>'.@join('<td>',$row); 
  }     
    print "<table class=svc border=1><caption>$caption</caption>$out</table>";
}

//view_var($_SERVER);   
function view_var($var){
    highlight_string('<? '.var_export($var,1));
return $var;}   

function view_database(){
    foreach(sql_col('SHOW TABLES') as $table){
        view_table(sql_table("SELECT * FROM $table"));
    }
} 

function view_json($array){
print @json_encode($array);}

//The file viewers
function view_html($file='',$data='',$block=''){
    @extract(ctrl_var());
    @extract($data);
    $block and view_begin($block);
    $file = @$path_html.ctrl_ext($file?$file:$uri_controller,'html');
    if($is=view_is_file($file)) include $file;
    $block and view_end();
return $is;}

function view_heredoc($file='',$data=''){
    @extract(ctrl_var());
    @extract($data);
    $file = @$path_html.ctrl_ext($file?$file:$uri_controller,'html');
    if(view_is_file($file)){
        $content = file_get_contents( $file);
        eval("\$html = <<<ooo\n$content\nooo;\n");}
return @$html;}

function view_xml($file='',$data='',$block=''){
    @extract(ctrl_var());
    @extract($data);
    $block and view_begin($block);
    $file = @$path_xml.ctrl_ext($file?$file:$uri_controller,'xml');
    if($is=view_is_file($file) ) include $file;
    $block and view_end();
return $is;}

function view_mail($file='',$data=array()){
    @extract(ctrl_var());
    @extract($data);
    $file = @$path_mail.ctrl_ext($file?$file:$uri_controller,'html');
    if(view_is_file($file) ) {
        ob_start();
        include $file;
        $mail= ob_get_clean();
        @ob_end_clean();
        if(@$to)   ctrl_mail(@$mail, @$from, @$to, @$subject, @$charset,@$encoding,@$cc,@$bcc);
    }
return $mail;}

function view_is_file($file){
    $is=is_file($file);
    $trace=debug_backtrace();//view_table($trace);
    $row=@$trace[1];
    if(!$is && error_reporting()) print("<br>Function <b>$row[function]</b>(SVC): 
        the file <b>$file</b> is not found 
        in <b>$row[file]</b> on line <b>$row[line]</b>.<br>");
return $is;}

//The link to file viewers
function view_js($file='',$data=array()){
    @extract(ctrl_var());
    if(!is_array($data)) $op[]=$data;
    else foreach($data as $var=>$value) $op[]="$var=".json_encode($value); 
    $file = @$url_site.@$path_js.ctrl_ext(@$file?$file:$uri_controller,'js');
    if($data) print "<script>".join(';',$op)."</script>\r\n";
    print "<script src=\"$file\"></script>";
return;}

function view_css($file=''){
    @extract(ctrl_var());
    $file = @$url_site.@$path_css.ctrl_ext($file?$file:$uri_controller,'css');
    print "<link rel=\"stylesheet\" type=\"text/css\" href=\"$file\"/>";
return;}

function view_img($file,$attr=''){
    @extract(ctrl_var());
    $file = @"$url_site$path_img$file";
    print "<img src=\"$file\" $attr>";
return;}

function view_icon($file){
    $file = ctrl_var('url_site').ctrl_var('path_img').$file;
    print "<link rel=\"icon\" href=\"$file\">";
return;}

//Blocks
function view_begin($block){global $_HTML;
    $_HTML['current']=$block;
    @ob_start();
    @ob_clean();
}

function view_end($block=''){global $_HTML;
    $block!=='' or $block = $_HTML['current'];
    $_HTML['current']='';
    @list($block,$mode)=explode(':',strtolower($block));
    $html = ob_get_clean();
    @ob_end_clean();
    switch ($mode){
        default: @$_HTML[$block].=$html;
        break;case 'before': $_HTML[$block]=@$html.$_HTML[$block];
        break;case 'replace': $_HTML[$block]=$html; 
        break;case 'log': view_log($html,$block); 
    }
return $_HTML[$block];}

function view_paste($block,$str=''){global $_HTML;
    @list($block,$mode)=explode(':',strtolower($block));
    if ($mode) return $_HTML[$block];
    if(!$str) print @$_HTML[$block]; 
    else  return @$_HTML[$block];
}


///// CONTROLLER SESSION //////////////////////////////////////////////////////////
//Load & Run
function ctrl_load($uri_line){    //view_log($uri_line,2);
    $uri_args = is_array($uri_line)?$uri_line:@explode('/',$uri_line);
    $uri_path = $path = ctrl_var('path_ctrl');
    for(;@$uri_args[0] and $uri_args[0]!='..' and is_dir($path.=@$uri_args[0].'/');
        array_shift($uri_args))  $uri_path=$path;
    $file= $uri_path.ctrl_ext($uri_args[0],'php');
    $uri_controller=@array_shift($uri_args);
    $uri_method = @array_shift($uri_args);
    if(is_file($file)) { 
        include_once ($uri_file=$file); 
        $uri_function = $uri_controller.'__'.$uri_method;
        if(!function_exists($uri_function)) $uri_function = $uri_controller.'__index';
        if(!function_exists($uri_function)) $uri_function = '';
    } 
return compact('uri_line','uri_path','uri_file','uri_function','uri_controller','uri_method','uri_args');}

function ctrl_run($uri_line,$data=array()){
    $uri = ctrl_load($uri_line);
    @extract($uri);
    if(@$uri_function) return $uri_function 
        ($data + (array)ctrl_slashes($_REQUEST), @$uri_args[0], @$uri_args[1], @$uri_args[2]);
return false;}

function ctrl_run_uri($default_controller='', $data=array()){
    $uri = ctrl_load(ctrl_uri(),$data); //view($uri);
    @extract($uri);
    if(@!$uri_file) {
        $uri = ctrl_load($default_controller,$data);
        extract($uri);
    }
    ctrl_var($uri); //view($uri);
    if(@$uri_function) $ret = $uri_function 
        ($data + (array)ctrl_slashes($_REQUEST), @$uri_args[0], @$uri_args[1], @$uri_args[2]);
return @$ret;}

///Variables
function ctrl_var($var=array(),$vol=null){
    static $vars; 
    if(!is_array($vars)){ 
        $script_name=@$_SERVER['SCRIPT_NAME'];
        $dirname = dirname($script_name);
        $dirname=($dirname=="\\"||$dirname=="/"?'/':"$dirname/");
        $url_index =(stristr(@ctrl_file('.htaccess'),'RewriteEngine'))? 
            $dirname:"$script_name/";
        $vars= array(
            'url_site'=>$dirname,
            'url_site_full'=>"http://$_SERVER[HTTP_HOST]$dirname",
            'url_index'=>$url_index,
            'url_index_full'=>"http://$_SERVER[HTTP_HOST]$url_index",
            'ctrl_language'=> @strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2)),
            'ctrl_debug'=>1
        );
    }
    is_array($var) and $vars = $var+$vars;
    is_string($var)&&$vol!=null and $vars = array($var=>$vol)+$vars;
return is_array($var)? $vars: @$vars[$var];}

function ctrl_url($type='',$file=''){
    $file = ctrl_ext($file,$type);
    if($type=='index') return  ctrl_var('url_index');
    $url=ctrl_var('url_site').($type?ctrl_var("path_$type"):'').$file;
    $url=preg_replace('{/[^/]+/\.\./}','/',$url);
    $url=preg_replace('{/[^/]+/\.\./}','/',$url);
return $url;}

function ctrl_uri($segment=false) {
    @$path_info = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:@$_SERVER['ORIG_PATH_INFO'];
    $uri=isset($_GET['uri'])?$_GET['uri']:@substr($path_info,1);
    $uris = explode('/',$uri);
return $segment===false?$uri:@$uris[$segment]; }

//Strings
function ctrl_slashes ($A='$$$_REQUEST',$always=false) {
    if($A==='$$$_REQUEST') $A=$_REQUEST;
    if(!$always){
        @set_magic_quotes_runtime(0);
        if (get_magic_quotes_gpc()) return $A;
    }
    if(is_array($A) || is_object($A)){ 
        $out=array();
        foreach ($A as $k=>$val){
            if("$k"=='GLOBALS') continue;
            @$out[$k] = ctrl_slashes ($val,true);
        }
    }else return AddSlashes($A);
return @$out; }

function ctrl_unslash ($A) {
    if(is_array($A) || is_object($A)) {
        $out=array();
        foreach ($A as $k=>$val){
            if("$k"=='GLOBALS') continue;
            $out[$k] = ctrl_unslash ($val);
        }
    }else return StripSlashes($A);
return @$out; }
///
function ctrl_html_chars ($A) {
    if(is_array($A) || is_object($A)){ 
        $out=array();
        foreach ($A as $k=>$val){
            if("$k"=='GLOBALS') continue;
            $out[$k] = ctrl_html_chars ($val);
        }
    }else return HtmlSpecialChars("$A", ENT_QUOTES);
return @$out; }
///
function ctrl_special_chars ($A) {
return ctrl_html_chars ($A);}

function ctrl_labels ($A) {
    global $labes; 
    if(is_array($A) || is_object($A)) {
        $out=array();
        foreach ($A as $k=>$val){
            if("$k"=='GLOBALS') continue;
            $out[$k] = ctrl_labels ($val);
        }   
    }
    else if(isset($labes[$A])) return @$labes[$A];
    else    return ucfirst(str_replace('_',' ',$A));
return @$out; }

function ctrl_strip($A,$size=0){
    if(is_array($A) || is_object($A)) {
        $out=array();
        foreach ($A as $k=>$val){
            if("$k"=='GLOBALS') continue;
            $out[$k] = ctrl_strip ($val,$size);
        }
    }else {
        $str=trim(strip_tags($A));
        $str=str_replace(array("\r\n","\n\r","\r","\n","\t",'     ','   ','  '),' ',$str);
        return ($size&&strlen($str)>$size)?substr($str,0,$size-2).'...':$str; 
    }
return @$out; }

function ctrl_between ($str, $tag1, $tag2, $to=false) {
    list($stag1,$stag2) = str_replace(
        array('\\', "\n", "\r", '$', '"', "'", "[", "(", ")", "|", "$", "*", ">", ']', '?', '.', '/', '-', '+' ),
        array('\\\\','\n','\r','\$','\"',"\'","\[","\(","\)","\|","\$",'\*','\>','\]','\?','\.','\/','\-','\+'),
        array($tag1,$tag2));
    if ($to===false){
        preg_match_all ("|$tag1(.*)$tag2|sU", $str, $tags);
        return (array)$tags[1];
    }elseif($tag1&&$tag2) {
        $new_str = preg_replace ("|$stag1(.*)$stag2|sU","$tag1$to$tag2", $str);
        $str=$new_str?$new_str:$str;}
return $str;}

function ctrl_preg($text,$pattern,$pos=0){
    @preg_match_all("|$pattern|sU",$text,$match);
return (array)@$match[$pos];}

function ctrl_xml_format($xml){
    $doc = new DOMDocument();
    $doc->preserveWhiteSpace = false;
    $doc->formatOutput = true;
    $doc->loadXML($xml);
    $doc->encoding = 'utf-8';
return $doc->saveXML();}

function ctrl_xml_path($xml,$tags){
    foreach (explode('/',$tags) as $tag)  $xml = ctrl_xml_1($xml,$tag);
return "$xml";}

function ctrl_xml_from_arr($A) {
    if(is_array($A) || is_object($A)) 
        foreach ((array)$A as $k=>$v) 
            @"HTTP{$k}_VARS" === $k0 or "HTTP{$k0}_VARS" === ($k0=$k) or 
            $out.= "<$k>". ("$k"=='GLOBALS'? $v: ctrl_xml_from_arr ($v))."</$k>\n";
    else return "".htmlspecialchars((string)$A)."";
    $str = @"\n$out";
return $str;}

function ctrl_xml_parse ($xml, $tag, $tag2='') {
    if (!$tag2) $tag2="/$tag";
    preg_match_all ("|<$tag*>(.*)<$tag2>|sU", $xml, $tags);
return @$tags[1];}

function ctrl_xml_1 ($xml, $tag, $tag2='') {
    if (!$tag2) $tag2="/$tag";
    preg_match_all ("|<$tag.*>(.*)<$tag2>|sU", $xml, $tags);
return @$tags[1][0];}


//Replacement language
function rl($key, $default_language=''){
    static $language,$text,$default;
    if(is_array($key)){
        $language   = ctrl_var('ctrl_language');
        $text       = $key;
        $default    = 'en';
    }
    if($default_language) $default=$default_language;
    if(         $line   = @array_search($key, $text['keys'])){
        if(     $string = @$text[$language][$line]);
        elseif( $string = @$text[$default][$line]);
        else    $string = $key;
    }else       $string = $key;
return  "<span class=rl-$line>$string</span>";}

//Mail
function ctrl_mail($message, $from='', $to='', $subject='', $charset='',$encoding='',$cc='',$bcc=''){
    static $def_from, $def_to, $def_subject, $def_charset,$def_encoding,$def_cc,$daf_bcc;
    if($message=='set'){
        $def_from = $from; $def_to=$to; $def_subject=$subject; 
        $def_charset  = $charset ?$charset :'utf-8';
        $def_encoding = $encoding?$encoding:'8bit';
        $def_cc =$cc; $def_bcc=$bcc;
        $ret='';
    }else{
        if(!@$def_charset) ctrl_mail('set');
        if(!$from)      $from       = $def_from;
        if(!$to)        $to         = $def_to;
        if(!$subject)   $subject    = $def_subject;
        if(!$charset)    $charset    = $def_charset;
        if(!$encoding)  $encoding   = $def_encoding;  
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=$charset\r\n";
        $headers .= "Content-Transfer-Encoding: $encoding\r\n";
        $headers .= "From: $from\r\n";
        $cc  and $headers .= "cc: $cc\r\n";
        $bcc and $headers .= "bcc: $bcc\r\n";
        $headers .= "\r\n";
        $ret = @mail($to,$subject,$message,$headers);
    }
return $ret;}

//Dates
function ctrl_date($start_date='',$format='',$add_days=0){
    if($format=='')$format='Y-m-d';
    if($start_date=='')$start_date=date($format);
return date($format,strtotime($start_date)+$add_days*24*3600);}     

//echo ctrl_days('2011-01-01');
function ctrl_days($start_date,$end_date=''){
    ctrl_set($end_date,date('Y-m-d'));
return (strtotime($end_date) - strtotime($start_date))/24.0/3600;}    

//Files
function ctrl_ext($file_name,$file_ext=false) {
    $ret = substr(strrchr($file_name,'.'),1);
    strlen($ret)<7 or $ret =''; 
    $file_ext===false or $ret = $ret==''&&$file_name!=''? "$file_name.$file_ext":$file_name;
return $ret;}

function ctrl_file($file, $str=false, $mode="w+") {
    if($str!==false){
        $f = @fopen ($file, $mode);
        $ok = @fwrite ($f, $str);
        @fclose ($f);
    }else return @file_get_contents($file);
return $ok;}

function ctrl_file_arr($file, $arr = false){
    if($arr!==false){
        return ctrl_file('config.cfg', http_build_query($arr)); 
    } else {   
        parse_str(ctrl_file('config.cfg'),$arr);
        return $arr; 
    }
}

function ctrl_dir($pattern='', $recursive=false,$files_dir='') { //view($files_dir);
    $cur_dir = getcwd();
    if($files_dir) chdir($files_dir);
    if (substr($pattern,-1)==='/'||substr($pattern,-1)==='\\'||$pattern==='') 
        $pattern .= '*'; 
    $dir=glob($pattern,GLOB_BRACE);
    if(!$recursive) return $dir;
    $path=pathinfo($pattern);
    foreach (glob("$path[dirname]/*",GLOB_ONLYDIR) as $folder) 
        $dir = array_merge($dir, ctrl_dir("$folder/$path[basename]",1));
    chdir($cur_dir);
return $dir;}

function ctrl_rmdir($path) {
    foreach (glob($path.'*',GLOB_ONLYDIR) as $rec) ctrl_rmdir($rec."/");
    foreach (glob($path.'*') as $file)   @unlink ($file);
    foreach (glob($path.'.*') as $file)   @unlink ($file);
    @rmdir($path);
}

///
function ctrl_xcopy($source,$dest) { //view($source,' ',$dest);
    @mkdir($dest,0777);
    if(is_dir($source)){ 
        $file_mask = '';
        $files=glob("$source/*"); 
    }else{
        $file_mask = basename($source);
        $files=array_merge(
            glob(dirname($source).'/'.$file_mask),
            glob(dirname($source).'/*',GLOB_ONLYDIR));
    }//view($files);
    foreach($files as $source_file){ 
        $file = basename($source_file);
        if (is_dir($source_file))   ctrl_xcopy("$source_file/$file_mask","$dest/$file");
        else copy($source_file,"$dest/$file");
    }
}

// Add $post
function ctrl_curl($url,$post=array()){
    if(!function_exists('curl_init')) return 'No CURL';
    $ch  = curl_init( $url);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER    ,true);
    curl_setopt( $ch, CURLOPT_ENCODING          ,"UTF8");            
    curl_setopt( $ch, CURLOPT_POST              ,true);     
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER    ,false);     
    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($post));       
    $contents = curl_exec( $ch ). curl_error($ch);
    curl_close( $ch );
return $contents;}


function ctrl_ini($file,$array='',$sections=false){
    $file=ctrl_ext($file,'ini');
    if(is_array($array)){
        foreach($array as $name=>$value) @$content.="$name=\"$value\"\n";
        ctrl_file($file,$content);
    }
    $array=(array)@parse_ini_file($file,$sections);
return $array;}

///
function ctrl_pack($packer_with_oprions, $archive_name, $files_dir='',$archive_dir='', $files_list ='') {
    $cur_dir = getcwd();
    chdir($files_dir);
    $ret = shell_exec("$packer_with_oprions $archive_dir/$archive_name $files_list");
    chdir($cur_dir);
return $ret;}

/// Если значение не установлено то устанавливает значение переменной $var в $value, или в ноль.
function ctrl_set(&$var,$value=''){
return $var = (isset($var)&&$var!='')?$var:$value;}

/// Запоминает занчение перемпенной $var в сесии с именим $name, 
// и востанавливает его в слючае пустого значения.
//Если значение в сесии не установлено устанавливается занчение $default
function ctrl_session($name,&$var,$default=''){
    $var = @$var?$var:(@$_SESSION[$name]?$_SESSION[$name]:$default);
return $_SESSION[$name]=$var;}
    
//echo ctrl_phone('12345678901');ctrl_phone('1234567890');echo ctrl_phone('123456789');echo ctrl_phone('1234567');echo ctrl_phone('123456');echo ctrl_phone('12345');
function ctrl_phone($phone){
    $phone=preg_replace('/\D/','',$phone);
    $phone = preg_replace("/(\d+)(\d{3}+)(\d{4})$/",'($1)$2-$3',$phone);
    $phone = preg_replace("/(\d+)(\d{4})$/",'$1-$2',$phone);
return $phone;}

// Change location of site
function ctrl_location($location){
    @extract(ctrl_var());
    header (@"location:$url_index$location");
exit;}

