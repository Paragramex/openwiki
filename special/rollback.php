<?php


if (!isset($_GET['page'])) {
    echo sysmsg('no-page-to-rollback');
    return;
}
$page = $_GET['page'];
if (!page_exists($page)) {
    echo sysmsg('rollback-page-doesnt-exist');
    return;
}
$page2ID = json_decode(file_get_contents(__DIR__ . "/../pages/page2ID.json"));
$pageid = $page2ID->$page;
if (!isset($_GET['revision'])) {
    $title = "Rollback failed";
    echo sysmsg('rollback-revision-failed');
    return;
}
$rev = $_GET['revision'];
$revisions = json_decode(file_get_contents(__DIR__ . "/../pages/data/$pageid/revisions.json"));
if (!isset($revisions[$rev])) {
    $title = "Rollback failed";
    echo sysmsg('rollback-revision-does-not-exist');
    return;
}
$oldrev = $rev;
if (!isset($revisions[$rev + 1])) {
    $title = "Rollback failed";
    echo sysmsg('rollback-cant-revert-to-current-revision');
    return;
}
if (!canEditPage($page)[0]) {
    $title = "Rollback failed";
    $why = canEditPage($page)[1];
    echo sysmsg("cant-edit-$why");
    return;
}
$rev = count($revisions) - 1 - $oldrev;
if (isset($_POST['do-it'])) {
    $author = userinfo($revisions[$oldrev]->author)->username;
    $summary = "Restored revision $oldrev by [[User:$author|$author]] ([[User talk:$author|talk]])";
    if ($_POST['summary']) $summary .= ": {$_POST['summary']}";
    $before = file_get_contents(__DIR__ . "/../pages/data/$pageid/pastRevisions/$oldrev/page.md");
    modifyPage($page, $before, $summary);
    $title = "Rollback done";
    echo sysmsg('rollback-done', $summary, $page);
    return;
}
$title = "Confirm rollback";
echo sysmsg('rollback-header');
$curr = page_get_contents($page);
$last = file_get_contents(__DIR__ . "/../pages/data/$pageid/pastRevisions/$oldrev/page.md");
require_once __DIR__ . "/../diff.php";
diff($curr, $last, "Latest revision", "Revision to revert to");
?>
<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
<label><?php echo sysmsgPlain("rollback-summary"); ?>
<input name="summary" />
</label>
<input type="submit" name="do-it" value="<?php echo sysmsgPlain("rollback-submit"); ?>" />
</form>