# phptoword
php export word
I make this just for php export to word
First I just use MIME like follow:
  $filename = 'my.doc';
  $url = 'http://xxxx';
  $html = file_get_contents($url, 5);
  $filename = iconv("UTF-8", "GBK", $filename);
  ob_start();
  header("Content-Type:application/msword; charset=UTF-8");
  header("Content-Disposition: attachment; filename=$filename");
  echo $html;
  ob_end_flush();
  
but i found that when i modified the doc and save it,there is a .files folder bring out.
what is not i wanted.

so i searched this solution
