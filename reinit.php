<?php
if (file_exists("db/application.dev.sqlite")) {
  echo unlink("db/application.dev.sqlite");
}
exec("vendor/bin/phinx migrate  ");
exec("vendor/bin/phinx seed:run");
