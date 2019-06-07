<?php

require '../database.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);

  // Validate.
  if($request->order_number === '' OR $request->payment_method === '' OR $request->grand_total === '')
  {
    return http_response_code(400);
  }

  // Sanitize.
  $order_number = mysqli_real_escape_string($con, $request->order_number);
  $customer_id = mysqli_real_escape_string($con, $request->customer_id);
  $payment_method = mysqli_real_escape_string($con, $request->payment_method);
  $grand_total = mysqli_real_escape_string($con, $request->grand_total);

  if($request->order_id==null || $request->order_id==0){
    
    // Create.
    $order_sql = "INSERT INTO `orders`(`order_id`,`order_number`,`customer_id`,`payment_method`,`grand_total`) VALUES (null,'{$order_number}','{$customer_id}','{$payment_method}','{$grand_total}')";

    if(mysqli_query($con,$order_sql))
    {
      //http_response_code(201);
      $order = [
        'order_number' => $order_number,
        'customer_id' => $customer_id,
        'payment_method' => $payment_method,
        'grand_total' => $grand_total,
        'order_id'    => mysqli_insert_id($con)
      ];
    }
  }else{

    $order_id = mysqli_real_escape_string($con, $request->order_id);
    
    // Update.
    $order_sql = "UPDATE INTO `orders`(`order_id`,`order_number`,`customer_id`,`payment_method`,`grand_total`) VALUES ('{$order_id}','{$order_number}','{$customer_id}','{$payment_method}','{$grand_total}')";

    if(mysqli_query($con,$order_sql))
    {
      //http_response_code(201);
      $order = [
        'order_number' => $order_number,
        'customer_id' => $customer_id,
        'payment_method' => $payment_method,
        'grand_total' => $grand_total,
        'order_id'    => mysqli_insert_id($con)
      ];
    }
  }

  if(isset($request->order_item_id) && ($request->order_item_id==null || $request->order_item_id==0)){

    $order_id = "SELECT order_id FROM orders WHERE `order_number` = '{$order_number}' LIMIT 1";

    if($result_id = mysqli_query($con,$order_id))
      {
          while($row = mysqli_fetch_assoc($result_id))
          {
              $current_order = $row['order_id'];
          }
      }

    $i = 0;
    foreach($request->OrderItems as $item){
        $item_value[$i]['item_id'] = $item->item_id;
        $item_value[$i]['quantity'] = $item->quantity;

    // Create.
    $item_sql = "INSERT INTO `order_items`(`order_item_id`,`order_id`,`item_id`,`quantity`) VALUES (null,'{$current_order}','{$item_value[$i]['item_id']}','{$item_value[$i]['quantity']}')";

    if(mysqli_query($con,$item_sql))
    {
          //http_response_code(201);
          $order_item = [
          'order_id' => $order_id,
          'item_id' => $item_value[$i]['item_id'],
          'quantity' => $item_value[$i]['quantity'],
          'order_item_id'    => mysqli_insert_id($con)
          ];
          //echo json_encode($order_item);
      }
      $i++;
    }
  }else{
    $order_id = "SELECT order_id FROM orders WHERE `order_number` = '{$order_number}' LIMIT 1";

    if($result_id = mysqli_query($con,$order_id))
      {
          while($row = mysqli_fetch_assoc($result_id))
          {
              $current_order = $row['order_id'];
          }
      }

    $i = 0;
    foreach($request->OrderItems as $item){
        $item_value[$i]['item_id'] = $item->item_id;
        $item_value[$i]['quantity'] = $item->quantity;
        $item_value[$i]['order_item_id'] = $item->order_item_id;

    // Create.
    $item_sql = "UPDATE INTO `order_items`(`order_item_id`,`order_id`,`item_id`,`quantity`) VALUES ('{$item_value[$i]['order_item_id']}','{$current_order}','{$item_value[$i]['item_id']}','{$item_value[$i]['quantity']}')";

    if(mysqli_query($con,$item_sql))
    {
          //http_response_code(201);
          $order_item = [
          'order_id' => $order_id,
          'item_id' => $item_value[$i]['item_id'],
          'quantity' => $item_value[$i]['quantity'],
          'order_item_id'    => $item_value[$i]['order_item_id'] 
          ];
          //echo json_encode($order_item);
      }
      $i++;
    }
  } 
  /*else
  {
    http_response_code(422);
  }*/
}