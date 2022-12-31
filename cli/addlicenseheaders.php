Adding license headers...
<?php
$skip = array("extraload.js", "load.js", "markdown/htmlpurifier/", "markdown/parsedown/parsedown_old.php", "markdown/parsedown/parsedown.php", "markdown/parsedown/LICENSE.txt", "LICENSE", "footer.html", '.json', 'highlight-js', '.md', '.git', '.gitignore', 'optionsWarning');
$formats = array(
    "php" => array("start" => "<?php\n/*", "end" => "*/\n?>"),
    "css" => array("start" => "/*", "end" => "*/")
);
$licensetext = <<<TEXT
This file is part of paragrams OpenWiki system, find him here: github.com/Paragramex or on replit: replit.com/@paragram.
TEXT
?>
Files to skip:
<?php 
foreach ($skip as $s) echo "* $s\n";
$count = 0;
function scan($dir = __DIR__ . "/..") {
    $installPath = realpath('..');
    global $count;
    global $skip;
    global $formats;
    global $licensetext;
    $items = scandir($dir, SCANDIR_SORT_NONE);
    $actualItems = array();
    foreach ($items as $item) {
        $olditem = $item;
        $item = realpath("$dir/$item");
        $relative = substr($item, strlen($installPath));
        if ($olditem === '.' || $olditem === '..') continue;
        if (is_dir("$dir/$olditem")) {
            scan("$dir/$olditem");
            continue;
        }
        echo "Adding headers to items: #$count being processed\n";
        echo "Add to $item\n";
        if (in_array($relative, $skip)) {
            echo "$relative matched $skip on the skip list.\n";
            continue;
        }
        foreach ($skip as $sk) {
            if (strpos($relative, $sk) !== false) {
                echo "$relative indirectly matches item $sk on the skip list. Skipping.\n";
                continue 2;
            }
        }
        echo "Detecting file type... ";
        $filetype = explode('.', $olditem)[count(explode('.', $olditem)) - 1];
        echo $filetype;
        echo "\n";
        if (!isset($formats[$filetype])) {
            echo "Comment format unknown. Skipping.\n";
            continue;
        }
        echo "Writing file...\n";
        $contents = file_get_contents($item);
        $oldcontents = $contents;
        if (strpos($contents, $licensetext) === false) {
            $contents = "{$formats[$filetype]['start']}\n{$licensetext}\n{$formats[$filetype]['end']}\n{$contents}";
            // some cleanup
            $contents = str_replace("?>\n<?php", "", $contents);
            fwrite(fopen($item, "w+"), $contents);
        }
        $count++;
    }
}
$files = scan();
?>