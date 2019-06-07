<?php

require '../database.php';

/*if(isset($_REQUEST) && !empty($_REQUEST))
{*/
     
  // Sanitize.
  $order_id   = mysqli_real_escape_string($con, trim($_REQUEST['order_id']));

  // Get by id.
  $sql = "SELECT * FROM `orders` WHERE `order_id` ='{$order_id}' LIMIT 1";

  $result = mysqli_query($con,$sql);
  $order = mysqli_fetch_assoc($result);

  $order_sql = "SELECT * FROM `order_items` AS oi JOIN `item` AS i ON oi.item_id = i.item_id WHERE oi.order_id='{$order_id}' ";
  $order_result = mysqli_query($con,$order_sql);

  $itemDetails = array();
  $i =0;
  while($row = mysqli_fetch_assoc($order_result))
    {
      
      $itemDetails[$i]['order_item_id'] = $row['order_item_id'];
      $itemDetails[$i]['order_id']     = $row['order_id'];
      $itemDetails[$i]['item_id']      = $row['item_id'];
      $itemDetails[$i]['quantity']    = $row['quantity'];
      $itemDetails[$i]['item_name']    = $row['item_name'];
      $itemDetails[$i]['item_price']       = $row['item_price'];
      $itemDetails[$i]['grand_total']       = round($row['item_price']*$row['quantity'],2);
      $i++;
    }


  $result = array(
    'order'       => $order,
    'orderItems' => $itemDetails
  );
  $json = json_encode($result);
  echo $json;
//}
