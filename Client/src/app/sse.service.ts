import { Injectable, NgZone } from '@angular/core';
import { Observable } from 'rxjs';
import { EventSourcePolyfill } from 'event-source-polyfill';
import { environment } from './../environments/environment';


@Injectable({
  providedIn: 'root'
})
export class SSEService {

  constructor(private zone: NgZone) {}

  private getEventSource(topic: string): EventSourcePolyfill {
    const url = new URL(environment.mercureHub);
    url.search = new URLSearchParams({topic: topic}).toString();
    return new EventSourcePolyfill(url.toString(), {
      headers: {
        'Authorization': 'Bearer ' + environment.jwtToken,
      }
    });
  }

  getServerSentEvent(topic: string): Observable<MessageEvent> {
    return new Observable(observer => {
      const eventSource = this.getEventSource(topic);
      eventSource.onmessage = ev => {
        this.zone.run(() => observer.next(ev as MessageEvent));
      };
    });
  }
}
