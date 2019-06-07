import { Component, OnInit } from '@angular/core';
import { OrderService } from '../order/order.service';
import { NgForm } from '@angular/forms';
import { MatDialog,MatDialogConfig } from '@angular/material/dialog';
import { OrderItemsComponent } from '../order-items/order-items.component';
import { CustomerService } from '../../shared/customer.service';
import { Customer } from '../../shared/customer';
import { Router,ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-order',
  templateUrl: './order.component.html',
  styleUrls: ['./order.component.css']
})
export class OrderComponent implements OnInit {
  customerList:Customer[];
  isValid:boolean = true;

  constructor(private service: OrderService,
    private dialog:MatDialog,
    private customerService:CustomerService,
    private router: Router,
    private currentRoute:ActivatedRoute) { }

  ngOnInit() {
    let order_id = this.currentRoute.snapshot.paramMap.get('order_id');
    if(order_id==null)
    this.resetForm();
    else{
      this.service.getOrderById(parseInt(order_id)).then(res=>{
        this.service.formData = res.order;
        this.service.orderItems = res.orderItems;
      });
    }
     this.customerService.getCustomerList().then(res => this.customerList = res as Customer[]);
  }

  resetForm(form?:NgForm){
    if(form = null)
    form.resetForm();
    this.service.formData = {
      order_id:null,
      order_number:Math.floor(10000+Math.random()*9000),
      customer_id:0,
      payment_method:'',
      grand_total:0,
      deleted_order_item_ids:'' 
    },
    this.service.orderItems=[];
  }

  AddOrEditOrderItem(orderItemIndex,order_id){
    const dialogConfig = new MatDialogConfig();
    dialogConfig.autoFocus = true;
    dialogConfig.disableClose = true;
    dialogConfig.width = "50%";
    dialogConfig.data = {orderItemIndex,order_id};
    this.dialog.open(OrderItemsComponent,dialogConfig).afterClosed().subscribe(res=>{
      this.updateGrandTotal();
    });
  }

  onDeleteOrderItem(order_item_id:number,i:number){
    if(order_item_id!=null)
    this.service.formData.deleted_order_item_ids +=order_item_id +",";
    this.service.orderItems.splice(i,1);
    this.updateGrandTotal();
  }
  
  updateGrandTotal(){
    this.service.formData.grand_total = this.service.orderItems.reduce((prev,curr)=>{
      return prev+curr.grand_total;
    },0);

    this.service.formData.grand_total = parseFloat(this.service.formData.grand_total.toFixed(2));
  }

  validateForm(){
    this.isValid = true;    
    if(this.service.formData.customer_id==0)
    this.isValid = false;
    else if(this.service.orderItems.length==0)
    this.isValid = false;
    return this.isValid;
  }

  onSubmit(form:NgForm){
    if(this.validateForm()){
      this.service.saveOrUpdateOrder().subscribe(res =>{
        this.resetForm(); 
        this.router.navigate(['/orders']);
      })
    }
  }
}
