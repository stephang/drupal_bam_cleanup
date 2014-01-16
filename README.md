Keep backup&migrate's scheduled folder small and lean.
==================

Helper for drupal's backup and migrate module (https://drupal.org/project/backup_migrate).

Delete backup files on the local filesystem in the following way
- today:             keep all backups
- this & last week:  keep one backup per day
- all the rest:      keep one backup per week

Usage:
  clean_bam_files.php [directory]

Usage examples:

1. Clean one specific directory  
  `clean_bam_files.php /var/www/myproject/sites/default/files/backup_migrate/scheduled`
1. Alternatively: Clean all backup&migrate scheduled directories  
  `find -name scheduled -exec clean_bam_files.php {} \;`

This script works with both drupal 6 and 7 (actually, it doesn't require any drupal instance).

*Please note:*
- It works only if you use the default backup&migrate filename pattern (the timestamp is extracted from the filename because of this <a href="https://drupal.org/node/1357402">issue</a>.)
- It works only for backups stored on the local disk (it won't touch any remote or other backup locations).
- In its default mode, deletion is simulated. In the script set $really_delete = TRUE if you want to delete.

