<?php
require '../database.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);

  // Validate.
  if ((int)$request->customer_id < 1 || trim($request->customer_name) == '') {
    return http_response_code(400);
  }

  // Sanitize.
  $customer_id    = mysqli_real_escape_string($con, (int)$request->customer_id);
  $customer_name = mysqli_real_escape_string($con, trim($request->customer_name));

  // Update.
  $sql = "UPDATE `customer` SET `customer_name`='$customer_name' WHERE `id` = '{$customer_id}' LIMIT 1";

  if(mysqli_query($con, $sql))
  {
    http_response_code(204);
  }
  else
  {
    return http_response_code(422);
  }  
}