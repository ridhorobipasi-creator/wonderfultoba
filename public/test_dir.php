<?php
echo "<pre>";
echo shell_exec("pwd");
echo "\n---\n";
echo shell_exec("ls -la ../");
echo "\n---\n";
echo shell_exec("ls -la");
echo "</pre>";
