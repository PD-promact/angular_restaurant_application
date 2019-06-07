import { Injectable } from '@angular/core';
import {HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class ItemService {

  constructor(private http:HttpClient) { }

    public url = 'http://localhost/restaurant/backend/';

  getItemList(){
    return this.http.get(this.url + 'item/get_item.php').toPromise();
  }
}
