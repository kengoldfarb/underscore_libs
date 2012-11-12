<?php
/*
 * http://php.net/manual/en/function.fopen.php
 * 
 * 'r'	 Open for reading only; place the file pointer at the beginning of the file.
'r+'	 Open for reading and writing; place the file pointer at the beginning of the file.
'w'	 Open for writing only; place the file pointer at the beginning of the file and truncate the file to zero length. If the file does not exist, attempt to create it.
'w+'	 Open for reading and writing; place the file pointer at the beginning of the file and truncate the file to zero length. If the file does not exist, attempt to create it.
'a'	 Open for writing only; place the file pointer at the end of the file. If the file does not exist, attempt to create it.
'a+'	 Open for reading and writing; place the file pointer at the end of the file. If the file does not exist, attempt to create it.
'x'	 Create and open for writing only; place the file pointer at the beginning of the file. If the file already exists, the fopen() call will fail by returning FALSE and generating an error of level E_WARNING. If the file does not exist, attempt to create it. This is equivalent to specifying O_EXCL|O_CREAT flags for the underlying open(2) system call.
'x+'	 Create and open for reading and writing; otherwise it has the same behavior as 'x'.
'c'	 Open the file for writing only. If the file does not exist, it is created. 
 * If it exists, it is neither truncated (as opposed to 'w'), nor the call to this function fails 
 * (as is the case with 'x'). The file pointer is positioned on the beginning of the file. This may be useful 
 * if it's desired to get an advisory lock (see flock()) before attempting to modify the file, as using 'w' could 
 * truncate the file before the lock was obtained (if truncation is desired, ftruncate() can be used after the lock is requested).
'c+'	 Open the file for reading and writing; otherwise it has the same behavior as 'c'.
 */
define('_FILE_READ_ONLY', 'r');
define('_FILE_READ_WRITE', 'r+');
define('_FILE_WRITING_ONLY_CREATE', 'w');
define('_FILE_READ_WRITE_TRUNCATE_CREATE', 'w+');
define('_FILE_WRITE_ONLY_END_OF_FILE_CREATE', 'a');
define('_FILE_READ_WRITE_END_OF_FILE_CREATE', 'a+');
define('_FILE_WRITE_ONLY_BEGIN_OF_FILE', 'x');
define('_FILE_READ_WRITE_BEGIN_OF_FILE', 'x+');
define('_FILE_WRITE_ONLY_NO_TRUNCATE_BEGIN_OF_FILE', 'c');
define('_FILE_READ_WRITE_NO_TRUNCATE_BEGIN_OF_FILE', 'c+');
define('_FILE_TMP', '_FILE_CREATE_TEMPORARY');


?>