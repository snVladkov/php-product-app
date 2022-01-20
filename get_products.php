<?php
//input clean
function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
//get input
$cat = intval($_GET['cat']);
$name = clean_input($_GET['name']);
$pmin = $_GET['pmin'] == "" ? 0 : floatval($_GET['pmin']);
$pmax = $_GET['pmax'] == "" ? 10000000 : floatval($_GET['pmax']);
//conditions for query statement
$catCond = $cat == 0 ? "" : "ProductCatId = :cat AND";
$nameCond = $name == "" ? "" : "ProductName LIKE :name AND";

try {
    //open db connection
    $conn = new PDO("mysql:host=localhost;dbname=ProductAppDB", "productapp", "products123");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //get products
    $prep = $conn->prepare("SELECT * FROM Products JOIN Categories ON Products.ProductCatId = Categories.CatId WHERE $catCond $nameCond ProductPrice BETWEEN :pmin AND :pmax");
    $prep->bindParam(':pmin', $pmin);
    $prep->bindParam(':pmax', $pmax);
    if ($cat > 0) $prep->bindParam(':cat', $cat);
    if (strlen($name) > 0) { 
        $name = "%$name%";
        $prep->bindParam(':name', $name);
    }
    $prep->execute();
    $productArr = $prep->fetchAll();
    
    foreach($productArr as $p) {
        echo "
        <tr class='row px-3' id='row$p[0]'>
            <td class='col-3'><img src='./images/$p[4]' alt='product' class='img-fluid'></td>
            <td class='col-2 pt-5'>$p[2]</td>
            <td class='col-2 pt-5'>$p[6]</td>
            <td class='col-2 pt-5'>$p[3]</td>
            <td class='col-3 pt-4'>
                <a href='edit_product.php?pid=$p[0]' class='btn btn-info w-75 mb-3'>Edit</a>
                <button type='button' onclick='deleteProduct($p[0])' class='btn btn-danger w-75'>Delete</button>
            </td>
        </tr>";
    }
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}
//close db connection
$conn = null;
?>