<?php
	include_once("PorterStemmer.php");
	$sqlFile = "init.sql";
	$sqlFile = fopen($sqlFile,'a');
	$query = "drop table if exists invertIndex;";
	fwrite($sqlFile,$query."\n");
	$query = "create table invertIndex(indexID varchar(255) primary key,word varchar(255),docID varchar(255),count int,title varchar(255), author varchar(255),source varchar(255));";
	fwrite($sqlFile,$query."\n");
	fclose($sqlFile);

?>