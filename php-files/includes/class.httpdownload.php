<?php
if (!defined("IN_FUSION")) { header("Location:../index.php"); exit; }
/*----------------------------------------------------------------------+
| Modified for use with PHP-Fusion 6 by CrappoMan (crappoman@email.com) |
+-----------------------------------------------------------------------*/

/*------------------
| Download Handler |
------------------*/

/*
 @author Nguyen Quoc Bao <quocbao.coder@gmail.com>
 visit http://en.vietapi.com/wiki/index.php/PHP:_HttpDownload for class information
 Please send me an email if you find some bug or it doesn't work with download manager.
 I've tested it with
  - Reget : http://reget.com
  - FDM : http://freefiledownloadmanager.org
 @version 1.2
 @desc A simple object for processing download operation , support section downloading
 @distribution It's free as long as you keep this header .
 @sample

 1: File Download
  $object = new httpdownload;
  $object->set_byfile($filename); //Download from a file
  $object->use_resume = true; //Enable Resume Mode
  $object->download(); //Download File

 2: Data Download
  $object = new httpdownload;
  $object->set_bydata($data); //Download from php data
  $object->use_resume = true; //Enable Resume Mode
  $object->set_filename($filename); //Set download name
  $object->set_mime($mime); //File MIME (Default: application/otect-stream)
  $object->download(); //Download File

  3: Manual Download
  $object = new httpdownload;
  $object->set_filename($filename);
  $object->download_ex($size);
  //output your data here , remember to use $this->seek_start and $this->seek_end value :)

*/

class httpdownload {

  /*----------------
  | Class Variable |
  ----------------*/
  /**
   $handler : Object Handler
   $use_resume : use section download
   $use_autoexit : auto stop after finishing download
   $use_auth : use authentication download
   $data : Download Data
   $data_len : Download Data Len
   $data_type : Download Data Type
   $data_mod : Last modified time
   $filename : Download File Name
   $mime : File mime
   $bufsize : BUFFER SIZE
   $seek_start : Start Seek
   $seek_end : End Seek
  **/
  var $handler = array('auth' => false ,'header' => false,'fopen'=>false,'fclose'=>false,'fread'=>false,'fseek' => false);
  var $use_resume = true;
  var $use_autoexit = true;
  var $use_auth = false;
  var $data = null;
  var $data_len = 0;

  var $data_type = 0;

  var $data_mod = 0;
  var $filename = null;
  var $mime = null;
  var $bufsize = 2048;
  var $seek_start = 0;
  var $seek_end = -1;

  /*-------------------
  | Download Function |
  -------------------*/
  /**
   pre_download() : Pre Download Function
   download() : Download all file
   set_byfile() : Set data download by file
   set_bydata() : Set data download by data
   set_byurl() : Set data download by url
   set_filename() : Set file name
   set_mime() : Set file mime
   download_header() : Send header
   download_ex() : Manual Download
  **/
  function pre_download() {
    global $HTTP_SERVER_VARS;
    if ($this->use_auth) { //use authentication
      if (!$this->_auth()) { //no authentication
        $this->_header('WWW-Authenticate: Basic realm="Please enter your username and password"');
        $this->_header('HTTP/1.0 401 Unauthorized');
//        $this->_header('Status: 401 Unauthorized');
        echo "Unauthorized Access: username or password incorrect\n";
        if ($this->use_autoexit) exit();
        return false;
      }
    }
    if ($this->mime == null) $this->mime = "application/octet-stream"; //default mime
    if (isset($_SERVER['HTTP_RANGE']) || isset($HTTP_SERVER_VARS['HTTP_RANGE'])) {
      if(isset($HTTP_SERVER_VARS['HTTP_RANGE'])) {
        $seek_range = substr($HTTP_SERVER_VARS['HTTP_RANGE'] , strlen('bytes='));
      } else {
        $seek_range = substr($_SERVER['HTTP_RANGE'] , strlen('bytes='));
      }
      $range = explode('-',$seek_range);
      if($range[0] > 0) {
        $this->seek_start = intval($range[0]);
      }
      if($range[1] > 0) {
        $this->seek_end = intval($range[1]);
      } else {
        $this->seek_end = -1;
      }
    } else {
      $this->seek_start = 0;
      $this->seek_end = -1;
    }
    if ($this->seek_start < 0 || !$this->use_resume) $this->seek_start = 0;
    return true;
  }

  function download_ex($size) {
    if (!$this->pre_download()) return false;
    ignore_user_abort(true);
    //Use seek end here
    if ($this->seek_start > ($size - 1)) $this->seek_start = 0;
    if ($this->seek_end <= 0) $this->seek_end = $size - 1;
    $this->download_header($size,$seek,$this->seek_end);
    $this->data_mod = time();
    return true;
  }

  function download() {
    if (!$this->pre_download()) return false;
    $seek = $this->seek_start;
    ignore_user_abort(true);
    @set_time_limit(0);
    $size = $this->data_len;
    if ($this->data_type == 0) {
      $size = filesize($this->data);
      if ($seek > ($size - 1)) $seek = 0;
      if ($this->filename == null) $this->filename = basename($this->data);
      $res =& $this->_fopen($this->data,'rb');
      if ($seek) $this->_fseek($res , $seek);
      if ($this->seek_end < $seek) $this->seek_end = $size - 1;
      $this->download_header($size,$seek,$this->seek_end); //always use the last seek
      $size = $this->seek_end - $seek + 1;
      while (!connection_aborted() && $size > 0) {
        if ($size < $this->bufsize) echo $this->_fread($res , $size);
        else echo $this->_fread($res , $this->bufsize);
        $size -= $this->bufsize;
      }
      $this->_fclose($res);
    } else if ($this->data_type == 1) {
      if ($seek > ($size - 1)) $seek = 0;
      if ($this->seek_end < $seek) $this->seek_end = $this->data_len - 1;
      $this->data = substr($this->data , $seek , $this->seek_end - $seek + 1);
      if ($this->filename == null) $this->filename = time();
      $size = strlen($this->data);
      $this->download_header($this->data_len,$seek,$this->seek_end);
      while (!connection_aborted() && $size > 0) {
        echo substr($this->data , 0 , $this->bufsize);
        $this->data = substr($this->data , $this->bufsize);
        $size -= $this->bufsize;
      }
    } else if ($this->data_type == 2) {
      //just send a redirect header
      header('Location: ' . $this->data);
    }
    if ($this->use_autoexit) exit();
    return true;
  }

  function download_header($size,$seek_start=null,$seek_end=null) {
    $this->_header('Content-type: ' . $this->mime);
    $this->_header('Content-Disposition: attachment; filename="'.$this->filename.'"');
    $this->_header('Last-Modified: ' . date('D, d M Y H:i:s \G\M\T' , $this->data_mod));
    if ($seek_start && $this->use_resume) {
      $this->_header("Content-Length: " . ($seek_end - $seek_start + 1));
      $this->_header('Accept-Ranges: bytes');
      $this->_header("HTTP/1.0 206 Partial Content");
      $this->_header("status: 206 Partial Content");
      $this->_header("Content-Range: bytes $seek_start-$seek_end/$size");
    } else {
      $this->_header("Content-Length: $size");
    }
  }

  function set_byfile($dir) {
    if (is_readable($dir) && is_file($dir)) {
      $this->data_len = 0;
      $this->data = $dir;
      $this->data_type = 0;
      $this->data_mod = filemtime($dir);
      return true;
    } else return false;
  }

  function set_bydata($data) {
    if ($data == '') return false;
    $this->data = $data;
    $this->data_len = strlen($data);
    $this->data_type = 1;
    $this->data_mod = time();
    return true;
  }

  function set_byurl($data) {
    $this->data = $data;
    $this->data_len = 0;
    $this->data_type = 2;
    return true;
  }

  function set_filename($filename) {
    $this->filename = $filename;
  }
  function set_mime($mime) {
    $this->mime = $mime;
  }
  function set_lastmodtime($time) {
    $time = intval($time);
    if ($time <= 0) $time = time();
    $this->data_mod = $time;
  }

  /*----------------
  | Other Function |
  ----------------*/
  /**
   header() : Send HTTP Header
  **/
  function _header($var) {
    if ($this->handler['header']) return @call_user_func($this->handler['header'],$var);
    else return header($var);
  }

  function &_fopen($file,$mode) {
    if ($this->handler['fopen']) return @call_user_func($this->handler['fopen'],$file,$mode);
    else return fopen($file,$mode);
  }

  function _fclose($res) {
    if ($this->handler['fclose']) return @call_user_func($this->handler['fclose'],$res);
    else return fclose($res);
  }

  function _fseek($res,$len) {
    if ($this->handler['fseek']) return @call_user_func($this->handler['fseek'],$res,$len);
    else return fseek($res,$len);
  }

  function &_fread($file,$size) {
    if ($this->handler['fread']) return @call_user_func($this->handler['fread'],$file,$size);
    else return fread($file,$size);
  }

  function _auth() {
    if (!isset($_SERVER['PHP_AUTH_USER'])) return false;
    if ($this->handler['auth']) return @call_user_func($this->handler['auth'],$_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);
    else return true; //you must use a handler
  }

}

?>