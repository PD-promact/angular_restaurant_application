<?php

require '../database.php';

// Extract, validate and sanitize the id.
$customer_id = ($_GET['customer_id'] !== null && (int)$_GET['customer_id'] > 0)? mysqli_real_escape_string($con, (int)$_GET['customer_id']) : false;

if(!$customer_id)
{
  return http_response_code(400);
}

// Delete.
$sql = "DELETE FROM `customer` WHERE `customer_id` ='{$customer_id}' LIMIT 1";

if(mysqli_query($con, $sql))
{
  http_response_code(204);
}
else
{
  return http_response_code(422);
}