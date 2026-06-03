<?php
header('Content-Type: text/plain');
echo shell_exec('cat ../storage/logs/laravel.log | tail -n 200');
