<?php

require '../database.php';

// Extract, validate and sanitize the id.
$order_id = ($_GET['order_id'] !== null && (int)$_GET['order_id'] > 0)? mysqli_real_escape_string($con, (int)$_GET['order_id']) : false;

if(!$order_id)
{
  return http_response_code(400);
}

// Delete.
$item_sql = "DELETE FROM `order_items` WHERE `order_id` ='{$order_id}' LIMIT 1";
$order_sql = "DELETE FROM `orders` WHERE `order_id` ='{$order_id}' LIMIT 1";

if(mysqli_query($con, $item_sql))
{
  http_response_code(204);
}

if(mysqli_query($con, $order_sql))
{
  http_response_code(204);
}

// else
// {
//   return http_response_code(422);
// }