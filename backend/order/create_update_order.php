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

 //check order id is set or not and if set than update that one.
 if($request->order_id== '' || $request->order_id== 0  ){
   // Create.
   $order_sql = "INSERT INTO orders(order_id,order_number,customer_id,payment_method,grand_total) VALUES (null,'{$order_number}','{$customer_id}','{$payment_method}','{$grand_total}')";
   
   if(mysqli_query($con,$order_sql))
   {
     //http_response_code(201);
     $order = [
       'order_number'    => $order_number,
       'customer_id'     => $customer_id,
       'payment_method'  => $payment_method,
       'grand_total'     => $grand_total,
       'order_id'        => mysqli_insert_id($con)
     ];
     //echo json_encode($order);
   }

   //set order id for further process
   $order_id  = mysqli_insert_id($con);

 }else{

   $order_id = $request->order_id;
   
   //update
   $update_order =mysqli_query($con,"UPDATE orders SET order_number='$order_number',customer_id='$customer_id',payment_method='$payment_method',grand_total='$grand_total'  WHERE order_id = '{$request->order_id}' LIMIT 1");
   //mysqli_query($con, $update_order);
 }
 $i = 0;
 foreach($request->OrderItems as $item){
     $item_value[$i]['item_id'] = $item->item_id;
     $item_value[$i]['quantity'] = $item->quantity;
 
 // Create.
 if($item->order_item_id== '' || $item->order_item_id== 0  ){
   
   $item_sql = "INSERT INTO order_items(order_item_id,order_id,item_id,quantity) VALUES (null,'{$order_id}','{$item_value[$i]['item_id']}','{$item_value[$i]['quantity']}')";
     if(mysqli_query($con,$item_sql))
     {
         //http_response_code(201);
         $orderitem = [
         'order_id'     => $order_id,
         'item_id'      => $item_value[$i]['item_id'],
         'quantity'    => $item_value[$i]['quantity'],
         'order_item_id' => mysqli_insert_id($con)
         ];
         //echo json_encode($orderitem);
     }
     $i++;
   }else{
     //update orderItem
     $update_orderitem =mysqli_query($con,"UPDATE order_items SET order_id='$order_id',item_id='{$item_value[$i]['item_id']}',quantity='{$item_value[$i]['quantity']}'  WHERE order_item_id = '{$item->order_item_id}' LIMIT 1");
   }
 }
 // for deleted orderitems
 if(isset($request->deleted_order_item_ids)){
   $orderitemids = explode(',',($request->deleted_order_item_ids));
   foreach($orderitemids as $id){
     if($id!=""){
       $id  = str_replace('undefined','',$id);
       $sql = mysqli_query($con,"DELETE FROM order_items WHERE order_item_id ='{$id}' LIMIT 1");
     }
   }
 }
 return "successful !!";
 /*else
 {
   http_response_code(422);
 }*/
}
