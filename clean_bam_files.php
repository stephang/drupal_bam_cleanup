<?php

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
        $files_info[$matches[0]] = array(
          'year' => $matches[1],
          'month' => $matches[2],
          'day' => $matches[3],
          'hour' => $matches[4],
          'min' => $matches[5],
          'second' => $matches[6],
        );
      }
    }
    
    // Run deletion. Keep a backup per day
    
    print_r($files_info);
}

?>



