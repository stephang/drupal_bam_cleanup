<?php

$really_delete = FALSE;

if (isset($argv[1])) {
  $path = $argv[1];
}
else {
  $path = getcwd();
}

$protected_filenames = array(
  '.',
  '..',
  '.htaccess',
  'test.txt',
);

$pattern_datetime = '/(\d{4}).(\d{2}).(\d{2})[T_](\d{2}).(\d{2}).(\d{2}).mysql(.gz)?$/';

if ($handle = opendir($path)) {
    $files_info = array();
  
    /* This is the correct way to loop over the directory. */
    while (false !== ($entry = readdir($handle))) {
      // Exclude special files
      if (in_array($entry, $protected_filenames) ) {
        continue;
      }
      
      // Determine date and time of backup files
      $matches = array();
      preg_match($pattern_datetime, $entry, $matches);
      if (isset($matches[0])) {
        // print_r($matches);
        $files_info[$path . '/' . $entry] = array(
          'year' => $matches[1],
          'month' => $matches[2],
          'day' => $matches[3],
          'hour' => $matches[4],
          'min' => $matches[5],
          'second' => $matches[6],
          'delete' => FALSE,
        );
      }
    }
}

ksort($files_info);
    
// Prepare deletion. Keep a backup per day
foreach ($files_info as $file => &$info) {
  // Check if backup for this day exists
  $files_for_day = file_for_date($files_info, $info['year'], $info['month'], $info['day']);
  if (count($files_for_day) > 1) {
    $info['delete'] = TRUE;
  }
}

// Do actual delete
foreach ($files_info as $file => &$info) {
  if ($info['delete']) {
    echo "Delete $file";
    if (@$really_delete) {
      unlink($file);
      echo " done.";
    }
  }
  else {
    echo "Keep   $file";
  }
  echo "\n";
}

/**
 * Returns file names for a given date.
 * Consider only those files which were not marked for deletion.
 */
function file_for_date($files_info, $year, $month, $day) {
  $ret = array();
  foreach($files_info as $file => $info) {
    if (!$info['delete'] && $info['year'] == $year && $info['month'] == $month && $info['day'] == $day) {
      $ret[] = $file;
    }
  }
  return $ret;
}
    
// print_r($files_info);

?>



