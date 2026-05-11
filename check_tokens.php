<?php
$code = file_get_contents('storage/framework/views/a3d7ebbfbc151c6ef5b536d530bf4487.php');
$tokens = token_get_all($code);
$if = 0; $foreach = 0;
foreach ($tokens as $t) {
    if (is_array($t)) {
        if ($t[0] == T_IF) { $if++; echo "IF at line " . $t[2] . "\n"; }
        if ($t[0] == T_ENDIF) { $if--; echo "ENDIF at line " . $t[2] . "\n"; }
        if ($t[0] == T_FOREACH) { $foreach++; echo "FOREACH at line " . $t[2] . "\n"; }
        if ($t[0] == T_ENDFOREACH) { $foreach--; echo "ENDFOREACH at line " . $t[2] . "\n"; }
    } else if ($t == '{') {
        $if++; // treating { as generic block open
    } else if ($t == '}') {
        $if--; // treating } as generic block close
    }
}
echo "if block balance: $if, foreach balance: $foreach\n";
