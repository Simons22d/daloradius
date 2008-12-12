<?php
/*
 *********************************************************************************************************
 * daloRADIUS - RADIUS Web Platform
 * Copyright (C) 2007 - Liran Tal <liran@enginx.com> All Rights Reserved.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 *********************************************************************************************************
 *
 * Authors:     Liran Tal <liran@enginx.com>
 *
 * Description:	An update/upgrade page to handle the upgrade of the database tables
 *
 *********************************************************************************************************
 */

$failureMsg = "";
$successMsg = "";

include_once('library/config_read.php');

function updateErrorHandler($err) {
/*
        echo("<br/><b>Database error</b><br>
                <b>Failure Message: </b>" . $err->getMessage() . "<br><b>Debug info: </b>" . $err->getDebugInfo() . "<br>");
*/
}

if (!isset($configValues['DALORADIUS_VERSION'])) {
	$failureMsg .= "Couldn't find the configuration variable DALORADIUS_VERSION defined in <b>daloradius.conf.php</b><br/>";
	$missingVersion = "Failed detetion of daloRADIUS Version. Choose from the list";
}



if (isset($_POST['submit'])) {

	$databaseVersion = $_POST['daloradius_version'];
	$upgradeErrors = array();

	include('library/opendb.php');
	$dbSocket->setErrorHandling(PEAR_ERROR_CALLBACK, 'updateErrorHandler');		// set our own callback for error handling

	if ($databaseVersion == "0.9-7") {


		/* Begining set of SQL entries */
	
		$sql = "ALTER TABLE userinfo ADD changeuserinfo VARCHAR(128) AFTER notes;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		}


		$sql = "ALTER TABLE operators ADD mng_rad_profiles_duplicate VARCHAR(32) AFTER mng_rad_profiles_edit;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		} else {
			$sql = "UPDATE operators SET mng_rad_profiles_duplicate='yes' WHERE username='administrator';";
			$res = $dbSocket->query($sql);
		}


		$sql = "ALTER TABLE operators ADD mng_rad_attributes_import VARCHAR(32) AFTER mng_rad_attributes_del;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		} else {
			$sql = "UPDATE operators SET mng_rad_attributes_import='yes' WHERE username='administrator';";
			$res = $dbSocket->query($sql);
		}



		$sql = "ALTER TABLE operators ADD config_backup_createbackups VARCHAR(32) AFTER config_operators_new;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		} else {
			$sql = "UPDATE operators SET config_backup_createbackups='yes' WHERE username='administrator';";
			$res = $dbSocket->query($sql);
		}


		$sql = "ALTER TABLE operators ADD config_backup_managebackups VARCHAR(32) AFTER config_backup_createbackups;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		} else {
			$sql = "UPDATE operators SET config_backup_managebackups='yes' WHERE username='administrator';";
			$res = $dbSocket->query($sql);
		}


		$sql = "ALTER TABLE userinfo ADD address VARCHAR(200) AFTER mobilephone;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		}

		$sql = "ALTER TABLE userinfo ADD city VARCHAR(200) AFTER address;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		}

		$sql = "ALTER TABLE userinfo ADD state VARCHAR(200) AFTER city;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		}

		$sql = "ALTER TABLE userinfo ADD zip VARCHAR(200) AFTER state;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		}


		$sql = "ALTER TABLE operators DROP COLUMN bill_prepaid;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		}


		$sql = "ALTER TABLE operators CHANGE bill_persecond bill_rates_date varchar(32);";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		}


		$sql = " 
CREATE TABLE billing_rates (
  id int(11) unsigned NOT NULL auto_increment,
  rateName varchar(128) NOT NULL default '',
  rateType varchar(128) NOT NULL default '',
  rateCost int(32) NOT NULL default 0,
  creationdate datetime default '0000-00-00 00:00:00',
  creationby varchar(128) default NULL,
  updatedate datetime default '0000-00-00 00:00:00',
  updateby varchar(128) default NULL,
  PRIMARY KEY (id),
  KEY rateName (rateName(128))
);
";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		}

		$sql = "ALTER TABLE operators ADD bill_paypal_transactions VARCHAR(32) AFTER bill_rates_list;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		} else {
			$sql = "UPDATE operators SET bill_paypal_transactions='yes' WHERE username='administrator';";
			$res = $dbSocket->query($sql);
		}


		$sql = "ALTER TABLE operators ADD bill_plans_list VARCHAR(32) AFTER bill_paypal_transactions;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		} else {
			$sql = "UPDATE operators SET bill_plans_list='yes' WHERE username='administrator';";
			$res = $dbSocket->query($sql);
		}


		$sql = "ALTER TABLE operators ADD bill_plans_new VARCHAR(32) AFTER bill_plans_list;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		} else {
			$sql = "UPDATE operators SET bill_plans_new='yes' WHERE username='administrator';";
			$res = $dbSocket->query($sql);
		}


		$sql = "ALTER TABLE operators ADD bill_plans_edit VARCHAR(32) AFTER bill_plans_new;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		} else {
			$sql = "UPDATE operators SET bill_plans_edit='yes' WHERE username='administrator';";
			$res = $dbSocket->query($sql);
		}


		$sql = "ALTER TABLE operators ADD bill_plans_del VARCHAR(32) AFTER bill_plans_edit;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		} else {
			$sql = "UPDATE operators SET bill_plans_del='yes' WHERE username='administrator';";
			$res = $dbSocket->query($sql);
		}


		$sql = "
CREATE TABLE billing_plans (
	id int(8) NOT NULL auto_increment,
	planName varchar(128) default NULL,
	planId varchar(128) default NULL,
	planType varchar(128) default NULL,
	planTimeBank varchar(128) default NULL,
	planTimeType varchar(128) default NULL,
	planTimeRefillCost varchar(128) default NULL,
	planBandwidthUp varchar(128) default NULL,
	planBandwidthDown varchar(128) default NULL,
	planTrafficTotal varchar(128) default NULL,
	planTrafficUp varchar(128) default NULL,
	planTrafficDown varchar(128) default NULL,
	planTrafficRefillCost varchar(128) default NULL,
	planRecurring varchar(128) default NULL,
	planRecurringPeriod varchar(128) default NULL,
	planCost varchar(128) default NULL,
	planSetupCost varchar(128) default NULL,
	planTax varchar(128) default NULL,
	planCurrency varchar(128) default NULL,
	planGroup varchar(128) default NULL,
	creationdate datetime default '0000-00-00 00:00:00',
	creationby varchar(128) default NULL,
	updatedate datetime default '0000-00-00 00:00:00',
	updateby varchar(128) default NULL,
	PRIMARY KEY (id),
	KEY planName (planName)
);
";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		}

		$sql = "
CREATE TABLE `billing_paypal` (
  `id` int(8) NOT NULL auto_increment,
  `username` varchar(128) default NULL,
  `password` varchar(128) default NULL,
  `mac` varchar(128) default NULL,
  `pin` varchar(128) default NULL,
  `txnId` varchar(128) default NULL,
  `planName` varchar(128) default NULL,
  `planId` varchar(128) default NULL,
  `quantity` varchar(128) default NULL,
  `receiver_email` varchar(128) default NULL,
  `business` varchar(128) default NULL,
  `tax` varchar(128) default NULL,
  `mc_gross` varchar(128) default NULL,
  `mc_fee` varchar(128) default NULL,
  `mc_currency` varchar(128) default NULL,
  `first_name` varchar(128) default NULL,
  `last_name` varchar(128) default NULL,
  `payer_email` varchar(128) default NULL,
  `address_name` varchar(128) default NULL,
  `address_street` varchar(128) default NULL,
  `address_country` varchar(128) default NULL,
  `address_country_code` varchar(128) default NULL,
  `address_city` varchar(128) default NULL,
  `address_state` varchar(128) default NULL,
  `address_zip` varchar(128) default NULL,
  `payment_date` datetime default NULL,
  `payment_status` varchar(128) default NULL,
  `payment_address_status` varchar(128) default NULL,
  `payer_status` varchar(128) default NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
);
";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		}


		$sql = "
CREATE TABLE `userbillinfo` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `username` varchar(64) default NULL,
  `planName` varchar(128) default NULL,
  `contactperson` varchar(200) default NULL,
  `company` varchar(200) default NULL,
  `email` varchar(200) default NULL,
  `phone` varchar(200) default NULL,
  `address` varchar(200) default NULL,
  `city` varchar(200) default NULL,
  `state` varchar(200) default NULL,
  `zip` varchar(200) default NULL,
  `paymentmethod` varchar(200) default NULL,
  `cash` varchar(200) default NULL,
  `creditcardname` varchar(200) default NULL,
  `creditcardnumber` varchar(200) default NULL,
  `creditcardverification` varchar(200) default NULL,
  `creditcardtype` varchar(200) default NULL,
  `creditcardexp` varchar(200) default NULL,
  `notes` varchar(200) default NULL,
  `changeuserbillinfo` varchar(128) default NULL,
  `lead` varchar(200) default NULL,
  `coupon` varchar(200) default NULL,
  `ordertaker` varchar(200) default NULL,
  `billstatus` varchar(200) default NULL,
  `lastbill` datetime default '0000-00-00 00:00:00',
  `nextbill` datetime default '0000-00-00 00:00:00',
  `postalinvoice` varchar(8) default NULL,
  `faxinvoice` varchar(8) default NULL,
  `emailinvoice` varchar(8) default NULL,
  `creationdate` datetime default '0000-00-00 00:00:00',
  `creationby` varchar(128) default NULL,
  `updatedate` datetime default '0000-00-00 00:00:00',
  `updateby` varchar(128) default NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
);
";

		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		}


		$sql = "ALTER TABLE operators ADD bill_pos_list VARCHAR(32) AFTER bill_rates_list;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		} else {
			$sql = "UPDATE operators SET bill_pos_list='yes' WHERE username='administrator';";
			$res = $dbSocket->query($sql);
		}


		$sql = "ALTER TABLE operators ADD bill_pos_new VARCHAR(32) AFTER bill_pos_list;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		} else {
			$sql = "UPDATE operators SET bill_pos_new='yes' WHERE username='administrator';";
			$res = $dbSocket->query($sql);
		}


		$sql = "ALTER TABLE operators ADD bill_pos_edit VARCHAR(32) AFTER bill_pos_new;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		} else {
			$sql = "UPDATE operators SET bill_pos_edit='yes' WHERE username='administrator';";
			$res = $dbSocket->query($sql);
		}


		$sql = "ALTER TABLE operators ADD bill_pos_del VARCHAR(32) AFTER bill_pos_edit;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		} else {
			$sql = "UPDATE operators SET bill_pos_del='yes' WHERE username='administrator';";
			$res = $dbSocket->query($sql);
		}


		$sql = "
CREATE TABLE `billing_history` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `username` varchar(128) default NULL,
  `planName` varchar(128) default NULL,
  `billAmount` varchar(128) default NULL,
  `billAction` varchar(128) default NULL,
  `billPerformer` varchar(128) default NULL,
  `billReason` varchar(128) default NULL,
  `paymentmethod` varchar(200) default NULL,
  `cash` varchar(200) default NULL,
  `creditcardname` varchar(200) default NULL,
  `creditcardnumber` varchar(200) default NULL,
  `creditcardverification` varchar(200) default NULL,
  `creditcardtype` varchar(200) default NULL,
  `creditcardexp` varchar(200) default NULL,
  `coupon` varchar(200) default NULL,
  `discount` varchar(200) default NULL,
  `notes` varchar(200) default NULL,
  `creationdate` datetime default '0000-00-00 00:00:00',
  `creationby` varchar(128) default NULL,
  `updatedate` datetime default '0000-00-00 00:00:00',
  `updateby` varchar(128) default NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
);
";


		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		}


		$sql = "ALTER TABLE operators ADD bill_history_query VARCHAR(32) AFTER bill_plans_list;";
		$res = $dbSocket->query($sql);
	
		if (DB::isError($res)) {
			$errorMsg = $res->getMessage() ." ". $res->getDebugInfo();
			array_push($upgradeErrors, $errorMsg);
		} else {
			$sql = "UPDATE operators SET bill_history_query='yes' WHERE username='administrator';";
			$res = $dbSocket->query($sql);
		}


		/* Ending set of SQL entries */

		$databaseVersion = "0.9-8";

	} // 0.9-7

	include 'library/closedb.php';
	
	// after finishing with upgrade, update the daloRADIUS version parameter in library/daloradius.conf.php
	
	$configValues['DALORADIUS_VERSION'] = $databaseVersion;
	include ("library/config_write.php");


	$updateStatus = "true";
	$successMsg .= "<br/>Finished upgrade procedure to version $databaseVersion.
			<br/><br/><a href='index.php'>Return</a> to daloRADIUS Platform login.";
	
	foreach($upgradeErrors as $error) {
		$failureMsg .= $error."<br/>";
	}

}




?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>daloRADIUS</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/1.css" type="text/css" media="screen,projection" />
<script src="library/javascript/pages_common.js" type="text/javascript"></script>
<script src="library/javascript/rounded-corners.js" type="text/javascript"></script>
<script src="library/javascript/form-field-tooltip.js" type="text/javascript"></script>
<script type="text/javascript" src="library/javascript/ajax.js"></script>
<script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
<script src="library/javascript/rounded-corners.js" type="text/javascript"></script>
<script src="library/javascript/form-field-tooltip.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/form-field-tooltip.css" type="text/css" media="screen,projection" />

<body>
<?php
	$m_active = "Update";
	include_once("lang/main.php");
?>



<div id="wrapper">
<div id="innerwrapper">
		
                <div id="header">

                                <h1><a href="index.php"> <img src="images/daloradius_small.png" border=0/></a></h1>

                                <h2>
                                	Radius Management, Reporting and Accounting by <a href="http://www.enginx.com">Enginx</a>                                
                                </h2>

                                <ul id="nav">
				<a name='top'></a>

				<li><a href="index.php"><em>H</em>ome</a></a></li>
				<li><a href="update.php" class="active"><em>U</em>pdate</a></a></li>

                                </ul>


                                <ul id="subnav">

					<div id="logindiv" style="text-align: right;">
                                                <li>daloRADIUS Update/Upgrade</li><br/>
					</div>
                                </ul>
								
                </div>

      

<div id="sidebar">

	<h2>Database Update</h2>

	<h3>Quick-Access</h3>

	<ul class="subnav">

		<li><a href="index.php"><b>&raquo;</b>Home</a></li>
		<li><a href="update.php"><b>&raquo;</b>Update</a></li>

	</ul>
	
	<h3>Support</h3>

	<p class="news">
		daloRADIUS <br/>
		RADIUS Management Platform
		<a href="http://www.enginx.com" class="more">Read More &raquo;</a>
	</p>


	<h2>Search</h2>

	<input name="" type="text" value="Search" />

</div>

		
		
		
<div id="contentnorightbar">
		
	<h2 id="Intro"><a href="#"></a></h2>
	<center>
		<h2> daloRADIUS Platform - Update </h2>
		<br/>
	</center>

                <?php
                        include_once('include/management/actionMessages.php');
                ?>
	<br/>


<?php if ((isset($updateStatus)) && ($updateStatus == "true")): ?>
	

<?php else: ?>

<form name="update" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">


        <fieldset>

                <h302> Update </h302>
                <br/>

                <ul>

                <li class='fieldset'>
                <label for='name' class='form'>

	<?php 
		if (isset($missingVersion)) {
			$option = "<option value=\"\">Please select</option>";
			echo $missingVersion;
		} else {
			$option = "<option value=\"".$configValues['DALORADIUS_VERSION']."\">".$configValues['DALORADIUS_VERSION']."</option>";
			echo "Successfully detected your daloRADIUS version as";
		}
	?>

		</label>

	<select name="daloradius_version" class='form'>
		<?php echo $option; ?>
		<option value=""></option>
		<option value="0.9-7">0.9-7</option>
		<option value="0.9-6">0.9-6</option>
	</select>

		<br/>
	
                <li class='fieldset'>
                <br/>
                <hr><br/>
		<input type='submit' name='submit' value="Update" class='button' />
                </li>

                </ul>
        </fieldset>

</form>

<?php endif; ?>	







</div>
		

<div id="footer">
		
								

<?php
echo "
	".$l['all']['copyright2']."
	<br />
	</p>
";

?>

<br />
</p>
		
		</div>
		
</div>
</div>


</body>
</html>