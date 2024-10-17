<?php
include 'RegisterIPN.php';
$merchantreference = rand(1, 1000000000000000000);
$phone = "0112163919";
$amount = $_POST['amount'];
$callbackurl = "https://28e7-41-139-202-31.ngrok-free.app/pesapalphp/response-page.php";
$branch = "Dev Jimin";
$first_name = "Alvin";
$middle_name = "Jimin";
$last_name = "Kiveu";
$email_address = "dev.jimin@gmail.com";
if(APP_ENVIROMENT == 'sandbox'){
  $submitOrderUrl = "https://cybqa.pesapal.com/pesapalv3/api/Transactions/SubmitOrderRequest";
}elseif(APP_ENVIROMENT == 'live'){
  $submitOrderUrl = "https://pay.pesapal.com/v3/api/Transactions/SubmitOrderRequest";
}else{
  echo "Invalid APP_ENVIROMENT";
  exit;
}
$headers = array(
    "Accept: application/json",
    "Content-Type: application/json",
    "Authorization: Bearer $token"
);

// Request payload
$data = array(
    "id" => "$merchantreference",
    "currency" => "KES",
    "amount" => $amount,
    "description" => "Payment description goes here",
    "callback_url" => "$callbackurl",
    "notification_id" => "$ipn_id",
    "branch" => "$branch",
    "billing_address" => array(
        "email_address" => "$email_address",
        "phone_number" => "$phone",
        "country_code" => "KE",
        "first_name" => "$first_name",
        "middle_name" => "$middle_name",
        "last_name" => "$last_name",
        "line_1" => "Pesapal Limited",
        "line_2" => "",
        "city" => "",
        "state" => "",
        "postal_code" => "",
        "zip_code" => ""
    )
);
$ch = curl_init($submitOrderUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo $response = curl_exec($ch);
$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "response code is",$responseCode;
$response = curl_exec($ch);
$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if($responseCode == 200){
    $responseData = json_decode($response, true);
    $redirect_url = $responseData['redirect_url'];
    
    // Redirect to the payment page
    header("Location: $redirect_url");
    exit();
} else {
    echo "Error: $responseCode";
    header('Location:index.php');
}

curl_close($ch);