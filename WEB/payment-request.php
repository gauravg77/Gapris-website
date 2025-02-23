<?php
session_start();
require_once '../Includes/dbConnect.php'; // Ensure this is your database connection file

if (isset($_POST['submit'])) {
    $amount = (int)($_POST['inputAmount4'] * 100); // Convert the amount to paisa (ensure it's an integer)
    $purchase_order_id = $_POST['inputPurchasedOrderId4'];
    $purchase_order_name = $_POST['inputPurchasedOrderName'];
    $name = $_POST['inputName'];
    $email = $_POST['inputEmail'];
    $phone = $_POST['inputPhone'];
    $address = $_POST['inputAddress']; // Address input

    // Validate the data
    if (empty($amount) || empty($purchase_order_id) || empty($purchase_order_name) || empty($name) || empty($email) || empty($phone) || empty($address)) {
        $_SESSION["validate_msg"] = '<script>
            Swal.fire({
                icon: "error",
                title: "All fields are required",
                showConfirmButton: false,
                timer: 1500
            });
        </script>';
        header("Location: checkout.php");
        exit();
    }

    // Check if the amount is a valid number
    if (!is_numeric($amount)) {
        $_SESSION["validate_msg"] = '<script>
            Swal.fire({
                icon: "error",
                title: "Amount must be a number",
                showConfirmButton: false,
                timer: 1500
            });
        </script>';
        header("Location: checkout.php");
        exit();
    }

    // Check if the phone number is a number
    if (!is_numeric($phone)) {
        $_SESSION["validate_msg"] = '<script>
            Swal.fire({
                icon: "error",
                title: "Phone must be a number",
                showConfirmButton: false,
                timer: 1500
            });
        </script>';
        header("Location: checkout.php");
        exit();
    }

    // Check if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["validate_msg"] = '<script>
            Swal.fire({
                icon: "error",
                title: "Email is not valid",
                showConfirmButton: false,
                timer: 1500
            });
        </script>';
        header("Location: checkout.php");
        exit();
    }

    // Insert order details into the database (orders table), including the address
    try {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, artwork_id, amount, contact_no, address, payment_status, created_at) 
        VALUES (?, ?, ?, ?, ?, 'Pending', NOW())");

        $stmt->execute([$_SESSION['user']['id'], $purchase_order_id, $amount, $phone, $address]); // Insert order details, including the address
    } catch (PDOException $e) {
        $_SESSION["validate_msg"] = '<script>
            Swal.fire({
                icon: "error",
                title: "Database error: ' . $e->getMessage() . '",
                showConfirmButton: false,
                timer: 1500
            });
        </script>';
        header("Location: checkout.php");
        exit();
    }

    // Prepare the data to send to Khalti API
    $postFields = array(
        "return_url" => "http://localhost/Gapris-website/WEB/payment-response.php",
        "website_url" => "http://localhost/Gapris-website/artwork-gallery.php",
        "amount" => $amount,
        "purchase_order_id" => $purchase_order_id,
        "purchase_order_name" => $purchase_order_name,
        "customer_info" => array(
            "name" => $name,
            "email" => $email,
            "phone" => $phone,
            "address" => $address // Include address in the customer info for Khalti API
        )
    );

    $jsonData = json_encode($postFields);

    // Make the request to Khalti API
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => array(
            'Authorization: key live_secret_key_68791341fdd94846a146f0457ff7b455',  // Use your actual Khalti secret key
            'Content-Type: application/json',
        ),
    ));

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        echo 'Error:' . curl_error($curl);
    } else {
        $responseArray = json_decode($response, true);

        // Handle the response based on the API response
        if (isset($responseArray['error'])) {
            echo 'Error: ' . $responseArray['error'];
        } elseif (isset($responseArray['payment_url'])) {
            // Redirect the user to the Khalti payment page
            header('Location: ' . $responseArray['payment_url']);
        } else {
            echo 'Unexpected response: ' . $response;
        }
    }

    curl_close($curl);
}
?>
