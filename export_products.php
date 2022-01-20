<?php
$rows = json_decode(file_get_contents("php://input"), true);
$text = "";
foreach($rows as $index=>$row) {
    if ($index == 0) continue;
    else $text .= "Product $index: " . $row['name'] . ", Category: " . $row['category'] . ", Price: " . $row['price'] . ";\n";
}
if ($text == "") $text = "No products selected. Please search for products before exporting.";

$filename = "products" . strval(microtime(true));
$file = fopen("$filename.txt", "w") or die("Unable to create export!");
fwrite($file, $text);
fclose($file);

echo $filename;
?>