<?php
#############################################################
# Cite CRM	Customer Relations Management							#
# Copyright (C) 2003 - 2005 In-Cite CRM								#
# www.citecrm.com  dev@incitecrm.com									#
# This program is distributed under the terms and 				#
# conditions of the GPL	and is free to use or modify			#
# 																				#
# Installer																	#
# Version 0.0.1	Thu Oct 20 06:47:29 PDT 2005						#
#############################################################

###############################
#		Lock Check					#
###############################
if(check_lock_file() ) {
	echo("<font color=\"red\">Set up has already ran! Some clean up needs to happen before you can run it again!</font>");
	exit;
	/* add code to clean up inlude file and remove any database settings so we can do a clean install */
}
	
###############################
#		Switch 						#
###############################
$mode = $_POST['mode'];
switch ($mode){

############################
#		Install 					#
############################
case "install":
		/* display page header and start graphics */
echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n
<html>\n
<head>\n
	<title>Cite CRM Installer</title>\n
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n
	<link href=\"../css/default.css\" rel=\"stylesheet\" type=\"text/css\">\n

</head>\n
<body>\n
<center>\n
<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n
	<tr>\n
		<td><img src=\"../images/index01.jpg\" alt=\"\" width=\"490\" height=\"114\"></td>\n
	</tr>\n
</table>\n
			
<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n
	<tr>\n
		<td colspan=\"3\" background=\"../images/index03.gif\"><img src=\"../images/index03.gif\" alt=\"\" width=\"100%\" height=\"40\"></td>\n
	</tr><tr>\n
		<td align=\"center\">\n

			<table width=\"100%\" border=\"0\" cellpadding=\"20\" cellspacing=\"0\">\n
				<tr>\n
					<td class=\"olotd\" align=\"center\">\n
						
						<!-- Begin Page -->\n
						<table width=\"800\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >\n
							<tr>\n
								<td class=\"menuhead2\" width=\"80%\">&nbsp;Cite CRM Installer</td>\n
							</tr><tr>\n
								<td class=\"menutd2\" colspan=\"2\">\n

									<table width=\"100%\"  class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >\n
										<tr>
											<td>
												<table width=\"100%\"  class=\"menutd\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >\n
									");	
		
							$login    = strtolower($_POST["first_name"]{0}).strtolower($_POST["last_name"]);

							/* write the need configs */
							set_path($_POST);

		
		/* Load our new configs */
		
		require("../include/ADODB/adodb.inc.php");
		
		/* Create ADODB Connection */
		$db = &ADONewConnection('mysql');

		$db->Connect($_POST['db_host'] ,$_POST['db_user'], $_POST['db_password']);
		if( $db->errorMsg() != '' ) {
			echo "There Was an error conecting to the database: ".$db->errorMsg();
			die;
		}
		

###################################
# Create Database (Skip if exists) #
##################################
    // Try to select the database to check if it exists
    if (!$db->SelectDB($_POST['db_name'])) {
        // Database doesn't exist, create it
        $q = "CREATE DATABASE ".$_POST['db_name'];
        if(!$rs = $db->Execute($q)) {
            echo("<tr>\n
                    <td>Create Database ". $_POST['db_name'] ." </td>\n
                    <td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg(). " </td>\n
                </tr>\n");
            die;
        } else {
            echo("<tr>\n
                        <td>Create Database ".$_POST['db_name']."</td>\n
                        <td><font color=\"green\"><b>CREATED</b></font></td>\n
                    </tr>\n");
        }
    } else {
        echo("<tr>\n
                    <td>Create Database ".$_POST['db_name']."</td>\n
                    <td><font color=\"blue\"><b>SKIPPED - Already exists</b></font></td>\n
                </tr>\n");
    }
    
##################################
# Verify CRM User Can Connect     #
##################################
    // First close the root connection
    $db->close();
    
    // Try to connect with the CRM user credentials
    $crm_db = &ADONewConnection('mysql');
    if($crm_db->Connect($_POST['db_host'], $_POST['crm_db_user'], $_POST['crm_db_password'], $_POST['db_name'])) {
        echo("<tr>\n
                <td>Verify CRM Database User</td>\n
                <td><font color=\"green\"><b>OK - User can connect</b></font></td>\n
            </tr>\n");
        $crm_db->close();
    } else {
        echo("<tr>\n
                <td>Verify CRM Database User</td>\n
                <td><font color=\"orange\"><b>WARNING:</b></font> User '".$_POST['crm_db_user']."' cannot connect. Tables will be created with root user.</td>\n
            </tr>\n");
    }
    
    // Reconnect with root for table creation
    $db = &ADONewConnection('mysql');
    $db->Connect($_POST['db_host'], $_POST['db_user'], $_POST['db_password']);
    $db->SelectDB($_POST['db_name']);
    
##################################
# Continue with table creation... #
##################################


@define('PRFX', $prefix);
##################################
# Build Tables							#
##################################
		/*include sql.php */
		include("sql.php");
		
##################################
# Add Admin								#
##################################
		$q = "INSERT INTO ".PRFX."TABLE_EMPLOYEE SET
			EMPLOYEE_LOGIN				=". $db->qstr( $login          ).", 
			EMPLOYEE_FIRST_NAME			=". $db->qstr( $_POST['first_name']            ).",
			EMPLOYEE_LAST_NAME 		=". $db->qstr( $_POST['last_name']             ).",
			EMPLOYEE_DISPLAY_NAME 		=". $db->qstr( $_POST['display_name']          ).",
			EMPLOYEE_ADDRESS 			=". $db->qstr( $_POST['address']               ).",
			EMPLOYEE_CITY 				=". $db->qstr( $_POST['city']                  ).",
			EMPLOYEE_STATE 				=". $db->qstr( $_POST['state']                 ).",
			EMPLOYEE_ZIP 				=". $db->qstr( $_POST['zip']                   ).",
			EMPLOYEE_TYPE 				=". $db->qstr( 4                              ).",
			EMPLOYEE_WORK_PHONE		=". $db->qstr( $_POST['work_phone']            ).",
			EMPLOYEE_HOME_PHONE		=". $db->qstr( $_POST['home_phone']            ).",
			EMPLOYEE_MOBILE_PHONE		=". $db->qstr( $_POST['mobile_phone']          ).",
			EMPLOYEE_STATUS  			=". $db->qstr( 1                               ).",
			EMPLOYEE_PASSWD				=". $db->qstr( md5($_POST['default_password']) ).",
			EMPLOYEE_EMAIL				=". $db->qstr( $_POST['default_email']         );
			
		if(!$rs = $db->Execute($q) ) {
			echo("<tr>\n
						<td>Create Default Admin</td>\n
						<td><font color=\"red\"><b>Failed: </b>".$db->ErrorMsg()."</td>\n
					</tr>\n");
		} else {
			echo("<tr>\n
						<td>Create Default Admin</td>\n
						<td><font color=\"green\"><b>OK</b></font></td>\n
				</tr>\n");
		}

##################################
# Add Company Infomation				#
##################################
		$q = " INSERT INTO ".PRFX."TABLE_COMPANY SET
				COMPANY_NAME			=". $db->qstr( $_POST['COMPANY_NAME']      ).",
				COMPANY_ADDRESS		=". $db->qstr( $_POST['COMPANY_ADDRESS']   ).", 
				COMPANY_CITY			=". $db->qstr( $_POST['COMPANY_CITY']      ).", 
				COMPANY_STATE		=". $db->qstr( $_POST['COMPANY_STATE']     ).",
				COMPANY_ZIP 			=". $db->qstr( $_POST['COMPANY_ZIP']       ).",
				COMPANY_COUNTRY 	=". $db->qstr( $_POST['COMPANY_COUNTRY']		).",
				COMPNAY_PHONE		=". $db->qstr( $_POST['COMPANY_PHONE']     ).",
				COMPNAY_MOBILE		=". $db->qstr( $_POST['COMPANY_MOBILE']    ).",
				COMPANY_EMAIL		=". $db->qstr( $_POST['COMPANY_EMAIL']		).",
				COMPANY_TOLL_FREE	=". $db->qstr( $_POST['COMPANY_TOLL_FREE'] );

		if(!$rs = $db->Execute($q)) {
			echo("<tr>\n
					<td>Adding Company Information</td>\n
					<td><font color=\"red\"><b>Failed</b></font> ".$db->ErrorMsg()."</td>\n
				</tr>\n");
		} else {
			echo("<tr>\n
						<td>Adding Company Information</td>\n
						<td><font color=\"green\"><b>OK</b></font></td>\n
					<tr>\n");
		}

##################################
# Completed								#
##################################
if($error_flag == true) {
	/* error can not complete the install */
	echo("<tr>\n
				<td colspan=\"2\">There where errors durring the install. Your CRM is not enabled and needs to be reinstalled. Please remove the Database
				and reinstall. If the errors continue please submit a bug report at.</td>\n
			</tr>\n");
} else {
		/* create lock file */
		if(!touch("../lock")) {
			echo("<tr><td colspan=\"2\"><font color=\"red\">Falied to create lock file. Please report this bug!!</font></td></tr>");
		}

		/* done */
		echo("<tr>\n<td colspan=\"2\">Instalation was sucsesfull.
				<br><br>
				There are still a few steps that need to be completed.<br>
				1. You need to move or rename the install directory. We recomend moving it to a location that is not accessible by your web server
					this way if you need to reinstall the CRM you can move the directory back. You will not beable to login untill this directory is removed<br>
				2. You need to login as the admin and finish setting up the CRM by editing the settings in the Control Center.
				<br><br>
				The Admin login is: ".$login ." and the password you supplied in the previous page.<br><br>
				Where to find help:<br>
				The user Documention is at <a href=\"www.incitecrm.com/doc/\">www.incitecrm.com/doc</a><br>
				A Support Forum is located at <a href=\"www.incitecrm.com/support/\">www.incitecrm.com/support</a><br>
				Bug Reporting is at <a href=\"www.citecrm.com/bugs\">www.citecrm.com/bugs</a><br>

				</td>\n</tr>\n");
}

									echo("
									</table>\n
								</td>\n
							</tr>\n
						</table>\n

					</td>\n
				</tr>\n
			</table>\n
		</td>\n
	</tr>\n
</table>\n
			<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n
				<tr>
					<td height=\"51\" align=\"center\" background=\"../images/index41.gif\"></td>\n
				</tr><tr>\n
					<td height=\"48\" align=\"center\" background=\"../images/index42.gif\"><span class=\"text3\">Copyright 2005 &copy; In-Cite CRM <a href=\"http://www.incitecrm.com\" target=\"new\">www.incitecrm.com</a>
								All rights reserved.</span></td>\n
				</tr><tr>\n
					<td>&nbsp;</td>\n
				</tr>\n
			</table>\n
		</td>\n
	</tr>\n
</table>\n
</center>\n

</body>\n
</html>\n");
	break;

################################
#		Default						#
###############################
default: 
$default_path = resolveDocumentRoot();
$default_server = get_server_name();

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">
<html>
<head>
	<title>Cite CRM Installer</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"../css/default.css\" rel=\"stylesheet\" type=\"text/css\">");
include('validate.js');
echo ("
</head>
<body>
<p>&nbsp;</p>
<center>
<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
		<td><img src=\"../images/index01.jpg\" alt=\"\" width=\"490\" height=\"114\"></td>
	</tr>
</table>
			
<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
	<tr>
		<td colspan=\"3\" background=\"../images/index03.gif\"><img src=\"../images/index03.gif\" alt=\"\" width=\"100%\" height=\"40\"></td>
	</tr><tr>
		<td align=\"center\">
		<br><br>

<table width=\"100%\" border=\"0\" cellpadding=\"20\" cellspacing=\"0\">
	<tr>
		<td class=\"olotd\" align=\"center\">
			
			<!-- Begin Page -->
			<table width=\"800\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
				<tr>
					<td class=\"menuhead2\" width=\"80%\">&nbsp;Cite CRM Installer</td>
					</td>
				</tr><tr>
					<td class=\"menutd2\" colspan=\"2\">

						<table width=\"100%\" class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
							<tr>
								<td width=\"100%\" valign=\"top\" >

									<form action=\"index.php\" method=\"POST\" name=\"install\" id=\"install\" onsubmit=\"try   { var myValidator = validate_install; } catch(e) { return true; } return myValidator(this);\">
									<input type=\"hidden\" name=\"mode\" value=\"install\">

										<table width=\"100%\" class=\"menutd\" cellspacing=\"0\"  border=\"0\" cellpadding=\"5\">
											<tr>
												<td>
													<table >
														<tr>
															<td>
															<b>Initial File Checks</b><br>

															<table class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
																<tr>
																	<td colspan=\"2\" >
																		You need to set the config file conf.php to be writable by your webserver for the install after that you must make them read only by your webserver.
																		The file log/access.log needs to be writable by the web server. The cache directory needs to be writable by the server.
																	</td>
																</tr><tr>
																	<td clospan=\"2\">&nbsp;</td>
																</tr><tr>
																
																	<td width=\"140\">Main Config Writable </td>
																	<td>");
																		if(!check_write ('../conf.php')) {
																			echo("<font color=\"red\">../conf.php is not wrtiable stoping</font>");
																			$errors[] = array('../conf.php'=>'Not Writable');
																		} else {
																			echo("<font color=\"green\"><b>OK</b>");
																		}
																	echo("</td>
																	
																</tr><tr>
																
																	<td width=\"140\">Template Chache</td>
																	<td>");
																		if(!check_write ('../cache')) {
																			echo("<font color=\"red\">../cache is not writable stoping.</font>");
																			$errors[] = array('../cache'=>'Not Writable');
																		} else {
																			echo("<font color=\"green\"><b>OK</b>");
																		}
																	echo( "</td>
																
																</tr><tr>
																
																	<td width=\"140\">Access Log</td>
																	<td>");
																		if(!check_write ('../log/access.log')) {
																			echo("<font color=\"red\">../log/access.log is not writable stoping.</font>");
																			$errors[] = array('../log/access.log'=>'Not Writable');
																		} else {
																			echo("<font color=\"green\"><b>OK</b>");
																		}
																	echo("<td>
																	
																</tr><tr>
															<!-- End of File Checks -->
																	<td colspan=\"2\">&nbsp;</td>
																</tr><tr>
																	<td colspan=\"2\"></td>
																		
																</tr>
															</table>");
														if(is_array($errors)) {
																	echo("Set up can not continue until the following errors are fixed:<br>");
																		foreach($errors as $key=>$val) {
																			echo("<font color=\"red\">Error $key: ");
																			foreach($val as $k=>$v) {
																				echo("$k $v");
																				}
																			echo("</font><br>");
																		}	
														} else {
															echo ("
															<br>
															<b>Database Information:</b>
															<table  class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
																<tr>
																	<td valign=\"top\" width=\"60%\" align=\"left\">
																		<table >
																			<tr>
																				<td width=\"140\">Root Database User:</td>
																				<td ><input type=\"text\" size=\"20\" name=\"db_user\" value=\"root\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Root Database Password:</td>
																				<td><input type=\"password\" size=\"20\" name=\"db_password\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Root Database Host:</td>
																				<td><input type=\"text\" size=\"20\" name=\"db_host\" value=\"localhost\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Database Name:</td>
																				<td>
																					<input type=\"text\" size=\"30\" name=\"db_name\" value=\"citecrm\" class=\"olotd5\">
																				</td>
																			</tr><tr>
																					<td width=\"140\">Table Prefix</td>
																					<td>
																						<input type=\"text\" size=\"30\" name=\"db_prefix\" value=\"CRM_\" class=\"olotd5\">
																					</td>
																				</tr><tr>
																					<td width=\"140\">CRM Database User:</td>
																					<td><input type=\"text\" size=\"20\" name=\"crm_db_user\"  class=\"olotd5\"></td>
																				</tr><tr>
																					<td width=\"140\">CRM Database Password:</td>
																					<td><input type=\"password\" size=\"20\" name=\"crm_db_password\" class=\"olotd5\"></td>
																		</table>
																	</td>
																	<td valign=\"top\">

																		<table width=\"100%\"  cellpadding=\"5\" cellspacing=\"0\" border=\"0\">
																			<tr>
																				<td colspan=\"2\">Set the root user name and password for your MySQL Database Server. You can change the database name and table prefix to suit your needs.
																				The pre set examples will work fine for most installs. We only need the root user to create the database and tables.<br><br>
																				Next you need to add a user and password for the database to run as. We do not sugjest using the root Mysql User for this.
																				</td>
																			</tr>
																		</table>

																	</td>
																</tr>
															</table>
															<br>

															<!-- Default User -->
															<b>Administrator</b>
															<table class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
																<tr>
																	<td valign=\"top\" width=\"60%\">

																		<table>
																			<tr>
																				<td width=\"140\">Password:</td>
																				<td><input type=\"password\" size=\"20\" name=\"default_password\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Password:</td>
																				<td><input type=\"password\" size=\"20\" name=\"default_password2\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">First Name:</td>
																				<td><input type=\"text\" size=\"20\" name=\"first_name\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Last Name:</td>
																				<td><input type=\"text\" size=\"20\" name=\"last_name\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Display Name:</td>
																				<td><input type=\"text\" size=\"20\" name=\"display_name\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Address:</td>
																				<td><input type=\"text\" size=\"20\" name=\"address\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">City:</td>
																				<td><input type=\"text\" size=\"20\" name=\"city\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">State:</td>
																				<td><input type=\"text\" size=\"20\" name=\"state\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Zip:</td>
																				<td><input type=\"text\" size=\"20\" name=\"zip\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Home Phone:</td>
																				<td><input type=\"text\" size=\"20\" name=\"home_phone\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Work Phone:</td>
																				<td><input type=\"text\" size=\"20\" name=\"work_phone\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Mobile Phone:</td>
																				<td><input type=\"text\" size=\"20\" name=\"mobile_phone\" class=\"olotd5\"></td>
																			</tr>
																				<td width=\"140\">Email:</td>
																				<td><input type=\"text\" size=\"20\" name=\"default_email\" class=\"olotd5\"></td>
																			</tr>
																		</table>
																	</td>
																	<td valign=\"top\">
																		<table width=\"100%\"  cellpadding=\"5\" cellspacing=\"0\" border=\"0\">
																			<tr>
																				<td>
																						Add the defualt Administrator. This user will have full permisions to the program and database. This user will also be the Manager of all workorders and employees. IE Full Permisions.
																						The login will be created bu using the first initial of the first name and the full last name. 
																				</td>	
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
															<br>


															<b>Company Information</b>
															<table class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
																<tr>
																	<td valign=\"top\" width=\"60%\">
																		<table>
																			<tr>
																				<td width=\"140\">Company Name:</td>
																				<td><input type=\"text\" size=\"20\" name=\"COMPANY_NAME\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Company Address:</td>
																				<td><input type=\"text\" size=\"20\" name=\"COMPANY_ADDRESS\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Company City:</td>
																				<td><input type=\"text\" size=\"20\" name=\"COMPANY_CITY\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Company State:</td>
																				<td><input type=\"text\" size=\"20\" name=\"COMPANY_STATE\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Company Zip:</td>
																				<td><input type=\"text\" size=\"20\" name=\"COMPANY_ZIP\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Company Country:</td>
																				<td><select name=\"COMPANY_COUNTRY\" class=\"olotd5\">
													  														<option value=\"AF\"  >Afghanistan</option>
													  														<option value=\"AL\"  >Albania</option>
													  														<option value=\"DZ\"  >Algeria</option>
													  														<option value=\"AS\"  >American Samoa</option>
													  														<option value=\"AD\"  >Andorra</option>
													  														<option value=\"AO\"  >Angola</option>
													  														<option value=\"AI\"  >Anguilla</option>
													  														<option value=\"AQ\"  >Antarctica</option>
													  														<option value=\"AG\"  >Antigua and Barbuda</option>
													  														<option value=\"AR\"  >Argentina</option>
													  														<option value=\"AM\"  >Armenia</option>
													  														<option value=\"AW\"  >Aruba</option>
													  														<option value=\"AU\"  >Australia</option>
													  														<option value=\"AT\"  >Austria</option>
													  														<option value=\"AZ\"  >Azerbaijan</option>
													  														<option value=\"AP\"  >Azores</option>
													  														<option value=\"BS\"  >Bahamas</option>
													  														<option value=\"BH\"  >Bahrain</option>
													  														<option value=\"BD\"  >Bangladesh</option>
													  														<option value=\"BB\"  >Barbados</option>
													  														<option value=\"BY\"  >Belarus</option>
													  														<option value=\"BE\"  >Belgium</option>
													  														<option value=\"BZ\"  >Belize</option>
													  														<option value=\"BJ\"  >Benin</option>
													  														<option value=\"BM\"  >Bermuda</option>
													  														<option value=\"BT\"  >Bhutan</option>
													  														<option value=\"BO\"  >Bolivia</option>
													  														<option value=\"BA\"  >Bosnia And Herzegowina</option>
													  														<option value=\"XB\"  >Bosnia-Herzegovina</option>
													  														<option value=\"BW\"  >Botswana</option>
													  														<option value=\"BV\"  >Bouvet Island</option>
													  														<option value=\"BR\"  >Brazil</option>
													  														<option value=\"IO\"  >British Indian Ocean Territory</option>
													  														<option value=\"VG\"  >British Virgin Islands</option>
													  														<option value=\"BN\"  >Brunei Darussalam</option>
													  														<option value=\"BG\"  >Bulgaria</option>
													  														<option value=\"BF\"  >Burkina Faso</option>
													  														<option value=\"BI\"  >Burundi</option>
													  														<option value=\"KH\"  >Cambodia</option>
													  														<option value=\"CM\"  >Cameroon</option>
													  														<option value=\"CA\"  >Canada</option>
													  														<option value=\"CV\"  >Cape Verde</option>
													  														<option value=\"KY\"  >Cayman Islands</option>
													  														<option value=\"CF\"  >Central African Republic</option>
													  														<option value=\"TD\"  >Chad</option>
													  														<option value=\"CL\"  >Chile</option>
													  														<option value=\"CN\"  >China</option>
													  														<option value=\"CX\"  >Christmas Island</option>
													  														<option value=\"CC\"  >Cocos (Keeling) Islands</option>
													  														<option value=\"CO\"  >Colombia</option>
													  														<option value=\"KM\"  >Comoros</option>
													  														<option value=\"CG\"  >Congo</option>
													  														<option value=\"CD\"  >Congo, The Democratic Republic O</option>
													  														<option value=\"CK\"  >Cook Islands</option>
													  														<option value=\"XE\"  >Corsica</option>
													  														<option value=\"CR\"  >Costa Rica</option>
													  														<option value=\"CI\"  >Cote d` Ivoire (Ivory Coast)</option>
													  														<option value=\"HR\"  >Croatia</option>
													  														<option value=\"CU\"  >Cuba</option>
													  														<option value=\"CY\"  >Cyprus</option>
													  														<option value=\"CZ\"  >Czech Republic</option>
													  														<option value=\"DK\"  >Denmark</option>
													  														<option value=\"DJ\"  >Djibouti</option>
													  														<option value=\"DM\"  >Dominica</option>
													  														<option value=\"DO\"  >Dominican Republic</option>
													  														<option value=\"TP\"  >East Timor</option>
													  														<option value=\"EC\"  >Ecuador</option>
													  														<option value=\"EG\"  >Egypt</option>
													  														<option value=\"SV\"  >El Salvador</option>
													  														<option value=\"GQ\"  >Equatorial Guinea</option>
													  														<option value=\"ER\"  >Eritrea</option>
													  														<option value=\"EE\"  >Estonia</option>
													  														<option value=\"ET\"  >Ethiopia</option>
													  														<option value=\"FK\"  >Falkland Islands (Malvinas)</option>
													  														<option value=\"FO\"  >Faroe Islands</option>
													  														<option value=\"FJ\"  >Fiji</option>
													  														<option value=\"FI\"  >Finland</option>
													  														<option value=\"FR\"  >France (Includes Monaco)</option>
													  														<option value=\"FX\"  >France, Metropolitan</option>
													  														<option value=\"GF\"  >French Guiana</option>
													  														<option value=\"PF\"  >French Polynesia</option>
													  														<option value=\"TA\"  >French Polynesia (Tahiti)</option>
													  														<option value=\"TF\"  >French Southern Territories</option>
													  														<option value=\"GA\"  >Gabon</option>
													  														<option value=\"GM\"  >Gambia</option>
													  														<option value=\"GE\"  >Georgia</option>
													  														<option value=\"DE\"  >Germany</option>
													  														<option value=\"GH\"  >Ghana</option>
													  														<option value=\"GI\"  >Gibraltar</option>
													  														<option value=\"GR\"  >Greece</option>
													  														<option value=\"GL\"  >Greenland</option>
													  														<option value=\"GD\"  >Grenada</option>
													  														<option value=\"GP\"  >Guadeloupe</option>
													  														<option value=\"GU\"  >Guam</option>
													  														<option value=\"GT\"  >Guatemala</option>
													  														<option value=\"GN\"  >Guinea</option>
													  														<option value=\"GW\"  >Guinea-Bissau</option>
													  														<option value=\"GY\"  >Guyana</option>
													  														<option value=\"HT\"  >Haiti</option>
													  														<option value=\"HM\"  >Heard And Mc Donald Islands</option>
													  														<option value=\"VA\"  >Holy See (Vatican City State)</option>
													  														<option value=\"HN\"  >Honduras</option>
													  														<option value=\"HK\"  >Hong Kong</option>
													  														<option value=\"HU\"  >Hungary</option>
													  														<option value=\"IS\"  >Iceland</option>
													  														<option value=\"IN\"  >India</option>
													  														<option value=\"ID\"  >Indonesia</option>
													  														<option value=\"IR\"  >Iran</option>
													  														<option value=\"IQ\"  >Iraq</option>
													  														<option value=\"IE\"  >Ireland</option>
													  														<option value=\"EI\"  >Ireland (Eire)</option>
													  														<option value=\"IL\"  >Israel</option>
													  														<option value=\"IT\"  >Italy</option>
													  														<option value=\"JM\"  >Jamaica</option>
													  														<option value=\"JP\"  >Japan</option>
													  														<option value=\"JO\"  >Jordan</option>
													  														<option value=\"KZ\"  >Kazakhstan</option>
													  														<option value=\"KE\"  >Kenya</option>
													  														<option value=\"KI\"  >Kiribati</option>
													  														<option value=\"KP\"  >Korea, Democratic People'S Repub</option>
													  														<option value=\"KW\"  >Kuwait</option>
													  														<option value=\"KG\"  >Kyrgyzstan</option>
													  														<option value=\"LA\"  >Laos</option>
													  														<option value=\"LV\"  >Latvia</option>
													  														<option value=\"LB\"  >Lebanon</option>
													  														<option value=\"LS\"  >Lesotho</option>
													  														<option value=\"LR\"  >Liberia</option>
													  														<option value=\"LY\"  >Libya</option>
													  														<option value=\"LI\"  >Liechtenstein</option>
													  														<option value=\"LT\"  >Lithuania</option>
													  														<option value=\"LU\"  >Luxembourg</option>
													  														<option value=\"MO\"  >Macao</option>
													  														<option value=\"MK\"  >Macedonia</option>
													  														<option value=\"MG\"  >Madagascar</option>
													  														<option value=\"ME\"  >Madeira Islands</option>
													  														<option value=\"MW\"  >Malawi</option>
													  														<option value=\"MY\"  >Malaysia</option>
													  														<option value=\"MV\"  >Maldives</option>
													  														<option value=\"ML\"  >Mali</option>
													  														<option value=\"MT\"  >Malta</option>
													  														<option value=\"MH\"  >Marshall Islands</option>
													  														<option value=\"MQ\"  >Martinique</option>
													  														<option value=\"MR\"  >Mauritania</option>
													  														<option value=\"MU\"  >Mauritius</option>
													  														<option value=\"YT\"  >Mayotte</option>
													  														<option value=\"MX\"  >Mexico</option>
													  														<option value=\"FM\"  >Micronesia, Federated States Of</option>
													  														<option value=\"MD\"  >Moldova, Republic Of</option>
													  														<option value=\"MC\"  >Monaco</option>
													  														<option value=\"MN\"  >Mongolia</option>
													  														<option value=\"MS\"  >Montserrat</option>
													  														<option value=\"MZ\"  >Mozambique</option>
													  														<option value=\"MM\"  >Myanmar (Burma)</option>
													  														<option value=\"NA\"  >Namibia</option>
													  														<option value=\"NR\"  >Nauru</option>
													  														<option value=\"NP\"  >Nepal</option>
													  														<option value=\"NL\"  >Netherlands</option>
													  														<option value=\"AN\"  >Netherlands Antilles</option>
													  														<option value=\"NC\"  >New Caledonia</option>
													  														<option value=\"NZ\"  >New Zealand</option>
													  														<option value=\"NI\"  >Nicaragua</option>
													  														<option value=\"NE\"  >Niger</option>
													  														<option value=\"NG\"  >Nigeria</option>
													  														<option value=\"NU\"  >Niue</option>
													  														<option value=\"NF\"  >Norfolk Island</option>
													  														<option value=\"MP\"  >Northern Mariana Islands</option>
													  														<option value=\"NO\"  >Norway</option>
													  														<option value=\"OM\"  >Oman</option>
													  														<option value=\"PK\"  >Pakistan</option>
													  														<option value=\"PW\"  >Palau</option>
													  														<option value=\"PS\"  >Palestinian Territory, Occupied</option>
													  														<option value=\"PA\"  >Panama</option>
													  														<option value=\"PG\"  >Papua New Guinea</option>
													  														<option value=\"PY\"  >Paraguay</option>
													  														<option value=\"PE\"  >Peru</option>
													  														<option value=\"PH\"  >Philippines</option>
													  														<option value=\"PN\"  >Pitcairn</option>
													  														<option value=\"PL\"  >Poland</option>
													  														<option value=\"PT\"  >Portugal</option>
													  														<option value=\"PR\"  >Puerto Rico</option>
													  														<option value=\"QA\"  >Qatar</option>
													  														<option value=\"RE\"  >Reunion</option>
													  														<option value=\"RO\"  >Romania</option>
													  														<option value=\"RU\"  >Russian Federation</option>
													  														<option value=\"RW\"  >Rwanda</option>
													  														<option value=\"KN\"  >Saint Kitts And Nevis</option>
													  														<option value=\"SM\"  >San Marino</option>
													  														<option value=\"ST\"  >Sao Tome and Principe</option>
													  														<option value=\"SA\"  >Saudi Arabia</option>
													  														<option value=\"SN\"  >Senegal</option>
													  														<option value=\"XS\"  >Serbia-Montenegro</option>
													  														<option value=\"SC\"  >Seychelles</option>
													  														<option value=\"SL\"  >Sierra Leone</option>
													  														<option value=\"SG\"  >Singapore</option>
													  														<option value=\"SK\"  >Slovak Republic</option>
													  														<option value=\"SI\"  >Slovenia</option>
													  														<option value=\"SB\"  >Solomon Islands</option>
													  														<option value=\"SO\"  >Somalia</option>
													  														<option value=\"ZA\"  >South Africa</option>
													  														<option value=\"GS\"  >South Georgia And The South Sand</option>
													  														<option value=\"KR\"  >South Korea</option>
													  														<option value=\"ES\"  >Spain</option>
													  														<option value=\"LK\"  >Sri Lanka</option>
													  														<option value=\"NV\"  >St. Christopher and Nevis</option>
													  														<option value=\"SH\"  >St. Helena</option>
													  														<option value=\"LC\"  >St. Lucia</option>
													  														<option value=\"PM\"  >St. Pierre and Miquelon</option>
													  														<option value=\"VC\"  >St. Vincent and the Grenadines</option>
													  														<option value=\"SD\"  >Sudan</option>
													  														<option value=\"SR\"  >Suriname</option>
													  														<option value=\"SJ\"  >Svalbard And Jan Mayen Islands</option>
													  														<option value=\"SZ\"  >Swaziland</option>
													  														<option value=\"SE\"  >Sweden</option>
													  														<option value=\"CH\"  >Switzerland</option>
													  														<option value=\"SY\"  >Syrian Arab Republic</option>
													  														<option value=\"TW\"  >Taiwan</option>
													  														<option value=\"TJ\"  >Tajikistan</option>
													  														<option value=\"TZ\"  >Tanzania</option>
													  														<option value=\"TH\"  >Thailand</option>
													  														<option value=\"TG\"  >Togo</option>
													  														<option value=\"TK\"  >Tokelau</option>
													  														<option value=\"TO\"  >Tonga</option>
													  														<option value=\"TT\"  >Trinidad and Tobago</option>
													  														<option value=\"XU\"  >Tristan da Cunha</option>
													  														<option value=\"TN\"  >Tunisia</option>
													  														<option value=\"TR\"  >Turkey</option>
													  														<option value=\"TM\"  >Turkmenistan</option>
													  														<option value=\"TC\"  >Turks and Caicos Islands</option>
													  														<option value=\"TV\"  >Tuvalu</option>
													  														<option value=\"UG\"  >Uganda</option>
													  														<option value=\"UA\"  >Ukraine</option>
													  														<option value=\"AE\"  >United Arab Emirates</option>
													  														<option value=\"UK\"  >United Kingdom</option>
													  														<option value=\"GB\"  >Great Britain</option>
													  														<option value=\"US\"  selected  >United States</option>
													  														<option value=\"UM\"  >United States Minor Outlying Isl</option>
													  														<option value=\"UY\"  >Uruguay</option>
													  														<option value=\"UZ\"  >Uzbekistan</option>
													  														<option value=\"VU\"  >Vanuatu</option>
													  														<option value=\"XV\"  >Vatican City</option>
													  														<option value=\"VE\"  >Venezuela</option>
													  														<option value=\"VN\"  >Vietnam</option>
													  														<option value=\"VI\"  >Virgin Islands (U.S.)</option>
													  														<option value=\"WF\"  >Wallis and Furuna Islands</option>
													  														<option value=\"EH\"  >Western Sahara</option>
													  														<option value=\"WS\"  >Western Samoa</option>
													  														<option value=\"YE\"  >Yemen</option>
													  														<option value=\"YU\"  >Yugoslavia</option>
													  														<option value=\"ZR\"  >Zaire</option>
													  														<option value=\"ZM\"  >Zambia</option>
													  														<option value=\"ZW\"  >Zimbabwe</option>
																										</select>
																			</td>
																			</tr>
																				<td width=\"140\">Company Phone:</td>
																				<td><input type=\"text\" size=\"20\" name=\"COMPANY_PHONE\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Company Mobile:</td>
																				<td><input type=\"text\" size=\"20\" name=\"COMPANY_MOBILE\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Company Toll Free:</td>
																				<td><input type=\"text\" size=\"20\" name=\"COMPANY_TOLL_FREE\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Company Email:</td>
																				<td><input type=\"text\" size=\"20\" name=\"COMPANY_EMAIL\" class=\"olotd5\"></td>
																			</tr>
																		</table>
																	</td>
																	<td valign=\"top\">
																		<table width=\"100%\"  cellpadding=\"5\" cellspacing=\"0\" border=\"0\">
																			<tr>
																				<td>
																					This is your Company's contact information as it will show up on invoices and billing.</td>
																				</td>
																			</tr>
																		</table>	
																	</td>
																</tr>
															</table>

															<!-- Site Information -->
															<br>
															<b>Web Site Information</b>
															<table class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
																<tr>
																	<td valign=\"top\" width=\"60%\">
																		<table>
																			<tr>
																				<td width=\"140\">Full Path:</td>
																				<td><input type=\"text\" size=\"40\" name=\"default_path\" value=\"".$default_path."/citecrm\"class=\"olotd5\"></td>
																			</tr>
																				<td width=\"140\">Site Name</td>
																				<td><input type=\"text\" size=\"40\" name=\"default_site_name\" value=\"http://".$default_server."/citecrm\" class=\"olotd5\"></td>
																			</tr>
																		</table>
																	</td>
																	<td valign=\"top\">
																		<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\">
																			<tr>
																				<td>
																					You need to give the full path to where the site lives. Do not include trailing / but include the directory name citecrm. IE.. If your site 
																					lives at /var/www/htdocs/citecrm use: /var/www/htdocs/citecrm
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>

															<br>
															<br>	
															<table>
																<tr>
																	<td>");
																	if(is_array($errors)) {
																	echo("Set up can not continue until the following errors are fixed:<br>");
																		foreach($errors as $key=>$val) {
																			echo("<font color=\"red\">Error $key: ");
																			foreach($val as $k=>$v) {
																				echo("$k $v");
																				}
																			echo("</font><br>");
																		}	
																	} else {
																		echo("<input type=\"submit\" name=\"submit\" value=\"Install\">");
																	}
																echo("</td>
																</tr>
															</table>
															</form>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</form>	  	  
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
			<br><br>
			<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td height=\"51\" align=\"center\" background=\"../images/index41.gif\"></td>
				</tr><tr>
					<td height=\"48\" align=\"center\" background=\"../images/index42.gif\"><span class=\"text3\">Copyright 2005 &copy; In-Cite CRM <a href=\"http://www.incitecrm.com\" target=\"new\">www.incitecrm.com</a>
								All rights reserved.</span></td>
				</tr><tr>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</center>

</body>
</html>");
	}
}

//@define('File_ROOT', '/home/www/htdocs/citecrm.com/demo');
//@define("SERVER_ROOT", "www.citecrm.com/demo");


function resolveDocumentRoot() {
   $current_script = dirname($_SERVER['SCRIPT_NAME']);
   $current_path  = dirname($_SERVER['SCRIPT_FILENAME']);
  
   /* work out how many folders we are away from document_root
       by working out how many folders deep we are from the url.
       this isn't fool proof */
   $adjust = explode("/", $current_script);
   $adjust = count($adjust)-1;
  
   /* move up the path with ../ */
   $traverse = str_repeat("../", $adjust);
   $adjusted_path = sprintf("%s/%s", $current_path, $traverse);

   /* real path expands the ../'s to the correct folder names */
   return realpath($adjusted_path);   
}

function get_server_name() {
$default_server = $_SERVER['SERVER_NAME'];
return $default_server;

}
#####################################
#		Check Lock					#
#####################################
function check_lock_file(){
	$lock_file = "../lock";
	if (file_exists($lock_file)) {
		return true;
	} else {
		return false;
	}
}



#####################################
#		Check If File Exists		#
#####################################
function file_exists_incpath ($file){
    $paths = explode(PATH_SEPARATOR, get_include_path());
 
    foreach ($paths as $path) {
        // Formulate the absolute path
        $fullpath = $path . DIRECTORY_SEPARATOR . $file;
 
        // Check it
        if (file_exists($fullpath)) {
            return true;
        }
    }

    return false;
}


#####################################
#		Check If File writes		#
#####################################
function check_write ($file) {
	if(is_writable($file)) {
		return true;
	} else {
		return false;
	}	
}

#####################################
#		Set Path					#
#####################################
function set_path($post_data)

{
	
	$install_date = date("M d Y h:i:s A" ,time());
	$filename = '../conf.php';
$content = "<?php
#############################################################
# Cite CRM	Customer Relations Management							#
# Copyright (C) 2003 - 2005 In-Cite CRM								#
# www.citecrm.com  dev@incitecrm.com									#
# This program is distributed under the terms and 				#
# conditions of the GPL	and is free to use or modify			#
# 																				#
# Installer																	#
# Version 0.0.1	Fri Oct 21 05:49:41 PDT 2005						#
#############################################################
\n
include('version.php');
@define('SEP',				'/');
@define('FILE_ROOT',			'".$_POST['default_path']."'.SEP);
@define('WWW_ROOT',				'".$_POST['default_site_name']."');
@define('IMG_URL',       		WWW_ROOT.'images');
@define('INCLUDE_URL',   		FILE_ROOT.'include'.SEP);
@define('SQL_URL',      		FILE_ROOT.'sql');
@define('CALENDAR_PATH',		FILE_ROOT.'DateTime');
@define('SMARTY_URL', 			INCLUDE_URL.'SMARTY'.SEP);
@define('ACCESS_LOG',			FILE_ROOT.'log'.SEP.'access.log');
@define('INSTALL_DATE',		'Dec 23 2005 10:50:45 PM');
@define('debug', 'no');

/* Database Settings */
@define('PRFX',			'".$_POST['db_prefix']."');
@define('DB_HOST', 	'".$_POST['db_host']."');
@define('DB_USER', 		'".$_POST['crm_db_user']."');
@define('DB_PASS', 	'".$_POST['crm_db_password']."');
@define('DB_NAME', 	'".$_POST['db_name']."');

/* IN Cite CRM locations */
@define('INCITCRM', \"http://dev.incitecrm.com/index.php\");

/* Load required Includes */
require(INCLUDE_URL.SEP.'session.php');
require(INCLUDE_URL.SEP.'auth.php');

/* Set Path for SMARTY in the php include path */
set_include_path(get_include_path() . PATH_SEPARATOR . INCLUDE_URL.'SMARTY'.SEP);
require('Smarty.class.php');

/* Set Path for ADODB in the php include path */
set_include_path(get_include_path() . PATH_SEPARATOR . INCLUDE_URL.'ADODB'.SEP);
require('adodb.inc.php');

/* Load smarty template engine */
global \$smarty;
\$smarty = new Smarty;
\$smarty->template_dir 	= FILE_ROOT.'templates';
\$smarty->compile_dir 		= FILE_ROOT.'cache';
\$smarty->config_dir 		= SMARTY_URL.'configs';
\$smarty->cache_dir 		= SMARTY_URL.'cache';
\$smarty->load_filter('output','trimwhitespace');

\$strKey = 'kcmp7n2permbtr0dqebme6mpejhn3ki';

/* create adodb database connection */
\$db = &ADONewConnection('mysql');
\$db->Connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);


\n";
				
	if (is_writable($filename)){
		if (!$handle = fopen($filename, 'w')){
			error_check("Cannot open file: $filename");
		}
		
		if (fwrite($handle, $content) === FALSE) {
			error_check("Cannot write to file: $filename");
		}
		fclose($handle);
	} else {
		error_check("The file $filename is not writable");
	}
	
}

#####################################
#		Generic error checking		#
#####################################
function error_check($error)
{
	echo("<font color=\"red\"><b>Error: </b></font>$error</br>");
	exit;
}

#####################################
#		Generic error checking		#
#####################################
function validate($data)
{
	//print_r($data);
	
	/* check for Null all values are required */
	foreach($data as $key => $val) {
		if($val == "") {
			error_check("Missing field $key.<br>");
		}
	}
	
	/* Check that paswords match for administrator */
	if($data['default_password'] != $data['default_password2']) {
		error_check("Administrators Passwords do not match.</br>");	
	}
}
?>
