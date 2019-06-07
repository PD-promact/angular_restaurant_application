<?php
/**
 * Returns the list of policies.
 */
require '../database.php';

$customers = [];
$sql = "SELECT customer_id, customer_name FROM customer";

if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $customers[$i]['customer_id']    = $row['customer_id'];
    $customers[$i]['customer_name'] = $row['customer_name'];
    $i++;
  }

  echo json_encode($customers);
}
else
{
  http_response_code(404);
}