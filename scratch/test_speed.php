<?php
$t = microtime(true);
$orphans = App\Models\Media::get()->filter(fn($m) => $m->usage_count === 0)->count();
echo 'Time: ' . (microtime(true) - $t) . 's, Orphans: ' . $orphans . PHP_EOL;
