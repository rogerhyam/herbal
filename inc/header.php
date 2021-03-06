<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once('vendor/autoload.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <title>CETAF URI Tester</title>
    <link rel="stylesheet" href="style/main.css" type="text/css" />
    <link rel="stylesheet" href="style/specimen_popup.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="style/cssmenu/styles.css">
    <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
    <script src="style/cssmenu/script.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/specimen_popup.js"></script>
  </head>
  <body>
  <header>
    <div id='cssmenu'>
          <ul>
             <li><a href="/">CETAF URI Tester</a></li>
             <li><a href='/search.php' >Search</a></li>
             <li><a href='/md.php?q=implementers' >Implementers</a></li>
             <li class='active'><a href='/md.php?q=documentation'>Documentation</a></li>
			 <li><a href='/md.php?q=monitor' >Monitor</a></li>
             <li><a href='/md.php?q=contact' >Contact</a></li>
          </ul>
    </div>
  </header>
  <div id="page-content">
  <!-- end inc/header.php -->
