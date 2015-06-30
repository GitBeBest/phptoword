<?php
/***********************************************************************
Class:        phptoword
Version:      1.0
Date:         2015/06/30
Author:       
Description:  The class is used to export html to word
***********************************************************************/

class PHPToWord{
    public function excute() {
        $url = 'http://xxxxx';
        $content = file_get_contents($url);
        $fileContent = $this->getWordDocument($content);
        $filename = 'my.doc';
        ob_start();
        header("Content-Type:application/doc; charset=UTF-8");
        header("Content-Disposition: attachment; filename=$filename");
        echo $fileContent;
        ob_end_flush();
    }
    
    public function getWordDocument( $content , $absolutePath = "" , $isEraseLink = true ) {
        $mht = new MhtFileMaker();
        if ($isEraseLink)
            $content = preg_replace('/<a\s*.*?\s*>(\s*.*?\s*)<\/a>/i' , '$1' , $content);   //去掉链接

        $images = array();
        $files = array();
        $matches = array();
        //这个算法要求src后的属性值必须使用引号括起来
        if ( preg_match_all('/<img[.\n]*?src\s*?=\s*?[\"\'](.*?)[\"\'](.*?)\/>/i',$content ,$matches ) )
        {
            $arrPath = $matches[1];
            for ( $i=0;$i<count($arrPath);$i++)
            {
                $path = $arrPath[$i];
                $imgPath = trim( $path );
                if ( $imgPath != "" )
                {
                    $files[] = $imgPath;
                    if( substr($imgPath,0,7) == 'http://')
                    {
                        //绝对链接，不加前缀
                    }
                    else
                    {
                        $imgPath = $absolutePath.$imgPath;
                    }
                    $images[] = $imgPath;
                }
            }
         }
        $mht->AddContents("tmp.html",$mht->GetMimeType("tmp.html"),$content);

        for ( $i=0;$i<count($images);$i++)
        {
            $image = $images[$i];
            if ( @fopen($image , 'r') )
            {
                $imgcontent = @file_get_contents( $image );
                if ( $content )
                    $mht->AddContents($files[$i],$mht->GetMimeType($image),$imgcontent);
            }
            else
            {
                echo "file:".$image." not exist!<br />";
            }
         }
    
         return $mht->GetFile();
    }
}
?>
