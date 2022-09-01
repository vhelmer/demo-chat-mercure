import {Component, Inject, OnInit} from '@angular/core';
import {FormControl, FormGroup, Validators} from "@angular/forms";
import {Message, MessageService} from "../message.service";
import {SSEService} from "../sse.service";
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from "@angular/material/dialog";
import {MessageEvent} from "event-source-polyfill";

export interface LoginData {
  name: string;
}

@Component({
  selector: 'login-dialog',
  templateUrl: 'login-dialog.html',
})
export class LoginDialog {
  loginForm = new FormGroup({
    name: new FormControl('', [
      Validators.required,
    ]),
  });
  constructor(
    public dialogRef: MatDialogRef<LoginDialog>,
    @Inject(MAT_DIALOG_DATA) public data: LoginData,
  ) {}

  onSubmit() {
    this.data.name = this.loginForm.value["name"] as string;
    this.dialogRef.close(this.data);
  }
}

@Component({
  selector: 'app-chat',
  templateUrl: './chat.component.html',
  styleUrls: ['./chat.component.scss']
})
export class ChatComponent implements OnInit {

  name: string = "";
  messages : Message[] = [];
  messageForm = new FormGroup({
    text: new FormControl('', [
      Validators.required,
    ]),
  });

  constructor(
    private messageService: MessageService,
    private sseService: SSEService,
    public dialog: MatDialog
  ) { }

  ngOnInit(): void {
    const dialogRef = this.dialog.open(LoginDialog, {
      data: {name: this.name},
      disableClose: true
    });

    dialogRef.afterClosed().subscribe(result => {
      this.name = result.name;
    })
    this.messageService.getMessage().subscribe((messages) => {
      this.messages.push(...messages)
    })
    this.sseService.getServerSentEvent("http://localhost/api/messages/{id}").subscribe((data: MessageEvent) => {
      this.messages.unshift(JSON.parse(data.data) as Message);
    })
  }

  onSubmit() {
    this.messageService.addMessage(this.name, this.messageForm.value["text"] as string).subscribe(() => {
      this.messageForm.controls['text'].setValue("");
    });
  }

}
