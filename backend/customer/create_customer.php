<?php

require '../database.php';

// Get the posted data.
$data = file_get_contents("php://input");

$postdata = json_decode($data, true);

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);

  // Validate.
  if(trim($request->customer_name) === '')
  {
    return http_response_code(400);
  }

  // Sanitize.
  $customer_name = mysqli_real_escape_string($con, trim($request->customer_name));

  // Create.
  $sql = "INSERT INTO `customer`(`customer_id`,`customer_name`) VALUES (null,'{$customer_name}')";

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $customer = [
      'customer_name' => $customer_name,
      'customer_id'    => mysqli_insert_id($con)
    ];
    echo json_encode($customer);
  }
  else
  {
    http_response_code(422);
  }
}