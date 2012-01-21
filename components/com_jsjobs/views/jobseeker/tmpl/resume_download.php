<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	views/application/tmpl/resumedownoad.php
 ^ 
 * Description: template view for a employment application
 ^ 
 * History:		NONE
 ^ 
 */

 $data = $this->application->filecontent;
  $name = $this->application->filename;
  $size = $this->application->filesize;
  $type = $this->application->filetype;
  header("Content-type: $type");
  header("Content-length: $size");
  header("Content-Disposition: attachment; filename=$name");
  echo $this->application->filecontent;	
?>