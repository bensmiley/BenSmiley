<?php

$logfile = fopen( '../.pushlog', 'w');

ob_start();
var_dump( $_REQUEST );
$log = ob_get_clean();

fwrite( $logfile, $log );

fclose( $logfile );
