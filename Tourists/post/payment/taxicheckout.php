<?php
require __DIR__ . '/vendor/autoload.php';

$stripe_secret_key = 'sk_test_51PIMdPDwJDfpiSSr04muva7l4XmHisSOvB1AKimDn25sT7tkMB5TRWvAt7we5h3xMMpL6zjAAas2J7ktFAoERJ4600kydtwfzm';
\Stripe\Stripe::setApiKey($stripe_secret_key);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $success_url = 'http://localhost:3000/Tourists/post/taxisubmit.php?' . http_build_query([
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'team_number' => $_POST['team_number'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'destination' => $_POST['destination'],
        'vehicle_type' => $_POST['vehicle_type'],

    ]);
    
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Service Charges',
                ],
                'unit_amount' => 500,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => $success_url,
        'cancel_url' => 'http://localhost:3000/Tourists/post/taxipost.php',
    ]);
    

    header('HTTP/1.1 303 See Other');
    header('Location: ' . $checkout_session->url);
    exit();
}

else {
    echo 'Invalid request method.';
}


?>