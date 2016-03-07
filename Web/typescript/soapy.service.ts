import {Injectable} from 'angular2/core';
import {Http, Response} from 'angular2/http';
import * as Rx from 'rxjs/Rx';

import {BaseError} from './error';
import {Playlist} from './soapy.interfaces';
import * as API from './soapy.api.interfaces';


export interface ServiceAppData {
  playlists?: Playlist[];
  selectedPlaylist?: Playlist;
}

export class APIError extends BaseError {
  constructor(message: string) {
    super(message);
  };
}

@Injectable()
export class SoapyService {
  public playlistsData: Rx.ConnectableObservable<ServiceAppData> = null;

  private playlists: { [id: string] : Playlist; } = {};

  constructor(private http: Http) {
    this.playlistsData = this.http.get('/api/me/playlists')
      .catch((err) => {
        var ret = err;

        if (err instanceof Response) {
          var res: Response = err;
          var json = res.json();
          if (json.hasOwnProperty('error')) {
            ret = new APIError(json.error);
          }
        }

        return Rx.Observable.throw(ret);
      })
      .map(res => res.json())
      .map(this.processAppData.bind(this))
      .do(null, (error) => {
        console.info('Error fetching playlist data:', error);
      })
      .publish();

    this.playlistsData.connect();
  }

  /**
   * Gets the playlist for a given id, or null if playlist isn't loaded.
   */
  public getPlaylist(id: string): Playlist {
    if (this.playlists[id]) {
      return this.playlists[id];
    }

    return null;
  }

  /**
   * Formats API response to a ServiceAppData and caches data.
   *
   * Throws an error if the response is an error message.
   */
  private processAppData(data: API.Response): ServiceAppData {
    if (data.error) {
      throw new Error('API Error: ' + data.error);
    } else if (!data.user) {
      throw new Error('No User object in API response.');
    }

    var ret: ServiceAppData = {};

    if (data.user.playlists) {
      data.user.playlists.forEach(this.cachePlaylist.bind(this));
      ret.playlists = data.user.playlists.map((apiPlaylist) => {
        return this.getPlaylist('' + apiPlaylist.soapyPlaylistId);
      });
    }

    if (data.user.selectedPlaylist) {
      var playlist = data.user.selectedPlaylist;

      if (!this.getPlaylist('' + playlist.soapyPlaylistId)) {
        this.cachePlaylist(playlist);
      }

      ret.selectedPlaylist = this.getPlaylist('' + playlist.soapyPlaylistId);
    }

    return ret;
  }

  /**
   * Maps SoapyPlaylist object from an API response into a Playlist object.
   */
  private formatPlaylistFromAPI(data: API.SoapyPlaylist): Playlist {
    var playlist: Playlist = {
      id: '' + data.soapyPlaylistId,
      title: data.spotifyPlaylist.name,
      tracks: data.spotifyPlaylist.tracks.total,
    };

    if (data.spotifyPlaylist && data.spotifyPlaylist.images) {
      var images = data.spotifyPlaylist.images;
      if (images.length > 0) {
        images.sort((a, b) => {
          return a.width - b.width;
        });
        playlist.image = images[0].url;
      }
    }

    return playlist;
  }

  /**
   * Adds the playlist to the playlist dictionary.
   */
  private cachePlaylist(playlist: API.SoapyPlaylist) {
    this.playlists['' + playlist.soapyPlaylistId] =
        this.formatPlaylistFromAPI(playlist);
  }
}

