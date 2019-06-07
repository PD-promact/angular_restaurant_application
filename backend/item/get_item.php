<?php
/**
 * Returns the list of policies.
 */
require '../database.php';

$items = [];
$sql = "SELECT item_id, item_name,item_price FROM item";

if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $items[$i]['item_id']    = $row['item_id'];
    $items[$i]['item_name'] = $row['item_name'];
    $items[$i]['item_price'] = $row['item_price'];
    $i++;
  }

  echo json_encode($items);
}
else
{
  http_response_code(404);
}