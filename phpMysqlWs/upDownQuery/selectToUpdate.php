<?php session_start(); 
    if(!empty($_GET['customersTable']))
        $tableName = $_GET['customersTable'];
    elseif(!empty($_GET['suppliersTable']))
        $tableName = $_GET['suppliersTable'];
    elseif(!empty($_GET['ordersTable']))
        $tableName = $_GET['ordersTable'];
    elseif(!empty($_GET['shippersTable']))
        $tableName = $_GET['shippersTable'];
    elseif(!empty($_GET['productsTable']))
        $tableName = $_GET['productsTable'];
    elseif(!empty($_GET['employeesTable']))
        $tableName = $_GET['employeesTable'];
    elseif(!empty($_GET['order_detailsTable']))
        $tableName = $_GET['order_detailsTable'];
    elseif(!empty($_GET['categoriesTable']))
        $tableName = $_GET['categoriesTable'];
    else exit("Should not be here");

    $_SESSION['tableName'] = $tableName;
 ?>
 
<form enctype="multipart/form-data" action="upload.php" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
    Select a <?php echo $tableName ?> data file :<br>
    <br />
    <input name="userfile" type="file" />
    <br /><br />
    <input type="submit" value="Upload the file" />
</form>


<?php
    echo '<br /><a href="nightlyProcess.php">Continue to upload</a><br>';
?>
<?php
    echo '<br /><a href="index.php">Return to the firstpage</a>';
?>