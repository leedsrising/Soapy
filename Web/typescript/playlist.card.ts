import {
  EventEmitter,
  Component,
  Input,
  Output,
  ElementRef,
  AfterViewInit,
  AfterViewChecked,
  ChangeDetectorRef} from 'angular2/core';

import {Playlist} from './soapy.interfaces';
import {StaticData} from './StaticData';

declare var jQuery: JQueryStatic;


@Component({
  selector: 'playlist-card',
  template: StaticData.templates.PlaylistCard,
  host: {
    '[class.collapsed]': '!expanded',
    '[class.hidden]': '!playlists',
    '[class.cancelable]': 'cancelable',
    '(window:scroll)': 'handleScroll($event)',
    '(window:touchend)': 'handleScroll($event)',
  },
})
export class PlaylistCardComponent implements AfterViewInit, AfterViewChecked {
  @Output() playlistSelected: EventEmitter<Playlist> = new EventEmitter<Playlist>();

  private $el: JQuery;
  private $header: JQuery;
  private _playlists: Playlist[];
  private _selectedPlaylist: Playlist = null;
  private _formerlySelectedPlaylist: Playlist = null;

  constructor(private el: ElementRef,
              private _changeDetector: ChangeDetectorRef) {
    this.$el = jQuery(this.el.nativeElement);
  }

  public ngAfterViewChecked() {
    this.updateStickyHeader();
    // Adds ripple effects to list items.
    (<any>window).componentHandler.upgradeElements(this.el.nativeElement);
  }

  public ngAfterViewInit() {
    this.$header = this.$el.find('.sticky-header');
    (<any>window).Stickyfill.add(this.$header[0]);
    this.hide();
  }

  public hide() {
    this.$el.hide();
  }

  public show() {
    this.$el.fadeIn();
    this.updateStickyHeader();
  }

  @Input()
  public set playlists(playlists: Playlist[]) {
    this._playlists = playlists;

    if (playlists) {
      this.show();
    }
  }

  public get playlists(): Playlist[] {
    return this._playlists;
  }

  @Input()
  public set selectedPlaylist(playlist: Playlist) {
    this._selectedPlaylist = playlist;

    if (playlist !== null) {
      this._formerlySelectedPlaylist = playlist;
    }
  }

  public get selectedPlaylist(): Playlist {
    return this._selectedPlaylist;
  }

  public get formerlySelectedPlaylist(): Playlist {
    return this._formerlySelectedPlaylist;
  }

  public get expanded(): boolean {
    return !this.selectedPlaylist;
  }

  public get cancelable(): boolean {
    return this.formerlySelectedPlaylist !== null;
  }

  public cancel() {
    if (this.formerlySelectedPlaylist) {
      return this.selectPlaylist(this.formerlySelectedPlaylist);
    }

    return false;
  }

  public selectPlaylist(playlist: Playlist) {
    if (this.expanded || playlist === null) {
      this.playlistSelected.emit(playlist);
    }

    return false;
  }

  private handleScroll(event) {
    this.updateStickyHeader();
  }

  private updateStickyHeader() {
    var stickyClass = 'stickied';

    var pos = this.$header.offset().top - jQuery(window).scrollTop();
    var top = parseInt(this.$header.css('top'), 10);
    var atTop = (pos <= top);

    if (this.$header.hasClass(stickyClass) && !atTop) {
      this.$header.removeClass(stickyClass);
    } else if (!this.$header.hasClass(stickyClass) && atTop) {
      this.$header.addClass(stickyClass);
    }
  }
}

