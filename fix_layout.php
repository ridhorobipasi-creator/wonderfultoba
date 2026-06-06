<?php
$file = 'resources/views/tour/package-detail.blade.php';
$content = file_get_contents($file);

$startStr = '        <!-- Booking Form Sidebar (Sticky) -->';
$endStr = '        <!-- Content Part -->';

$posStart = strpos($content, $startStr);
$posEnd = strpos($content, $endStr, $posStart);

$formBlock = substr($content, $posStart, $posEnd - $posStart);

// Remove the form block from the original content
$content = str_replace($formBlock, "", $content);

// Now find END LEFT COLUMN WRAPPER
$endWrapperStr = '        </div> <!-- END LEFT COLUMN WRAPPER -->';
$posEndWrapper = strpos($content, $endWrapperStr);

// Insert formBlock after END LEFT COLUMN WRAPPER
$insertion = "        </div> <!-- END LEFT COLUMN WRAPPER -->\n\n" . $formBlock;
$content = str_replace($endWrapperStr, $insertion, $content);

file_put_contents($file, $content);
echo "Done";
