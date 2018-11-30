<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
* Custom constants defined here
*/
define('VERIFIED',1);
define('NOTVERIFIED',0);
define('ACTIVE',1);
define('NOTACTIVE',0);
define('DELETED',1);
define('NOTDELETED',0);

define('RESERVE',1);
define('NOTRESERVE',0);
define('ISLOCK',0);
define('ISUNLOCK',1);

define('RIDEPENDING','pending');
define('RIDERUNNING','running');
define('RIDECANCEL','cancel');
define('RIDECOMPLETE','complete');

define('MAINTPENDING','pending');
define('MAINTSTART','start');
//define('RIDEPROGRESS','progress');
define('MAINTPROGRESS','progress');
define('MAINTCANCEL','cancel');
define('MAINTCOMPLETE','complete');

define('USER', 1);
define('MAINTENANCE', 2);
define('ACCEPTED', 1);
define('REJECTED', 2);
define('REMOVED', 3);
define('NOTACCEPTED', 0);
/*
define('OTP_USER', 'trade4sure');
define('OTP_PASS', '1766718756');
define('OTP_SENDER', 'TFSTFS');*/
define('NOTIFICATION_AUTH', 'AIzaSyCQl8x_w5R0uwo-vf0xTBV9HvLjeei68po');
//1 Promotional, 4 Transactional
define('OTP_ROUTE', 4);
define('OTP_SENDER', 'ESCOTR');
define('OTP_COUNRTY', '65');
define('OTP_AUTH', '187769AfzorwMqGQ5a2f9d17');
define('OTP_RETRYTYPE', 'text');
define('OTP_EXPIRY', '3');
define('OTP_SUCCESS', 'success');
define('OTP_ERR', 'error');

define('NOFITICATION_USERS', 1000);


define('NOTVIEWED', 0);
define('VIEWED', 1);
define('NOTAPROVED', 0);
define('APROVED', 1);
define('OTP_TIMEOUT', 180);


define('CONFIRMED', 1);
define('NOTCONFIRMED', 0);
//Memeberships
define('MEMBER_FREE', 1); 
define('LOGGED',1);
define('NOTLOGGED',0);
define('NOTSENT',0);
define('SENT',1);
define('UNREAD',2);

define('SIGNUPFB',1);
define('SIGNUPGEN',0);

define('RIDETRANSCTIONS',0);
define('TOPUPTRANSCTIONS',1);
define('REFFERALTRANSCTIONS',2);
//define('AUTOCANCEL',10);//minutes

define('BASICAUTHUSERNAME','Scooter');
define('BASICAUTHPASSWORD','1Af2GH');

define('TASKCOMPLETEYES','Yes');
define('TASKCOMPLETENO','No');

define('ASSIGN',1);
define('REASSIGN',2);
define('NOTASSIGN',0);
define('ADMINTASK',1);
define('USERTASK',2 );
define('OPENPORT', 9001);
define('MINBATTERYLVL', 30.1);

define('NOTUNDERMAINTAINANCE', 0);
define('UNDERMAINTAINANCE', 1);
define('TIMEOUTSEC', 10);
define('SETMAXSPEEDCMD', '++SET12');
define('SETMINVOLTAGE', '++SET16');
define('SETSTOPRIDE', '++CTL02 0x0D 0x0A');
//define('GOOGLE_LOC_API_KEY', 'AIzaSyCQrlRiMmqcCrv2KfVy74akVZJAQVS_b_o');
define('GOOGLE_LOC_API_KEY', 'AIzaSyA4cydsYV4YgUWEPVM0tU2I6H74HQXvcj4');

define('RESTRICTED_AREA_RADIUS', 50);
//Payment gateway URL
define('PAYMENT_URL_TEST', 'https://secure-dev.reddotpayment.com/service/payment-api' );
define('PAYMENT_URL_LIVE', 'https://secure.reddotpayment.com/service/payment-api' );
define('PAYMENT_QUERY_REDIRECT_URL_TEST', 'https://secure-dev.reddotpayment.com/service/Merchant_processor/query_redirection' );
define('PAYMENT_QUERY_REDIRECT_URL_LIVE', 'https://secure.reddotpayment.com/service/Merchant_processor/query_redirection' );
define('PAYMENT_LIVE', false);
define('PAYMENT_MERCHANT_NO', '1007778204');
define('PAYMENT_MERCHANT_SECRETE_KEY', "0JmuW1SQs6PvdeBUjFNPST2SZxwz7WezgGqw0GYrovJnhRQqnomm2qH1MLIbbwNcvSTsGEewyZb2IyLLkN2tZWsN5HoiuZPyx6FclK9aLgtRODRsuJRSt7Iqh2YV2X0H");
define('PAYMENT_HASH_KEY', 'sha512');
define('PAYMENT_CURRENCY', 'SGD');
define('PAYMENT_API_MODE', 'redirection_hosted');
define('PAYMENT_TYPE', 'S');
define('PAYMENT_PREFIX', 'eScooterPayment');

define("TITLE", "Scooto"); 
define("SHORT_DESC", "It's a smart move");
define("WEB_ADD", strtolower(TITLE.".com"));

define("TRANS_NEW", "NEW");
define("TRANS_INPROCESS", "INPROCESS");
define("TRANS_COMPLETED", "COMPLETED");
define("TRANS_FAILED", "FAILED");
define("TRANS_REJECTED", "REJECTED");
define("TRANS_CANCELLED", "CANCELLED");