<?php

// TODO: Keep one file per week for other months than this month.
// TODO: Use command line -f to do an actual delete.
// TODO: This is really slow when deleting many files. Use a second array for deleted files.
// TODO: Drush module.

// Set to TRUE if you want to really delete files.
$really_delete = FALSE;

// Get command line arguments
if (isset($argv[1])) {
  $path = $argv[1];
}
else {
  $path = getcwd();
}

// Ignore these files
$ignore_files = array(
  '.',
  '..',
  '.htaccess',
  'test.txt',
);

// Regex pattern which is used to extract date and time from filenames.
// Examples: SiteName-2013-08-02T03-22-10.mysql.gz
//           SiteName-2013-08-02_03-22-10.mysql.gz
$pattern_datetime = '/-(\d{4}).(\d{2}).(\d{2})[T_\.](\d{2}).(\d{2}).(\d{2}).mysql(.gz)?$/';

if ($handle = opendir($path)) {
    $files_info = array();
  
    while (false !== ($entry = readdir($handle))) {
      // Ignore special files
      if (in_array($entry, $ignore_files) ) {
        continue;
      }
      
      // Determine date and time of backup files
      $matches = array();
      preg_match($pattern_datetime, $entry, $matches);
      if (isset($matches[0])) {
        $file_date = new DateTime(
          $matches[1] . '-' . $matches[2] . '-' . $matches[3] . ' ' . $matches[4] . ':' . $matches[5] . ':' . $matches[6]
        );
        
        $files_info[$path . '/' . $entry] = array(
          'timestamp' => $file_date,
          'delete' => FALSE,
        );
      }
    }
}

ksort($files_info);
    
// Prepare: Check which files will be deleted. 
$today = date('Y m d');
$this_week = date('W');
foreach ($files_info as $file => &$info) {
  
  // Skip today's backups
  if ( $today == $info['timestamp']->format('Y m d') ) {
    continue;
  }
  
  // Keep one backup per day for this week
  // Keep one backup per week for the rest    
  if ( $this_week == $info['timestamp']->format('W') ) {
    $files_for_day = files_for_period($files_info, $info['timestamp'], 'day');
    // $info['debug'] = 'one per day';
  } 
  else {
    $files_for_day = files_for_period($files_info, $info['timestamp'], 'week');
    // $info['debug'] = 'one per week';
  }
  
  if (count($files_for_day) > 1) {
    $info['delete'] = TRUE;
  }
}

// Do actual delete
$saved_space = 0;
foreach ($files_info as $file => &$info) {
  if ($info['delete']) {
    echo "Delete $file";
    $saved_space += filesize($file);
    if (@$really_delete) {
      unlink($file);
      @unlink($file . '.info');
      echo " done.";
    }
  }
  else {
    echo "Keep   $file";
  }
  echo "\n";
}

// Show saved disk space
$saved_space /= 1024 * 1024;
$saved_space = round($saved_space);
echo "\nSaved disk space: $saved_space MB.\n";

if (!$really_delete) {
  echo "Simulation only. Nothing was actually deleted.";
}



/**
 * Returns file names for a given period.
 * Consider only those files which were not marked for deletion.
 * 
 * @param $files_info array
 * @param $timestamp DateTime 
 *  Date within the period to search for
 * @param $period string
 *  Can be 'day' or 'week'.
 *  
 * @return array of filenames.
 */
function files_for_period($files_info, $timestamp, $period = 'day') {
  $ret = array();
  
  if ($period == 'day') {
    $period_format = 'Y m d';
  }
  else if ($period == 'week') {
    $period_format = 'W';
  }
  
  foreach($files_info as $file => $info) {
    if (!$info['delete'] && $info['timestamp']->format($period_format) == $timestamp->format($period_format) ) {
      $ret[] = $file;
    }
  }
  return $ret;
}
?>

