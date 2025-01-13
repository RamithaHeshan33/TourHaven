<?php
require __DIR__ . '/vendor/autoload.php';
session_start();

$stripe_secret_key = 'sk_test_51PIMdPDwJDfpiSSr04muva7l4XmHisSOvB1AKimDn25sT7tkMB5TRWvAt7we5h3xMMpL6zjAAas2J7ktFAoERJ4600kydtwfzm';
\Stripe\Stripe::setApiKey($stripe_secret_key);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $guider_email = $_POST['guider_email'];
    $trip_details = $_SESSION['trip_details'];

    require('../../../conn.php');
    $sql = "SELECT vehicle_name, price FROM vehicles WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $vehicle_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicle = $result->fetch_assoc();

    if (!$vehicle) {
        echo 'Invalid vehicle selection.';
        exit();
    }

    $total_price = 150;

    $success_url = 'http://localhost:3000/Tourists/post/submit.php?' . http_build_query($trip_details, '', '&') . '&vehicle_id=' . $vehicle_id . '&guider_email=' . $guider_email;
    $cancel_url = 'http://localhost:3000/Tourists/post/post.php';

    try {
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'lkr',
                    'product_data' => [
                        'name' => 'Service Charges',
                    ],
                    'unit_amount' => round($total_price * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $success_url,
            'cancel_url' => $cancel_url,
        ]);

        header('HTTP/1.1 303 See Other');
        header('Location: ' . $checkout_session->url);
        exit();
    } catch (Exception $e) {
        echo 'Error creating Stripe Checkout session: ' . $e->getMessage();
        exit();
    }
} else {
    echo 'Invalid request method.';
    exit();
}
?>
