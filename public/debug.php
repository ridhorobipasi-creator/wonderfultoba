<?php echo file_exists("../storage/logs/laravel.log") ? "<pre>".htmlspecialchars(implode("", array_slice(file("../storage/logs/laravel.log"), -200)))."</pre>" : "No log found."; ?>
