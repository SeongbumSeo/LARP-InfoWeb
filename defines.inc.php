<?php

define("TESTMODE",false);
define("TESTERIP",'58.233.248.12');
define("HTTP_HOST","http://".$_SERVER['HTTP_HOST']);
define("HTTP_SELF","http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

// SA-MP Game Server
define("GAME_HOST","server.la-rp.co.kr");
define("GAME_PORT",7777);

// Database
define("DB_HOST","db.acu.pe.kr");
define("DB_USERNAME","samp_larp");
define("DB_PASSWORD","zIMZMa86QHRapKJA");

// Sign Up
define("SMS_SEND_URL","http://resources.la-rp.co.kr/registration/sms_send.php");
define("AUTH_TIME",5*60);
define("MAX_AUTH_TRY",3);

// Log In
define("MAX_LOGINTRY",3);
define("BAN_TIME",1);

// Admin Account
define("ACCOUNT_ACU",1);
define("ACCOUNT_WLABYR",8);

// Notice
define("MAX_NOTICE",10);
?>