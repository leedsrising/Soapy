import {Component, OnInit, ChangeDetectorRef} from 'angular2/core';

import {ErrorCardComponent} from './error.card';
import {UserCardComponent} from './user.card';

import {StaticData} from './StaticData';
import {RFID, User, UsersService} from './users.service';

@Component({
  providers: [UsersService],
  selector: 'soapy-app',
  template: StaticData.templates.UsersApp,
  directives: [
    ErrorCardComponent,
    UserCardComponent,
  ],
})
export class UsersAppComponent implements OnInit {
  public errors: string[] = [];

  constructor(private _usersService: UsersService,
              private _changeDetector: ChangeDetectorRef) {}

  public ngOnInit() {
    this._usersService.errors.subscribe((err: any) => {
      var message = err.hasOwnProperty('message') ? err.message : '' + err;
      this.errors.push(message);
      this._changeDetector.detectChanges();
      window.scrollTo(0, 0);
    });

    this._usersService.subscribeToUsers();
  }

  public get unknownRFIDs(): RFID[] {
    return this._usersService.unknownRFIDs;
  }

  public get users(): User[] {
    return this._usersService.users;
  }

  public unpairRFID(rfid: string) {
    this._usersService.unpairRFID(rfid);
  }
}

