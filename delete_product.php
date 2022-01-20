<?php 
//get product id
$id = intval($_GET['pid']);

try {
    //open db connection
    $conn = new PDO("mysql:host=localhost;dbname=ProductAppDB", "productapp", "products123");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //delete image from server
    $prep = $conn->prepare("SELECT ProductImage FROM Products WHERE ProductId = :pid");
    $prep->bindParam(':pid', $id);
    $prep->execute();
    $result = $prep->fetchAll()[0];
    unlink("./images/$result[0]");
    //delete product from db
    $prep = $conn->prepare("DELETE FROM Products WHERE ProductId = :pid");
    $prep->bindParam(':pid', $id);
    $prep->execute();
    echo "Product deleted successfully.";
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}
$conn = null;
?>
