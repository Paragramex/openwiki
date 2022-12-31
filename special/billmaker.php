<?php
 
?>
<p>Enter an amount to get a Unicode dollar bill.</p>
<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
    <label>Amount: <input name="amount" /></label>
    <input type="submit" value="Make it" />
    <input type="hidden" name="title" value="Special:billmaker" />
</form>
<?php
if (isset($_GET['amount'])) {
    $string = '[̲̅$̲̅(̲̅';
    for ($i = 0; $i < strlen($_GET['amount']); $i++) {
        $string .= str_replace('5', $_GET['amount'][$i], "5̲̅");
    }
    $string .= ')̲̅$̲̅]';
    ?><p>Output: <?php echo htmlspecialchars($string); ?></p><?php
}
$title = 'Make Unicode bill characters';