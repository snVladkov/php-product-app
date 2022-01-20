<?php
$rows = json_decode(file_get_contents("php://input"), true);
$text = "";
foreach($rows as $index=>$row) {
    if ($index == 0) continue;
    else $text .= "Category $index: " . $row['name'] . ", Description: " . $row['description'] .  ";\n";
}
if ($text == "") $text = "There are no categories.";

$filename = "categories" . strval(microtime(true));
$file = fopen("$filename.txt", "w") or die("Unable to create export!");
fwrite($file, $text);
fclose($file);

echo $filename;
?>