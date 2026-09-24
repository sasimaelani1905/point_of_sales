<?php  
date_default_timezone_set('Asia/Jakarta');
session_start();

$koneksi = mysqli_connect('localhost','root','','point'); 

if (!$koneksi) {
    die('Connect Error: ' . mysqli_connect_errno());
}

function base_url($url = null)

  {
    $base_url = "";
    if ($url != null)
    {
    	return $base_url."/".$url;
    }
    else
    {
    	return $base_url;
    }
  } 
?>