<?php
 
if (!isset($_SESSION['userid'])) {
	?><div class="error">Please <a href="index.php?title=Special:login">log in</a> or <a href="index.php?title=Special:signup">create an account</a> to authenticate yourself with external services.</div><?php
	return;
}
if (!isset($_GET['callback'], $_GET['appname'])) {
	?>
	<strong>Faliure</strong>:
	<code>callback</code> or <code>appname</code> parameter missing. Please contact the application owner to inform them of this problem.
	<?php
	return;
}
$appname = $_GET['appname'];
$callback = $_GET['callback'];
$title = "Authorize \"$appname\"";
?>Hello <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>,<p>An application claiming to be <strong><?php echo htmlspecialchars($appname); ?></strong> wants you to authenticate with them.</p><p><strong>PLEASE</strong> verify that the callback URL <strong><?php echo htmlspecialchars($callback); ?></strong> is legitimate, because the name can be faked.</p>
<p>If you wish to share your username with this application, click the button below.
<?php 
if (!isset($_GET['failure-callback'])) {
	?> If you do <em>not</em> wish to share your username, please press the Back button in your browser.<?php
}
$token = hash('sha512', uniqid('', true));
$authusers = json_decode(file_get_contents(__DIR__ . "/../authusers.json"));
$obj = new stdClass;
$obj->name = $_SESSION['username'];
$obj->time = time();
$authusers->$token = $obj;
file_put_contents(__DIR__ . "/../authusers.json", json_encode($authusers));
?>
</p>
<form action="<?php echo htmlspecialchars($callback); ?>" class="inline">
	<input type="hidden" name="authentication-token" value="<?php echo htmlspecialchars($token); ?>" />
	<input type="submit" value="Authorize" />
</form>
<?php
if (isset($_GET['failure-callback'])) {
	?><div style="float: right;"><small>...or <a href="<?php echo htmlspecialchars($_GET['failure-callback']); ?>">decline to share your username</a> if you don't want to</small></div>
<div style="clear: both;"></div><?php
}