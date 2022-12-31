<?php
/*
This file is part of paragrams OpenWiki system, find him here: github.com/Paragramex or on replit: replit.com/@paragram.
*/

$title = "Editing presentation";
if (!isset($_GET['name'])) {
    $title = "Nothing to edit";
    ?><p>You must <a href="index.php?title=Special:presentation">select something to edit</a>.</p><?php
    return;
}