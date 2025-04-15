<?php
require('../config/db.php');
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
$customer_id = $_SESSION['customer_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_type = strtoupper($_POST['payment_type']);

    // Validate payment type
    $allowed_payment_types = ['COD', 'ONLINE'];
    if (!in_array($payment_type, $allowed_payment_types)) {
        echo json_encode(["status" => "error", "message" => "Invalid payment type."]);
        exit;
    }

    // Fetch Customer Email
    $email_query = "SELECT email FROM Customer WHERE id = ?";
    $stmt = $conn->prepare($email_query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $email_result = $stmt->get_result();
    $stmt->close();

    if ($email_result->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Customer email not found."]);
        exit;
    }

    $customer_data = $email_result->fetch_assoc();
    $customer_email = $customer_data['email'];

    // Fetch all pending orders of the customer
    $cart_query = "SELECT Order_id, Product_id, Price, Quantity, Total_Amount 
                   FROM Orders WHERE id = ? AND Order_Status = 'Pending'";
    $stmt = $conn->prepare($cart_query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $cart_result = $stmt->get_result();

    if ($cart_result->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Cart is empty."]);
        exit;
    }

    $order_items = [];
    while ($row = $cart_result->fetch_assoc()) {
        if (empty($row['Product_id'])) {
            echo json_encode(["status" => "error", "message" => "Invalid Product ID in cart."]);
            exit;
        }
        $order_items[] = $row;
    }
    $stmt->close();

    $total_amount = array_sum(array_column($order_items, 'Total_Amount'));
    $product_id = $order_items[0]['Product_id']; // Get first product ID from order

    // Confirm Orders
    $update_order = "UPDATE Orders SET Order_Status = 'Confirmed', Order_Date = NOW() 
                     WHERE id = ? AND Order_Status = 'Pending'";
    $stmt = $conn->prepare($update_order);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $stmt->close();

    // Get latest confirmed Order_id
    $get_order_id = "SELECT Order_id FROM Orders WHERE id = ? AND Order_Status = 'Confirmed' 
                     ORDER BY Order_id DESC LIMIT 1";
    $stmt = $conn->prepare($get_order_id);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order_data = $result->fetch_assoc();
    $stmt->close();

    if (!$order_data) {
        echo json_encode(["status" => "error", "message" => "Failed to fetch Order ID."]);
        exit;
    }

    $order_id = $order_data['Order_id'];

    // Insert billing details
    $insert_bill = "INSERT INTO Billing (Customer_id, Order_id, Total_Amount, Payment_status, Payment_type, Payment_date, Product_id) 
                    VALUES (?, ?, ?, 'Unpaid', ?, NOW(), ?)";
    $stmt = $conn->prepare($insert_bill);
    $stmt->bind_param("iidsi", $customer_id, $order_id, $total_amount, $payment_type, $product_id);
    $stmt->execute();
    $stmt->close();

    // Load MPDF Library
    $mpdf = new \Mpdf\Mpdf();

    // Generate invoice content
    $html = "<h2>Order Invoice</h2>
    <p>Order ID: $order_id</p>
    <p>Customer ID: $customer_id</p>
    <p>Total Amount: ₹$total_amount</p>
    <p>Payment Type: $payment_type</p>
    <p>Status: Confirmed</p>
    <hr>
    <table border='1' width='100%' cellspacing='0' cellpadding='5'>
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
    </tr>";

    // Fetch Order Items
    $fetch_items = "SELECT p.Pro_name, o.Price, o.Quantity, o.Total_Amount 
                    FROM Orders o 
                    JOIN Product p ON o.Product_id = p.Product_id 
                    WHERE o.id = ? AND o.Order_Status = 'Confirmed'";
    $stmt = $conn->prepare($fetch_items);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($item = $result->fetch_assoc()) {
        $html .= "<tr>
            <td>{$item['Pro_name']}</td>
            <td>₹{$item['Price']}</td>
            <td>{$item['Quantity']}</td>
            <td>₹{$item['Total_Amount']}</td>
        </tr>";
    }
    $stmt->close();

    $html .= "</table>";

    // Write HTML to PDF
    $mpdf->WriteHTML($html);

    // Save PDF to Server
    $invoice_dir = __DIR__ . "/../../invoices/";
    if (!is_dir($invoice_dir)) {
        mkdir($invoice_dir, 0777, true);
        chmod($invoice_dir, 0777);
    }
    $invoice_path = $invoice_dir . "bill_$order_id.pdf";
    $mpdf->Output($invoice_path, 'F');

    // Send Email with Invoice
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'emedicinewebsite@gmail.com'; // Replace with your email
        $mail->Password = 'jpqt dkaw zhgs qclr'; // Replace with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('your_email@example.com', 'E-Medicine Website');
        $mail->addAddress($customer_email);

        $mail->Subject = "Invoice for Order #$order_id";
        $mail->Body = "Dear Customer,\n\nThank you for your purchase. Please find your invoice attached.\n\nBest Regards,\nE-Medicine Store Team";
        $mail->addAttachment($invoice_path);

        $mail->send();
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Failed to send email: " . $mail->ErrorInfo]);
        exit;
    }

    // Provide PDF link in response
    $pdf_url = "/invoices/bill_$order_id.pdf";

    echo json_encode([
        "status" => "success",
        "message" => "Bill generated and sent successfully!",
        "order_id" => $order_id,
        "pdf_url" => $pdf_url,
        "redirect_url" => "/frontend/pages/order_confirmation.html?order_id=" . $order_id
    ]);
}
?>
