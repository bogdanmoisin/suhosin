--TEST--
suhosin input filter (suhosin.post.max_totalname_length - RFC1867 version)
--INI--
suhosin.log.syslog=0
suhosin.log.sapi=0
suhosin.log.script=0
suhosin.log.file=255
suhosin.log.file.time=0
suhosin.log.file.name={PWD}/suhosintest.$$.log.tmp
auto_append_file={PWD}/suhosintest.$$.log.tmp
suhosin.request.max_totalname_length=0
suhosin.post.max_totalname_length=7
--SKIPIF--
<?php include('../skipif.inc'); ?>
--COOKIE--
--GET--
--POST_RAW--
Content-Type: multipart/form-data; boundary=---------------------------20896060251896012921717172737
-----------------------------20896060251896012921717172737
Content-Disposition: form-data; name="var"

0
-----------------------------20896060251896012921717172737
Content-Disposition: form-data; name="var1"

1
-----------------------------20896060251896012921717172737
Content-Disposition: form-data; name="var2[]"

2
-----------------------------20896060251896012921717172737
Content-Disposition: form-data; name="var3[xxx]"

3
-----------------------------20896060251896012921717172737
Content-Disposition: form-data; name="var04"

4
-----------------------------20896060251896012921717172737
Content-Disposition: form-data; name="var05[]"

5
-----------------------------20896060251896012921717172737
Content-Disposition: form-data; name="var06[xxx]"

6
-----------------------------20896060251896012921717172737--
--FILE--
<?php
var_dump($_POST);
?>
--EXPECTF--
array(5) {
  ["var"]=>
  string(1) "0"
  ["var1"]=>
  string(1) "1"
  ["var2"]=>
  array(1) {
    [0]=>
    string(1) "2"
  }
  ["var04"]=>
  string(1) "4"
  ["var05"]=>
  array(1) {
    [0]=>
    string(1) "5"
  }
}
ALERT - configured POST variable total name length limit exceeded - dropped variable 'var3[xxx]' (attacker 'REMOTE_ADDR not set', file '%s')
ALERT - configured POST variable total name length limit exceeded - dropped variable 'var06[xxx]' (attacker 'REMOTE_ADDR not set', file '%s')
ALERT - dropped 2 request variables - (0 in GET, 2 in POST, 0 in COOKIE) (attacker 'REMOTE_ADDR not set', file '%s')
