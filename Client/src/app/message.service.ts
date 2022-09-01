import { Injectable } from '@angular/core';
import {map, Observable} from "rxjs";
import {HttpClient} from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class MessageService {

  constructor(private http: HttpClient) { }

  getMessage(): Observable<Message[]> {
    return this.http.get("/api/messages").pipe(
      map( (data: any) => {
          return data["hydra:member"];
        }
      )
    )
  }

  addMessage(name: string, text: string) {
    return this.http.post<Message>('/api/messages', {
      name: name,
      text: text
    }).pipe(
      map((data: any) => {
        return data as Message;
      })
    );
  }
}

export interface Message {
  id: number|null;
  name: string;
  text: string;
  createdAt: string|null
}
