///<reference path="../node_modules/moment/moment.d.ts"/>
import moment from 'moment';

import {EventEmitter, Injectable} from 'angular2/core';
import {Http, Response, URLSearchParams, Headers, RequestOptions} from 'angular2/http';
import * as Rx from 'rxjs/Rx';

import {StaticData} from './StaticData';
import {BaseError} from './error';
import * as API from './soapy.api.interfaces';


export class APIError extends BaseError {
  constructor(message: string) {
    super(message);
  };
}

export interface HumanDate {
  date: Date;
  fullText: string;
  fuzzyText: string;
}

export interface RFID {
  rfid: string;
  lastTap?: HumanDate;
}

export interface User {
  ldap: string;
  firstName: string;
  lastName: string;
  hasSpotifyAccount: boolean;
  avatar?: string;
  lastTap?: HumanDate;
  rfids: RFID[];
}

@Injectable()
export class UsersService {
  public errors: EventEmitter<any> = new EventEmitter();

  private _polling: boolean = false;
  private _maxPollInterval: number = 500; // milliseconds

  private _unknownRFIDs: { [rfid: string]: RFID; } = {};
  private _users: { [ldap: string]: User; } = {};

  constructor(private http: Http) {
    this.errors.subscribe((error) => {
      console.error('An error occurred in UsersService:', error);
    });
  }

  /**
   * Repeatedly polls for changes in RFID taps or mappings.
   */
  public subscribeToUsers() {
    this._polling = true;
    this.pollForUsers();
  }

  /**
   * Stops polling.
   */
  public unsubscribeFromUsers() {
    this._polling = false;
  }

  /**
   * Fetches the RFID mappings, including time of last tap.
   */
  public pollForUsers() {
    var startTime = new Date().getTime();

    this.fetchMappings().subscribe(res => {
      if (!this._polling) {
        return;
      }

      this.processUnknownRFIDs(res.unknownRFIDs);
      this.processUsers(res.users);

      // Calculate a short delay for refetching to avoid spamming.
      var elapsed = new Date().getTime() - startTime;
      var timeout = this._maxPollInterval - elapsed;
      if (timeout < 0) {
        timeout = 0;
      }

      setTimeout(this.pollForUsers.bind(this), timeout);
    });
  }

  /**
   * Get processed list of known users.
   */
  public get users(): User[] {
    return (<any>Object).values(this._users);
  }

  /**
   * Get processed list of unknown RFIDs.
   */
  public get unknownRFIDs(): RFID[] {
    return (<any>Object).values(this._unknownRFIDs);
  }

  /**
   * Sends an RPC to unpair an RFID fob.
   */
  public unpairRFID(rfid: string): Rx.Observable<API.Response> {
    if (!this.removeRFIDFromData(rfid)) {
      return Rx.Observable.throw(new Error('RFID not found.'));
    }

    var params = new URLSearchParams();
    params.set('rfid', rfid);

    return this
      .makePostRequest('/api/rfid/unpair', params)
      .map(res => res.json());
  }

  /**
   * Removes an RFID from the local cache of user data.
   * Returns false if RFID is not found.
   */
  private removeRFIDFromData(rfid: string) {
    for (var i = 0; i < this.users.length; i++) {
      var user: User = this.users[i];
      for (var j = 0; j < user.rfids.length; j++) {
        if (user.rfids[j].rfid === rfid) {
          user.rfids.splice(j, 1);
          return true;
        }
      }
    }

    return false;
  }

  /**
   * Converts a timestamp into a pair containing the date and a fuzzy timestamp.
   */
  private timestampToHumanDate(timestamp: string): HumanDate {
    var date = new Date((<any>Number).parseInt(timestamp));

    return {
      date: date,
      fullText: moment(date).format("ddd, MMMM Do YYYY, h:mm:ss a"),
      fuzzyText: moment(date).fromNow(),
    };
  }

  /**
   * Maps a user object from an API response into a user object for this page.
   */
  private mapApiUser(user: API.User): User {
    var ret: User = {
      ldap: user.ldap,
      firstName: user.firstName,
      lastName: user.lastName,
      hasSpotifyAccount: !!user.spotifyAccount,
      rfids: [],
    };

    if (user.spotifyAccount) {
      ret.avatar = user.spotifyAccount.avatar;
    }

    if (user.rfids) {
      ret.rfids = <RFID[]>user.rfids.map(this.mapApiRFID.bind(this));

      var sortedRFIDs = ret.rfids.slice(0).sort(
        (a, b) => b.lastTap.date.getTime() - a.lastTap.date.getTime());
      ret.lastTap = sortedRFIDs[0].lastTap;
    }

    return ret;
  }

  /**
   * Maps a API response's RFID tap into an RFID tap object for this page.
   */
  private mapApiRFID(rfid: API.RFID): RFID {
    return {
      rfid: rfid.rfid,
      lastTap: this.timestampToHumanDate(rfid.lastTap),
    };
  }

  /**
   * Processes current list of uknown RFIDs.
   */
  private processUnknownRFIDs(rfids: API.RFID[]) {
    rfids
    .map(this.mapApiRFID.bind(this))
    .forEach((rfid: RFID) => {
      if (this._unknownRFIDs.hasOwnProperty(rfid.rfid)) {
        (<any>Object).assign(this._unknownRFIDs[rfid.rfid], rfid);
        return;
      }

      this._unknownRFIDs[rfid.rfid] = rfid;
    });
  }

  /**
   * Processes current list of known users.
   */
  private processUsers(users: API.User[]) {
    users
    .map(this.mapApiUser.bind(this))
    .forEach((user: User) => {
      if (this._users.hasOwnProperty(user.ldap)) {
        (<any>Object).assign(this._users[user.ldap], user);
        return;
      }

      this._users[user.ldap] = user;
    });
  }

  /**
   * Fetches RFID->LDAP mappings along with their last tap timestamp.
   */
  private fetchMappings(): Rx.Observable<API.Response> {
    // TODO: TEMPORARY API MOCK
    var emitter = new EventEmitter<API.Response>();
    setTimeout(() => {
      emitter.emit({
        unknownRFIDs: [
          {
            rfid: 'UNKNOWN02',
            lastTap: '1482271152200',
          },
          {
            rfid: 'UNKNOWN01',
            lastTap: '' + (new Date().getTime() - 200000000 + (200000000 * Math.random())),
          },
        ],
        users: [
          {
            ldap: 'dag10',
            firstName: 'Drew',
            lastName: 'Gottlieb',
            isAdmin: false,
            spotifyAccount: {
              username: 'spotifyDag10',
              avatar: 'http://placehold.it/250x250',
            },
            rfids: [
              {
                rfid: '12345',
                lastTap: '1482271039547',
              },
            ],
          },
          {
            ldap: 'dev',
            firstName: 'John',
            lastName: 'Smith',
            isAdmin: false,
            spotifyAccount: {
              username: 'spotifyDev',
              //avatar: 'http://placehold.it/250x250',
            },
            rfids: [
              {
                rfid: 'devid01',
                lastTap: '' + (new Date().getTime() - (1000 * 60 * 60 * 24)),
              },
              {
                rfid: 'devid02',
                lastTap: '' + (new Date().getTime() - 200000000 + (200000000 * Math.random())),
              },
            ],
          },
        ],
      });
    }, 200);
    return emitter;

    /*
    return this
      .makeGetRequest('/api/users/all')
      .map(res => res.json());
     */
  }

  /**
   * Makes a parameterize post request to the API.
   */
  private makePostRequest(route: string, params?: URLSearchParams):
    Rx.Observable<Response> {

    var headers = new Headers({
      'Content-Type': 'application/x-www-form-urlencoded',
    });

    var options = new RequestOptions({
      headers: headers,
    });

    var body: string = params ? params.toString() : '';

    var res = this.http.post(route, body, options)
      .catch(this.catchAPIErrors.bind(this))
      .publishReplay(1);

    res.connect();

    return <Rx.ConnectableObservable<Response>> res;
  }

  /**
   * Makes a get request to the API.
   */
  private makeGetRequest(route: string): Rx.Observable<Response> {
    var res = this.http.get(route)
      .catch(this.catchAPIErrors.bind(this))
      .publishReplay(1);

    res.connect();

    return <Rx.ConnectableObservable<Response>> res;
  }

  /**
   * Catches Response errors and extracts an API error message if applicable.
   */
  private catchAPIErrors(err) {
    var ret: any = new Error('Error fetching data from server.');

    if (err.status !== 200) {
      ret = new Error(`Got status ${err.status} from server.`);
    } else if (err instanceof Response) {
      var res: Response = err;
      var json = res.json();
      if (json.hasOwnProperty('error')) {
        ret = new APIError(json.error);
      }
    }

    this.errors.emit(ret);
    return Rx.Observable.throw(ret);
  }
}
