<?php 

require('lib/bk_zip.php'); /* include zip lib */
require('lib/bk_db.php'); /*include export code lib*/
$name_zip_file .= date("j-n-Y") ; /* get date now  */
$name_zip_file .='-user.zip'; 

$files_to_zip = directoryToArray('../public_html',true);
/*Export DB*/
backup_tables('db_host','user_db','user_db_pass','db_name');

/*Zip file*/
$result = create_zip($files_to_zip,$name_zip_file);

/*Transfer file via FTP */
$server = 'IP FTP';
$ftp_user_name="Username FTP";
$ftp_user_pass="Password FTP";
$connection = ftp_connect($server);
$login = ftp_login($connection, $ftp_user_name, $ftp_user_pass);
if (!$connection || !$login) { die('Connection attempt failed!'); }
$upload = ftp_put($connection, $name_zip_file,$name_zip_file, FTP_ASCII);
if (!$upload) { echo 'FTP upload failed!'; }
ftp_close($connection);

/*Delete file zip and sql after backup*/
$files_db = glob("*.sql");
foreach($files_db as $file_db) {
    if(is_file($file_db)) { 
        unlink($file_db);
    }
}
unlink($name_zip_file);

?>
