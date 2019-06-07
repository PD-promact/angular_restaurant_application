import { Injectable } from '@angular/core';
import { Order } from './order';
import { OrderItem } from '../order-items/order-item';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class OrderService {

  formData:Order;
  orderItems:OrderItem[];

  constructor(private http: HttpClient) { }

  public url = 'http://localhost/restaurant/backend/';

  saveOrUpdateOrder(){

    var body = {
      ...this.formData,
      OrderItems : this.orderItems
    };

    return this.http.post(this.url + 'order/create_update_order.php',body);
  }

  getOrderList(){
    return this.http.get(this.url + 'order/get_order.php').toPromise();
  }

  getOrderById(order_id:number):any{
    return this.http.get(this.url + 'order/get_single_order.php?order_id='+order_id).toPromise();
  }

  deleteOrder(order_id:number):any{
    return this.http.delete(this.url + 'order/delete_order.php?order_id='+order_id).toPromise();
  }
}
