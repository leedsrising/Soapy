<?PHP namespace CSH;

require '../vendor/autoload.php';
require '../config.php';

function get_webauth($app) {
  global $cfg;

  // This is just to fake webauth when developing on systems without it.
  if ($cfg['webauth']) {
    return [
      'ldap' => $_SERVER['WEBAUTH_USER'],
      'firstname' => $_SERVER['WEBAUTH_LDAP_GIVENNAME'],
      'lastname' => $_SERVER['WEBAUTH_LDAP_SN'],
    ];
  } else {
    return [
      'ldap' => 'dag10',
      'firstname' => 'John',
      'lastname' => 'Smith',
    ];
  }
}

function user_for_rfid($rfid) {
  // Only for development.
  if ($rfid == '12345') {
    return \UserQuery::create()->findOneByLDAP('dag10');
  } else if ($rfid == "0800B3E4E6B9") {
    return \UserQuery::create()->findOneByLDAP("smirabito");
  } else if ($rfid == "28006DD3E177") {
    return \UserQuery::create()->findOneByLDAP("jmf");
  }

  // Use JD's server for fetching user info for iButton/RFID id.
  if (($json = @file_get_contents(
      "http://www.csh.rit.edu:56124/?ibutton=" . $rfid)) === false) {
    return null;
  }

  // TODO: Cache RFID->LDAP mappings in db.

  try {
    $user_data = json_decode($json, true);
    $uid = $user_data['uid'];
    return \UserQuery::create()->findOneByLDAP($uid);
  } catch (Exception $e) {
    return null;
  }
}

