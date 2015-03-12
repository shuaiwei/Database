<form enctype="multipart/form-data" action="tagSelect.php" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    Select a data file: 
    <br />
    <input name="userfile" type="file" />
    <br /><br />
    <!-- Select a delimiter tag from the dropdown form-->
    Select a delimiter tag: <br />
    <select multiple name="Tag[]">
        <option value="comma">,</option>
        <option value="tab">\t</option>
        <option value="pipe">|</option>
 	<option value="percent">%</option>
 	<option value="semicolon">;</option>
    </select><br />
	<br />
	<input type="submit" value="Upload the file and tag" />
</form>


