<?php

/**
 * Create dummy backup files for the last three years, that were created
 * every three hours.
 */
$today = time();
$day = 60 * 60 * 24;
$period = $day * 365 * 3;

// Set to true to get some debugging.
$debug = FALSE;

// Create testcase directory.
$dirname = 'testcase';
$backupfile_basename = 'Site-or-ProjectName.domain-';

if (!file_exists($dirname)) {
  mkdir($dirname);
}

for ($date = $today; $date > $today - $period;) {
  $date -= $day;
  
  if ($debug) {
    echo "\n--------------------\n" . date('Y.m.d_H.i.s', $date) . "\n--------------------\n";
  }
  
  for ($now_time = 0; $now_time < 24; $now_time+=3) {
    // 2011.06.14_13.49.18
    $timestamp = date('Y.m.d_H.i.s', $date + $now_time * 60 * 60);
    
    if ($debug) {
      echo $timestamp . "\n";
    }
   
    $filename = $dirname . '/' . $backupfile_basename . $timestamp . 'mysql.gz';
    file_put_contents($filename, '');
  }
}

echo "Backups created in $dirname.\n"
?>
