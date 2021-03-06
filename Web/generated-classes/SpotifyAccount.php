<?php

use Base\SpotifyAccount as BaseSpotifyAccount;

/**
 * Skeleton subclass for representing a row from the 'spotifyaccount' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SpotifyAccount extends BaseSpotifyAccount
{
  public function getDataForJson() {
    return [
      'username' => $this->getUsername(),
      'accessToken' => $this->getAccessToken(),
      'avatar' => $this->getAvatar(),
      ];
  }
}
