<?php
// wrapper for cron: run the sync worker with CLI-friendly env
chdir(__DIR__ . '/../');
passthru('php scripts/messaging_sync.php');

?>
