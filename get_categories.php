<?php 
try {
    //open db connection
    $conn = new PDO("mysql:host=localhost;dbname=ProductAppDB", "productapp", "products123");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //get categories
    $prep = $conn->prepare("SELECT * FROM Categories");
    $prep->execute();
    $catArr = $prep->fetchAll();
    
    foreach($catArr as $cat) {
        echo "
        <tr class='row px-3' id='row$cat[0]'>
            <td class='col-3 pt-4'>$cat[1]</td>
            <td class='col-6 pt-4'>$cat[2]</td>
            <td class='col-3 pt-3'>
                <a href='edit_category.php?cid=$cat[0]' class='btn btn-warning w-75 mb-2'>Edit</a>        
                <button type='submit' onclick='deleteCategory($cat[0])' class='btn btn-danger w-75'>Delete</button>
            </td>
        </tr>";
    }
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}
//close db connection
$conn = null;
?>