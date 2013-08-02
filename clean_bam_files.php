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

$pattern = '/(02|03|04|05|06|07|08|09|10|11|13|14|15|16|17|18|19|20|21|22|23).\d+.\d+.mysql(.gz)?/';

if ($handle = opendir($path)) {
    /* This is the correct way to loop over the directory. */
    while (false !== ($entry = readdir($handle))) {
        if (in_array($entry, $protected_filenames) ) {
        continue;
      }
      
      if (preg_match($pattern, $entry)) {
        echo "Delete $entry\n";
        
      }
      else {
        echo "Skipping $entry\n";
      }
    }
}

?>



