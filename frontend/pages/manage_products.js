$(document).ready(function () {
    // Fetch Categories for Dropdown
    function fetchCategoriesForDropdown() {
        $.ajax({
            url: "http://localhost:8000/backend/controllers/fetch_categories.php",
            type: "GET",
            dataType: "json",
            success: function (categories) {
                let categoryOptions = '<option value="">Select Category</option>';
                if (categories.length > 0) {
                    categories.forEach(category => {
                        categoryOptions += `<option value="${category.Category_id}">${category.Category_Name}</option>`;
                    });
                } else {
                    console.log("No categories found.");
                }
                $("#productCategory").html(categoryOptions); // Populate the dropdown
            },
            error: function (xhr, status, error) {
                console.log("Error fetching categories:", error);
            }
        });
    }

    function fetchCategoriesForTable() {
        $.ajax({
            url: "http://localhost:8000/backend/controllers/fetch_categories.php",
            type: "GET",
            dataType: "json",
            success: function (response) {
                let categoryTable = $("#categoryTable tbody");
                categoryTable.empty();

                if (response.categories && response.categories.length > 0) {
                    response.categories.forEach(category => {
                        categoryTable.append(`
                            <tr>
                                <td>${category.Category_id}</td>
                                <td>${category.Category_Name}</td>
                                <td>
                                    <button class="delete-btn category-delete-btn" data-id="${category.Category_id}">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    categoryTable.append(`<tr><td colspan="3">No categories found.</td></tr>`);
                }
            },
            error: function (xhr, status, error) {
                console.log("Error fetching categories:", error);
            }
        });
    }

    // Fetch Subcategories for Dropdown
    function fetchSubcategoriesForDropdown(categoryId) {
        $.ajax({
            url: "http://localhost:8000/backend/controllers/fetch_subcategories.php",
            type: "GET",
            data: { Category_id: categoryId },
            dataType: "json",
            success: function (subcategories) {
                let subcategoryOptions = '<option value="">Select Subcategory</option>';
                if (subcategories.error) {
                    console.log("Error fetching subcategories:", subcategories.error);
                } else {
                    subcategories.forEach(subcategory => {
                        subcategoryOptions += `<option value="${subcategory.SubCategory_id}">${subcategory.SubCategory_Name}</option>`;
                    });
                }
                $("#productSubCategory").html(subcategoryOptions);
            },
            error: function (xhr, status, error) {
                console.log("Error fetching subcategories:", error);
            }
        });
    }

    // Fetch subcategories when a category is selected
    $("#productCategory").on("change", function () {
        const categoryId = $(this).val();
        if (categoryId) {
            fetchSubcategoriesForDropdown(categoryId);
        } else {
            $("#productSubCategory").html('<option value="">Select Subcategory</option>');
        }
    });

    // Add Category
    $("#addCategoryBtn").on("click", function () {
        const categoryName = $("#categoryInput").val().trim();

        if (!categoryName) {
            alert("Please enter a category name!");
            return;
        }

        $.ajax({
            url: "http://localhost:8000/backend/controllers/add_category.php",
            type: "POST",
            data: { Category_Name: categoryName },
            success: function (res) {
                alert(res);
                fetchCategoriesForDropdown(); // Reload categories dropdown
                fetchCategoriesForTable(); // Reload categories table
                $("#categoryInput").val(""); // Clear input field
            },
            error: function (xhr, status, error) {
                console.log("Error adding category:", xhr.responseText);
                alert("Error adding category.");
            }
        });
    });

    // Fetch Products
    function fetchProducts() {
        $.ajax({
            url: "http://localhost:8000/backend/controllers/fetch_products.php",
            type: "GET",
            dataType: "json",
            success: function (products) {
                let productTable = $("#productTable tbody");
                productTable.empty();

                if (products.length === 0) {
                    productTable.append(`
                        <tr>
                            <td colspan="7" style="text-align: center;">No products found.</td>
                        </tr>
                    `);
                } else {
                    products.forEach(product => {
                        productTable.append(`
                                <tr>
                                    <td>${product.Pro_name || ''}</td>
                                    <td>${product.Description || ''}</td>
                                    <td>₹${product.Price || 0}</td>
                                    <td>${product.Stock || 0}</td>
                                    <td>${product.Category_Name || ''}</td>
                                    <td>${product.SubCategory_Name || ''}</td>
                                    <td>
                                        <button class="delete-product" data-id="${product.Product_id || ''}">Delete</button>
                                    </td>
                                </tr>
                            `);
                    });
                }
            },
            error: function () {
                console.log("Error fetching products.");
            }
        });
    }

    // Delete category function
    $(document).on('click', '.category-delete-btn', function () {
        const categoryId = $(this).data('id');
        if (confirm("Are you sure you want to delete this category?")) {
            $.ajax({
                url: "http://localhost:8000/backend/controllers/delete_category.php",
                type: "POST", // Fixed to POST
                data: { category_id: categoryId }, // Fixed key to category_id
                success: function (response) {
                    try {
                        let res = JSON.parse(response);
                        if (res.status === "success") {
                            alert("Category deleted successfully!");
                            fetchCategoriesForTable(); // Refresh category list
                        } else {
                            alert("Error deleting category: " + res.message);
                        }
                    } catch (e) {
                        alert("Error processing response: " + response);
                    }
                },
                error: function (xhr, status, error) {
                    alert("Error deleting category: " + error);
                }
            });
        }
    });

    // Initial Fetch
    fetchCategoriesForDropdown();
    fetchProducts();
    fetchCategoriesForTable(); // Removed unnecessary fetchSubcategoriesForDropdown()
});