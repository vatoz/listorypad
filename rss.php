<?php
header("Content-Type: text/xml; charset=UTF-8");
?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0"  xmlns:googleplay="http://www.google.com/schemas/play-podcasts/1.0" >
<channel>
<title>Listorypad</title>
<language>cs</language>
<copyright>Jednotliví autoři 2020</copyright>
<link>https://listorypad.eu</link>
<ttl>3600</ttl>
<description>Řada tvůrců každý den bere nové téma, novou výzvu. A pak vypráví příběh.</description>
<itunes:summary>Řada tvůrců každý den bere nové téma, novou výzvu. A pak vypráví příběh.</itunes:summary>
<itunes:category text="Performing Arts"/>
<image href="https://listorypad.eu/static/logo.png"/>
<itunes:image href="https://listorypad.eu/static/logo.png"/>
<googleplay:image href="https://listorypad.eu/static/logo.png"/>
<itunes:keywords>Listorypad, storytelling</itunes:keywords>
<itunes:author>Listorypad.eu a jednotliví autoři</itunes:author>
<googleplay:author>Listorypad.eu a jednotliví autoři</googleplay:author>
<itunes:explicit>No</itunes:explicit>
<itunes:owner>
<itunes:email>vasek@vasekcerny.cz</itunes:email>
</itunes:owner>
<googleplay:owner>vasek@vasekcerny.cz</googleplay:owner>
<?php
function xmlescape($str){
  return str_replace( array('"',"'","<",">","&"),
  array("&quot;","&apos;","&lt;","&gt;","&amp;"),$str);
}
function Render($Title,$Description,$Duration,$Date, $Url,$Filesize,$Author, $Mimetype, $Keywords=""){
  echo '<item>';
  echo '<title>'.xmlescape($Title).'</title>';
  $d=xmlescape($Description);
  echo '<description>'.$d.'</description>';
  echo '<itunes:subtitle>'.$d.'</itunes:subtitle>';
  echo '<itunes:summary>'.$d.'</itunes:summary>';
  echo '<itunes:duration>'.$Duration.'</itunes:duration>';
  echo '<pubDate>';
  $k=new DateTime($Date);
  echo $k->format(DateTime::RFC822);
  echo '</pubDate>';
  echo '<enclosure url="https://listorypad.eu/'.$Url.'" type="'.$Mimetype.'" length="'.$Filesize.'"/>';
  echo '<guid isPermaLink="false">https:/listorypad.eu/#'.$Url.'</guid>';
  echo '<link>https:/listorypad.eu/#'.$Url.'</link>';
  echo '<author>'.xmlescape($Author).'</author>';
  if(strlen($Keywords)){
      echo '<itunes:keywords>'.$Keywords.'</itunes:keywords>';
  }
  echo '</item>'."\n" ;

}


$posts=getPosts();
$users=getActiveUsers();
$topics=getTopics();
foreach($posts as $pid=>$post){
  Render(
    $post["name"],
    $users[$post['author']]['name']." vypráví v rámci Listorypadu na téma ".$topics[$post['topic']],
    $post['duration'],
    $post['moment'],
    $post['url'],
    $post['filesize'],
    $users[$post['author']]['name'],
    $post['mimetype'],
    "Listorypad, storytelling, vyprávění, ". $topics[$post['topic']]. (strpos($post["name"]," ")==false?", ".$post["name"]:"")
  );
}
foreach(getEditorials() as $post){
  Render(
  "Editorial - ".$post["name"],
  $post['transcript'],
  $post['duration'],
  $post['moment'],
  $post['url'],
  $post['filesize'],
  "listorypad.eu",
  $post['mimetype'],
  ""
);

}


?>
</channel>
</rss>
