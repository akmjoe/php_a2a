<?php
// Install script
if (file_exists('config.php')) {
	die ('Configuration file exists! Cannot re-run installer!');
}

if(!is_writeable('./')) {
	die('I do not have write access on install directory, unable to install!');
}
define('DIR_ROOT',getcwd().'/');
define('DIR_ADMIN',DIR_ROOT.'admin/');
define('DIR_INCLUDES',DIR_ROOT.'includes/');
require_once 'includes/application_top.php';

$messageStack = new messageStack();

$install = new install();

if(isset($_POST['save'])) {
	// attempt to install
	if($install->save()) {
		// success - redirect to admin
		header('location: admin/index.php');
	}
}
?>
<!DOCTYPE html>

<html>
<head>
    <title>Install</title>
</head>
<body>
<h1>Install</h1>
<p>This program requires MYSQL and the PHP MySQLi database connector.</p>
<p>Please enter your connection information below.</p>
<form method="post">
	<label for="db_host">Database Host:</label>
	<input type="text" name="db_host" id="db_host" value="<?php echo $install->db_host; ?>">
	<br>
	<label for="db_user">Database Username:</label>
	<input type="text" name="db_user" id="db_user" value="<?php echo $install->db_user; ?>">
	<br>
	<label for="db_password">Database Password:</label>
	<input type="text" name="db_password" id="db_password" value="<?php echo $install->db_password; ?>">
	<br>
	<label for="db_name">Database Name:</label>
	<input type="text" name="db_name" id="db_name" value="<?php echo $install->db_name; ?>">
	<br>
	<label for="db_port">Database Port:</label>
	<input type="text" name="db_port" id="db_port" value="<?php echo $install->db_port; ?>">
	<br>
	<label for="email">Admin email:</label>
	<input type="text" name="email" id="email" value="<?php echo $install->email; ?>">
	<br>
	<label for="password">Admin Password:</label>
	<input type="password" name="password" id="password" value="">
	<br>
	<input type="submit" name="save" value="Save">
</form>
</body>
</html>
