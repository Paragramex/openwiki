<?php
 
$title = 'Patrol recent changes';
?>
<p>You can review recent edits here.</p>
<noscript id="no-js">
<?php 
require_once __DIR__ . "/getrecentedits.php";
?>
</noscript>
<div id="yes-js" hidden="hidden">
    <button onclick="refreshChanges()" id="refresh">Refresh list</button>
    <div id="recentChangesArea">Loading...</div>
</div>
<script>
    document.getElementById('no-js').hidden = 'hidden';
    document.getElementById('yes-js').hidden = '';
    function refreshChanges() {
        document.getElementById('recentChangesArea').style.opacity = '0.5';
        document.querySelector('#refresh').disabled = 'disabled';
        fetch('index.php?title=Special:getrecentedits&raw=true')
        .then(function(response) {
            return response.text();
        })
        .then(function(text) {
            document.getElementById('recentChangesArea').style.opacity = '1';
            document.querySelector('#refresh').disabled = '';
            document.getElementById('recentChangesArea').innerHTML = text;
        });
    }
    refreshChanges();
</script>