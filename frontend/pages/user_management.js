$(document).ready(function () {
    function fetchUsers() {
        $.ajax({
            url: "http://localhost:8000/backend/controllers/fetch_users.php",
            type: "GET",
            dataType: "json",
            success: function (users) {
                let userTable = $("#userTable");
                userTable.empty();

                users.forEach(user => {
                    userTable.append(`
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${user.phone}</td>
                            <td>${user.gender}</td>
                            <td>${user.address}</td>
                            <td>
                                <button class="edit-btn" data-id="${user.id}"><i class="fas fa-edit"></i></button>
                                <button class="delete-btn" data-id="${user.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `);
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching users:", error);
            }
        });
    }

    fetchUsers();
    setInterval(fetchUsers, 5000); // Refresh every 5 seconds

    // Delete user functionality
    $(document).on("click", ".delete-btn", function () {
        let userId = $(this).data("id");

        if (confirm("Are you sure you want to delete this user?")) {
            $.ajax({
                url: "http://localhost:8000/backend/controllers/delete_user.php",
                type: "POST",
                data: { id: userId },
                success: function (response) {
                    alert("User deleted successfully!");
                    fetchUsers();
                },
                error: function () {
                    alert("Error deleting user.");
                }
            });
        }
    });
});
