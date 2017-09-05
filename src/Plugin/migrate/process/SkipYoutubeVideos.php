<?php
# modules/custom/my_custom_module/src/Plugin/migrate/process/SkipYoutubeVideos.php
namespace Drupal\migrate_creporter\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Skip youtube videos.
 *
 * @MigrateProcessPlugin(
 *   id = "skip_youtube_files"
 * )
 */
class SkipYoutubeVideos extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (parse_url(end($value), PHP_URL_SCHEME) == 'youtube') {
      throw new MigrateSkipRowException();
    }
    return $value;
  }
}
