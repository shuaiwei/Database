<?php session_start();

/* If a user does not select a file to upload,exit! */
if(empty($_FILES['userfile']['tmp_name'])) 
	exit("You should select a file to upload, exit.");

/* Put the uploaded file into the directory /home/shuaiwei/Documents/phpshuaiwei/
	and rename it as temp.tmp";                                             */
include_once("dbconnect.inc.php");
if (is_uploaded_file($_FILES['userfile']['tmp_name']))
{
        move_uploaded_file($_FILES['userfile']['tmp_name'],$FileDestination);
        print "The file " . $_FILES['userfile']['name'] . " has been uploaded successfully.<br>";
}
else print "Possible file upload attack. Filename: " . $_FILES['userfile']['tmp_name'];

/* If a user does not select a delimitertag, exit! */
if(empty($_POST['Tag'])) exit("You should select a delimitertag, exit.");

/* Get the selected tag value */
foreach ($_POST['Tag'] as $value);
if($value == 'comma')
$delimitertag  = ',';
elseif($value == 'tab')
$delimitertag  = '\t';
elseif($value == 'pipe')
$delimitertag  = '|';
elseif($value == 'percent')
$delimitertag  = '%';
else 
$delimitertag  = ';';

echo "The delimitertag $delimitertag has been uploaded successfully.<br>";

/* Get the first line of the FileDestination */
//$firstline=`head -n1 $FileDestination`; php5.5
$f = fopen($FileDestination, 'r');
$firstline = fgets($f);
fclose($f);

/* Put all the column headings into $keywords */
$keywords = preg_split("@$delimitertag@", $firstline);

/* Put the number of column headings into $countsOfAttributes */
$countsOfAttributes = count($keywords);
echo "Counts Of Attributes are: $countsOfAttributes";

/* Transfer theses variables to another .php file */
$_SESSION['delimitertag'] = $delimitertag;
$_SESSION['countsOfAttributes'] = $countsOfAttributes;
$_SESSION['keywords'] = $keywords;

?>

<!-- Create a dropdown form for each column heading-->
<form action="connectToDb.php" method="POST">
    <?php for ($i=0;$i<$countsOfAttributes;$i++) : ?> 
        <br />
        <!-- Select a data type from the dropdown form-->
	Select a data type for attribute <?=$keywords[$i]?>: <br />
        <select multiple name="Tag2[]">
            <option value='int'>int</option>
            <option value='decimal'>decimal</option>
            <option value='char'>char</option>
        </select><br />
    <?php endfor; ?> 
    <input type="submit" value="Update" />
</form>
