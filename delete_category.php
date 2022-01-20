<?php
//get product id
$id = intval($_GET['cid']);

try {
    //open db connection
    $conn = new PDO("mysql:host=localhost;dbname=ProductAppDB", "productapp", "products123");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //check if category contains products
    $prep = $conn->prepare("SELECT * FROM Products WHERE ProductCatId = :pcid");
    $prep->bindParam(':pcid', $id);
    $prep->execute();
    $products = $prep->fetchAll();
    if (count($products) == 0) {
        //delete category
        $prep = $conn->prepare("DELETE FROM Categories WHERE CatId = :cid");
        $prep->bindParam(':cid', $id);
        $prep->execute();
        echo "Category deleted successfully.";
    } else echo "Error. The category contains products. Please delete all products from the category first.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>