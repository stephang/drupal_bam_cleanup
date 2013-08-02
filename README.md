Keep backup&migrate's scheduled folder small and lean.
==================

Helper for drupal's backup and migrate module (https://drupal.org/project/backup_migrate).

Delete backup files in the following way
- today:             keep all backups
- this & last week:  keep one backup per day
- all the rest:      keep one backup per week

Usage:
  clean_bam_files.php [directory]

Example:
  clean_bam_files.php /var/www/myproject/sites/default/files/backup_migrate/scheduled

This script works with both drupal 6 and 7 (actually, it doesn't require any drupal instance).
