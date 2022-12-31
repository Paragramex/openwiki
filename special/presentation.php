<?php


if (!isset($_SESSION['userid'])) {
    $title = "Please log in";
    echo '<p>Please log in to use Presentations.</p>';
    return;
}
$title = "Parody presentations";
?>
<p>Please pick a presentation to open.</p>
<p>Presentations are shared between users. Do not put confidential data in them.</p>
<ul>
<?php
$presentations = json_decode(file_get_contents(__DIR__ . "/../presentations.json"));
foreach ($presentations as $key => $presentation) {
    ?><li><a href="index.php?title=Special:editpresentation&title=<?php echo htmlspecialchars(urlencode($key)); ?>"><?php echo htmlspecialchars($presentation->title); ?></a></li><?php
}
?>
<li><form action="index.php">
    <input type="hidden" name="title" value="Special:editpresentation" />
    <label>Presentation name:
    <input name="name" />
    </label>
    <input type="submit" value="Create or edit presentation" />
</form></ul>
</ul>