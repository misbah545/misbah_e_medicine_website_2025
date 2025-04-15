<?php
header('Content-Type: application/json');
include('../config/db.php');

// Check if Category_id is provided
if (isset($_GET['Category_id'])) {
    $category_id = intval($_GET['Category_id']);

    // Query to fetch subcategories for the given category
    $query = "SELECT SubCategory_id, SubCategory_Name FROM Subcategory WHERE Category_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $subcategories = [];
    while ($row = $result->fetch_assoc()) {
        $subcategories[] = $row;
    }

    echo json_encode($subcategories);
} else {
    echo json_encode(["error" => "Category ID not provided."]);
}
?>