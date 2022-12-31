<?php


$title = "Editing presentation";
if (!isset($_GET['name'])) {
    $title = "Nothing to edit";
    ?><p>You must <a href="index.php?title=Special:presentation">select something to edit</a>.</p><?php
    return;
}