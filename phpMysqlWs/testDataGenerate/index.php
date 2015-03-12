<?php

 	/* Generate STUDENT_ID */
 	$rand_num = rand(100000,900000);
 	$numbers = range($rand_num,$rand_num+5000);
	shuffle($numbers);
	$numbers = array_slice($numbers,0,1500);
	foreach ($numbers as $index => $value)
	{
    	$numbers[$index] = (string)$value; 
		$STUDENT_ID_ALL[] = "902"."$numbers[$index]";
	}

	/* Generate unique SSAN */
 	$rand_num = rand(100000,900000);
 	$numbers = range($rand_num,$rand_num+5000);
	shuffle($numbers);
	$numbers = array_slice($numbers,0,1500);
	foreach ($numbers as $index => $value)
	{
    	$numbers[$index] = (string)$value; 
    	$rand_num = rand(100,999);
    	$SSAN_ALL[] = "$rand_num"."$numbers[$index]";
    }

	$Dffield = "NULL";
	$APPLICATION_STATUS = "D";

	$ADMISSIONS_POPULATION_ALL = array("EA","FR");	
	$RESIDENCY_ALL 			   = array("N","R");
	$latest_decision_ALL 	   = array("CC","CD","DA","DD");
	$DECISION_ALL 			   = array("DF","DE","AD");
	$GENDER_ALL				   = array("F","M");
	$FirstGeneration_ALL 	   = array("N","Y");
	$FinancialAid_ALL 		   = array("Y","N","NULL");
	$HOUSING_ALL 			   = array("Y","N","NULL");
	$ETHNICITY_ALL 			   = array("W","W","W","W","B","I","X","O","H");//More white people
	$ACADEMIC_PERIOD_ALL       = array("200908","200901","201109","201101","201309","201302");
	$LEGACY_ALL 			   = array("P","O","NULL");
	$Income_ALL                = array("N","N","N","Y");
 	$COLLEGE_ALL               = array("EN","AR","LA","BU","NU","AG","ED","SM","HS","FW");

	$MAJOR_ALL  = file_get_contents('major.ini',true);
    $MAJOR_ALL  = explode("\n", $MAJOR_ALL);
    $MAJOR_ALL  = array_filter($MAJOR_ALL);
    $MAJOR_ALL  = array_chunk($MAJOR_ALL, 9); //2 dimension array

    $FIRST_NAME_M_ALL  = file_get_contents('firstNameM.ini',true);
    $FIRST_NAME_M_ALL  = explode("\n", $FIRST_NAME_M_ALL);
    $FIRST_NAME_M_ALL = array_filter($FIRST_NAME_M_ALL);

    $FIRST_NAME_F_ALL = file_get_contents('firstNameF.ini',true);
    $FIRST_NAME_F_ALL  = explode("\n", $FIRST_NAME_F_ALL);
    $FIRST_NAME_F_ALL = array_filter($FIRST_NAME_F_ALL);


    $LAST_NAME_ALL = file_get_contents('lastName.ini',true);
    $LAST_NAME_ALL = explode("\n", $LAST_NAME_ALL);
    $LAST_NAME_ALL = array_filter($LAST_NAME_ALL);

	$COUNTY_NAME_ALL = file_get_contents('countyState.ini',true);
    $COUNTY_NAME_ALL = explode("\n", $COUNTY_NAME_ALL);
    $COUNTY_NAME_ALL = array_filter($COUNTY_NAME_ALL);

    $COUNTY_CODE_ALL = file_get_contents('countyCode.ini',true);
    $COUNTY_CODE_ALL = explode("\n", $COUNTY_CODE_ALL);
    $COUNTY_CODE_ALL = array_filter($COUNTY_CODE_ALL);

    $STATE_ALL = file_get_contents('state.ini',true);
    $STATE_ALL = explode("\n", $STATE_ALL);
    $STATE_ALL = array_filter($STATE_ALL);

   	$HS_NAME_ALL = file_get_contents('hsName.ini',true);
    $HS_NAME_ALL = explode("\n", $HS_NAME_ALL);
    $HS_NAME_ALL = array_filter($HS_NAME_ALL);

    $HS_CODE_ALL = file_get_contents('hsCode.ini',true);
    $HS_CODE_ALL = explode("\n", $HS_CODE_ALL);
    $HS_CODE_ALL = array_filter($HS_CODE_ALL);

    $fp = fopen('ApplicationsTestData.sql', 'w');
    $sqlData = "drop table if exists ApplicationsTest;\n";  
    fwrite($fp, $sqlData);
	$sqlData = "CREATE TABLE ApplicationsTest (
	`STUDENT_ID` varchar(200) default NULL,
	`LAST_NAME` varchar(200) default NULL,
	`FIRST_NAME` varchar(200) default NULL,
	`ADMISSIONS_POPULATION` varchar(200) default NULL,
	`APPLICATION_STATUS` varchar(200) default NULL,
	`DECISION` varchar(200) default NULL,
	`latest_decision` varchar(200) default NULL,
	`GPA` decimal(18,2) default NULL,
	`ACT` int(11) default NULL,
	`Ability_bandexpr` varchar(200) default NULL,
	`GENDER` varchar(200) default NULL,
	`LEGACY` varchar(200) default NULL,
	`RESIDENCY` varchar(200) default NULL,
	`COLLEGE` varchar(200) default NULL,
	`ETHNICITY` varchar(200) default NULL,
	`SSAN` varchar(200) default NULL,
	`ACADEMIC_PERIOD` varchar(200) default NULL,
	`MAJOR` varchar(200) default NULL,
	`COUNTY_CODE` varchar(200) default NULL,
	`HS_CODE` varchar(200) default NULL,
	`HS_NAME` varchar(200) default NULL,
	`COUNTY_NAME` varchar(200) default NULL,
	`STATE_ORIGIN` varchar(200) default NULL,
	`High_Income` varchar(200) default NULL,
	`Moderate_Income` varchar(200) default NULL,
	`Low_Income` varchar(200) default NULL,
	`Very_Low_Income` varchar(200) default NULL,
	`FirstGeneration` varchar(200) default NULL,
	`HOUSING` varchar(200) default NULL,
	`FinancialAid` varchar(200) default NULL,
	`Expr1030` varchar(200) default NULL,
	`DFfield` varchar(200) default NULL,
	`STATE` varchar(200) default NULL,
	PRIMARY KEY  (`STUDENT_ID`));\n";
	fwrite($fp, $sqlData);

	for($i=0;$i<1500;$i=$i+1)
	{
    	$index_county 	= array_rand($COUNTY_NAME_ALL, 1);
    	$index_college  = array_rand($COLLEGE_ALL, 1);
    	$index_hs	 	= array_rand($HS_NAME_ALL, 1);

    	$MAJOR_PART	  = $MAJOR_ALL[$index_college];

		$HS_CODE 	  = $HS_CODE_ALL[$index_hs];
	 	$HS_NAME	  = $HS_NAME_ALL[$index_hs];
		$COUNTY_CODE  = $COUNTY_CODE_ALL[$index_county];
		$COUNTY_NAME  = $COUNTY_NAME_ALL[$index_county];
		$STATE_ORIGIN = $STATE_ALL[$index_county];
		$Expr1030     = $STATE_ALL[$index_county];
		$STATE        = $STATE_ALL[$index_county];
		$COLLEGE      = $COLLEGE_ALL[$index_college];
		$STUDENT_ID   = $STUDENT_ID_ALL[$i];
		$SSAN 		  = $SSAN_ALL[$i];

    	shuffle($Income_ALL);

		$High_Income 	 = $Income_ALL[0]; 	 
   		$Moderate_Income = $Income_ALL[1];
    	$Low_Income 	 = $Income_ALL[2];
    	$Very_Low_Income = $Income_ALL[3];

    	$GPA = rand(0,400)/100.0;
		$ACT = rand(1,36);
		$Ability_bandexpr = (int)(8-(int)($ACT/9));

		$STATE_ORIGIN = $STATE;
		$Expr1030 	  = $STATE_ORIGIN;  

		if($GENDER = "M")
			$FIRST_NAME 	   = $FIRST_NAME_M_ALL[array_rand($FIRST_NAME_M_ALL, 1)];
		else $FIRST_NAME 	   = $FIRST_NAME_F_ALL[array_rand($FIRST_NAME_F_ALL, 1)];

    	$ADMISSIONS_POPULATION = $ADMISSIONS_POPULATION_ALL[array_rand($ADMISSIONS_POPULATION_ALL, 1)];
    	$LAST_NAME 			   = $LAST_NAME_ALL[array_rand($LAST_NAME_ALL, 1)];
    	$DECISION 			   = $DECISION_ALL[array_rand($DECISION_ALL, 1)];
    	$latest_decision 	   = $latest_decision_ALL[array_rand($latest_decision_ALL, 1)];	
		$GENDER 			   = $GENDER_ALL[array_rand($GENDER_ALL, 1)];
		$LEGACY 			   = $LEGACY_ALL[array_rand($LEGACY_ALL, 1)];
		$RESIDENCY 			   = $RESIDENCY_ALL[array_rand($RESIDENCY_ALL, 1)];
		$ETHNICITY 			   = $ETHNICITY_ALL[array_rand($ETHNICITY_ALL, 1)];
		$ACADEMIC_PERIOD 	   = $ACADEMIC_PERIOD_ALL[array_rand($ACADEMIC_PERIOD_ALL, 1)];
		$FirstGeneration 	   = $FirstGeneration_ALL[array_rand($FirstGeneration_ALL, 1)];
		$HOUSING 			   = $HOUSING_ALL[array_rand($HOUSING_ALL, 1)];
		$FinancialAid 		   = $FinancialAid_ALL[array_rand($FinancialAid_ALL, 1)];
		$MAJOR 				   = $MAJOR_PART[array_rand($MAJOR_PART, 1)];

		if($LEGACY == "P")
		$FirstGeneration = "N";
		
		$stateOriginRand =rand(0,1500);
		if(($i+$stateOriginRand) > 2500)
			$STATE_ORIGIN = $STATE_ALL[$stateOriginRand];
		
		$sqlData = "INSERT INTO ApplicationsTest VALUES(
			'$STUDENT_ID',
			'$LAST_NAME',	
			'$FIRST_NAME',		
			'$ADMISSIONS_POPULATION',
			'$APPLICATION_STATUS',
			'$DECISION',
			'$latest_decision',		
			'$GPA',
			'$ACT',
			'$Ability_bandexpr',
			'$GENDER',
			'$LEGACY',
			'$RESIDENCY',
			'$COLLEGE',
			'$ETHNICITY',
			'$SSAN',
			'$ACADEMIC_PERIOD',
			'$MAJOR',
			'$COUNTY_CODE',
			'$HS_CODE',
			'$HS_NAME',
			'$COUNTY_NAME',
			'$STATE_ORIGIN',
			'$High_Income',   	 
   			'$Moderate_Income', 
    		'$Low_Income', 
    		'$Very_Low_Income',
			'$FirstGeneration',
			'$HOUSING',
			'$FinancialAid',
			'$Expr1030',
			'$Dffield',
			'$STATE');\n";
			fwrite($fp, $sqlData);
}
	fclose($fp);
?>
