export interface SelectableOption {
  id: string;
  title: string;
}

export interface Track extends SelectableOption {
  id: string;
  title: string;
  artists?: string[];
  album?: string;
  valid?: boolean;
  local?: boolean;
}

export interface Playlist extends SelectableOption {
  id: string;
  title: string;
  tracklist?: Track[];
  tracks?: number;
  image?: string;
}

export interface User {
  ldap: string;
  firstName: string;
  lastName: string;
  image?: string;
  paired: boolean;
}

export interface Playback {
  shuffle: boolean;
}

