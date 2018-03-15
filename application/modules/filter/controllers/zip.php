<?php

//Mysql query to fetch file names
$result=mysql_query("select * from phphurdles_files");
//create an empty array
$file_names = array();
//fetch the names from database
while($row = mysql_fetch_array($result))
{
	$file_names[] = $row['image'];
}


function zipFilesAndDownload($file_names,$archive_file_name,$file_path)
{

	$zip = new ZipArchive();
	//create the file and throw the error if unsuccessful
	if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
	exit("cannot open &lt;$archive_file_name&gt;\n");
	}
	//add each files of $file_name array to archive
	foreach($file_names as $files)
	{
		$zip->addFile($file_path.$files,$files);
	}
	$zip->close();
	//then send the headers to download the zip file
	header("Content-type: application/zip");
	header("Content-Disposition: attachment; filename=$archive_file_name");
	header("Pragma: no-cache");
	header("Expires: 0");
	readfile("$archive_file_name");
	exit;
}