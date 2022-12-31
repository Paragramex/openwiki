<?php


$title = "Make a password hash";
$algos = array('bcrypt', 'argon2i', 'md2', 'md5', 'sha1', 'sha256', 'sha384', 'sha512', 'ripemd128', 'ripemd160');
$specialAlgos = array('bcrypt' => PASSWORD_BCRYPT, 'argon2i' => PASSWORD_ARGON2I);
?>
<p>You can generate hashes below.</p>
<form action="index.php?title=Special:hash" method="GET">
	<input type="hidden" name="title" value="Special:hash" />
	<label>
		String to hash:
		<input name="text" value="<?php echo htmlspecialchars($_GET['text'] ?? ''); ?>" />
	</label>
	<label>Algorithm:
	<select name="algo">
		<?php 
		foreach ($algos as $algo) {
			if (($_GET['algo'] ?? '') === $algo) {
				echo "<option selected=\"selected\">$algo</option>";
			} else {
				echo "<option>$algo</option>";
			}
		}
		?>
	</select>
	</label>
	<input type="submit" />
</form>
<p>Hash: <strong class="copythis"><?php 
if (!isset($_GET['text'])) {
	echo "???";
} else {
	$algo = $_GET['algo'];
	if (isset($specialAlgos[$algo])) {
		echo password_hash($_GET['text'], $specialAlgos[$algo]);
		echo '</strong> (changes every time)<strong>';
	} else {
		if (!in_array($algo, $algos)) {
			echo "Invalid algorithm specified";
		} else {
			echo hash($algo, $_GET['text']);
		}
	}
}
?></strong></p>