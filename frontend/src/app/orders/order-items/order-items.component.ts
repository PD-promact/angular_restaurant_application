import { Component, OnInit,Inject } from '@angular/core';
import { NgForm } from '@angular/forms';
import { MAT_DIALOG_DATA,MatDialogRef } from '@angular/material';
import { OrderItem } from '../../orders/order-items/order-item';
import { ItemService } from '../../shared/item.service';
import { Item } from '../../shared/item';
import { OrderService } from '../order/order.service';

@Component({
  selector: 'app-order-items',
  templateUrl: './order-items.component.html',
  styleUrls: ['./order-items.component.css']
})
export class OrderItemsComponent implements OnInit {
  formData:OrderItem;
  itemList: Item[];
  isValid:boolean = true;

  constructor(
    @Inject(MAT_DIALOG_DATA) public data,
    public dialogRef:MatDialogRef<OrderItemsComponent>,
    private itemService:ItemService,
    private orderService:OrderService) { }

  ngOnInit() {

    this.itemService.getItemList().then(res=> this.itemList = res as Item[]);
    if(this.data.orderItemIndex==null)

    this.formData = {
      order_item_id:null,
      order_id:this.data.order_id,
      item_id:0,
      item_name:'',
      item_price:0,
      quantity:0,
      grand_total:0
    }
    else
      this.formData = this.orderService.orderItems[this.data.orderItemIndex];        
  }

  updatePrice(ctrl){
    if(ctrl.selectedIndex==0){
      this.formData.item_price=0;
      this.formData.item_name='';
    }else{
      this.formData.item_price = this.itemList[ctrl.selectedIndex-1].item_price;
      this.formData.item_name = this.itemList[ctrl.selectedIndex-1].item_name;
    }
    this.updateTotal();
  }

  updateTotal(){
    this.formData.grand_total = parseFloat((this.formData.quantity * this.formData.item_price).toFixed(2));
  }

  OnSubmit(form:NgForm){
    if(this.validateForm(form.value)){
      if(this.data.orderItemIndex==null)
      this.orderService.orderItems.push(form.value);
      else
      this.orderService.orderItems[this.data.orderItemIndex] = form.value;
      this.dialogRef.close();
    }
  }

  validateForm(FormData:OrderItem){
    this.isValid = true;
    if(FormData.item_id==0)
      this.isValid = false;
    else if(FormData.quantity==0)
      this.isValid = false;
    return this.isValid;
  }

}
