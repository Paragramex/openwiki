<?php
 
// Oh this is not used anymore
// It's just retained for historical
// reference

if (!isset($_SESSION['userid'])) {
	$title = "Log in to chat";
	?><p>The title says it all. Please log in to use the chat.</p><?php
	return;
}
if (!isset($_GET['room']) && !isset($_POST['create'])) {
	$title = "Choose a chat room";
	?>
	<p>Below are some chat rooms. You can also create some.</p>
	<p>Key:</p>
<ul>
	<li><strong>open</strong>: opens room in current tab.</li>
	<li><strong>open (new)</strong>: opens room in new tab.</li>
</ul>
	<ul>
		<?php 
			$rooms = json_decode(file_get_contents(__DIR__ . "/../chat-rooms/room2ID.json"));
	foreach ($rooms as $id => $room) {
		?><li><?php echo htmlspecialchars($room); ?> (<a href="index.php?title=Special:chat&room=<?php echo $id; ?>">open</a> <a href="index.php?title=Special:chat&room=<?php echo $id; ?>" target="_blank">[new]</a>)</li><?php
	}
		?>
	</ul>
<p>Or create a room:</p>
<form action="index.php?title=Special:chat" method="post">
	<label>Room name: <input name="title" /></label>
	<input type="submit" value="Create room" name="create" />
</form>
	<?php
	return;
}
$room2id = json_decode(file_get_contents(__DIR__ . "/../chat-rooms/room2ID.json"));
$roomid = $_GET['room'] ?? -1;
if (isset($_POST['create'])) {
	$currentRoomId = json_decode(file_get_contents(__DIR__ . "/../chat-rooms/currentRoomID.json"));
	$id = $currentRoomId;
	$roomid = $currentRoomId;
	$room2id->$id = $_POST['title'];
	fwrite(fopen(__DIR__ . '/../chat-rooms/room2ID.json', 'w+'), json_encode($room2id));
	fwrite(fopen(__DIR__ . "/../chat-rooms/currentRoomID.json", "w+"), json_encode(++$currentRoomId));
	mkdir(__DIR__ . "/../chat-rooms/data/$id", 0777);
	fwrite(fopen(__DIR__ . "/../chat-rooms/data/$id/creator.json", "w+"), json_encode($_SESSION['userid']));
	fwrite(fopen(__DIR__ . "/../chat-rooms/data/$id/messages.json", "w+"), '[]');
}
if (!isset($room2id->$roomid)) {
	?><div class="error">Room <?php echo $roomid; ?> not found. <a href="index.php?title=Special:chat">Return home</a>?</div><?php
}
$roomtitle = $room2id->$roomid;
$title = "Chat - room \"$roomtitle\" (#$roomid, user {$_SESSION['userid']})";
?>
<a href="index.php?title=Special:chat">(back to home)</a>
<table id="messages"><?php 
$limit = 100;
$messages = json_decode(file_get_contents(__DIR__ . "/../chat-rooms/data/$roomid/messages.json"));
$last50 = array_slice($messages, -1 * $limit);
if (count($last50) === 0) {
	?>
	<tr>
		<td colspan="2">There are currently no messages in the channel. Sorry. Wanna check out <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">this video</a> while you wait?</td>
	</tr>
	<?php
}
require_once __DIR__ . "/../markdown/parsedown/parsedown.php";
$Parsedown = new Parsedown;
	foreach ($last50 as $message) {
		?><tr>
			<th scope="row" id="nomessages">
				<?php 
		require_once __DIR__ . "/../date.php";
		echo userlink(userinfo($message->user)->username); ?>
		<br />
				<?php echo formatDate($message->time); ?>
			</th>
			<td><?php echo $Parsedown->text($message->text); ?></td>
		</tr><?php
	}
?></table>
<script>
	const room = <?php echo $roomid; ?>;
	const sse = new EventSource('index.php?raw&title=Special:chatfeed&room=' + room);
	sse.addEventListener('message', function(data) {
		console.log('NEW MESSAGE!');
		console.log(data);
	});
	function post(text) {
		document.querySelector('#status').textContent = 'Sending message...';
		fetch(`index.php?title=Special:post2chat&room=${encodeURIComponent(room)}`, {
			method: 'POST',
			body: new FormData(document.querySelector('#message'))
		})
		.then(function() {
					document.querySelector('#status').textContent = 'Sent!';
			this.value = '';
		}.bind(this))
		.catch(function(err) {
			document.querySelector('#status').textContent = err.toString();
		});
	}
</script>
<form style="position: sticky; bottom: 0;" action="javascript:;" onsubmit="post(document.querySelector('#enter').value" id="message">
	<label for="enter">Your message:</label><br />
	<textarea id="enter" style="box-sizing: border-box; width: 100%;" rows="2" onkeydown="if (!event.shiftKey &amp;&amp; event.keyCode === 13) {post(this.value); event.preventDefault();}"></textarea>
</form>