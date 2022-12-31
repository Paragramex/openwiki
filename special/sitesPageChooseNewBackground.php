<?php
/*
This file is part of paragrams OpenWiki system, find him here: github.com/Paragramex or on replit: replit.com/@paragram.
*/

$title = 'Change Special:sites page background';
?>
<p>Here, you can change the background for the Special:sites page. Be aware this will only impact this computer in this session.</p>
<span id="status" style="color: black;">Change an option below to continue.</span>
<form action="javascript:;" onsubmit="document.querySelector('#status').style.color = 'green'; document.querySelector('#status').textContent = 'Background image will change on next load'; localStorage.setItem('custom-background', document.querySelector('select').value);">
	<label>
		Background image:
		<select id="options" onchange="document.querySelector('#status').style.color = 'red'; document.querySelector('#status').textContent = 'Click the Change button to save'; document.querySelector('img').hidden = ''; document.querySelector('img').src = 'https://alanhw.weeklyd3.repl.co/' + this.value;">
			<option value="none">No preference</option>
		</select>
	</label>
	<input type="submit" value="Change" />
	<input type="button" name="cancel" value="Remove preference" onclick="document.querySelector('#status').style.color = 'gold'; document.querySelector('#status').textContent = 'Preference removed'; localStorage.removeItem('custom-background');" />
</form>
<img style="float: right;" hidden="hidden" alt='Preview image' />
<script>
	          var backgrounds = [
              'better_bird_poop_road.png',
              'lapotty_indiana.png',
              'rickastley.png',
              'BSIV.png'
          ];
	backgrounds.forEach((b) => {
		const option = document.createElement('option');
		option.textContent = b;
		if (localStorage.getItem('custom-background') === b) option.selected = 'selected';
		document.querySelector('select').appendChild(option);
	});
</script>
<?php exit(0); ?>