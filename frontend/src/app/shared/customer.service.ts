import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class CustomerService {

  constructor(private http : HttpClient) { }

  public url = 'http://localhost/restaurant/backend/';

  getCustomerList(){
    return this.http.get(this.url + 'customer/get_customer.php').toPromise();
  }
}
