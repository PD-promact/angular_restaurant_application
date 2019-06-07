import { Component, OnInit } from '@angular/core';
import { OrderService } from '../orders/order/order.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-orders',
  templateUrl: './orders.component.html',
  styleUrls: ['./orders.component.css']
})
export class OrdersComponent implements OnInit {
  orderList;

  constructor(private service:OrderService,
  private router:Router) { }

  ngOnInit() {
   this.refreshList();
  }

  refreshList(){ 
     this.service.getOrderList().then(res=> this.orderList = res);
  }

  openForEdit(order_id:number){  
    this.router.navigate(['/order/edit/' + order_id]);
  }

  onOrderDelete(order_id:number){
    this.service.deleteOrder(order_id).then(res=>{
      this.refreshList();
    });   
  }
}
