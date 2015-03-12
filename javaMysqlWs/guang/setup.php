<?php
include_once("PorterStemmer.php");
$sql_file = "init.sql";
$fh = fopen($sql_file,'a');
$query = "drop table if exists invert_index;";
fwrite($fh,$query."\n");
$query = "create table invert_index(IndexID varchar(255) primary key,Word varchar(255),DocID varchar(255),Count int,Title varchar(255));";
fwrite($fh,$query."\n");
fclose($fh);

?>