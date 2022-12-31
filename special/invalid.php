<?php
 
?>
You entered an invalid special page name. A list of all valid 
special pages is below:
<ul>
<?php 
$title = 'Bad special page';
global $specialPages;
foreach ($specialPages as $page => $filename) {
    if ($page === '.' || $page === '..') continue;
    ?><li><a href="index.php?title=Special:<?php echo htmlspecialchars(urlencode($page)); ?>">Special:<?php echo htmlspecialchars($page); ?></a></li><?php
}
?></ul>