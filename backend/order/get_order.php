<?php
/**
 * Returns the list of policies.
 */
require '../database.php';

$orders = [];
$sql = "SELECT order_id,order_number,customer_id,payment_method,grand_total FROM orders";
$customer_name = "SELECT customer_name FROM customer WHERE `customer_id`={customer_id}";

if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $orders[$i]['order_id']    = $row['order_id'];
    $orders[$i]['order_number'] = $row['order_number'];
    $orders[$i]['customer_id'] = $row['customer_id'];;
    $orders[$i]['payment_method'] = $row['payment_method'];
    $orders[$i]['grand_total'] = $row['grand_total'];
    $i++;
  }

  echo json_encode($orders);
}
else
{
  http_response_code(404);
}