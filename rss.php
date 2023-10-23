<?php
header("Content-Type: text/xml; charset=UTF-8");
?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0"  xmlns:googleplay="http://www.google.com/schemas/play-podcasts/1.0" >
<channel>
<title>Listorypad</title>
<language>cs</language>
<copyright>Jednotliví autoři 2020-2021</copyright>
<link>https://listorypad.vasekcerny.cz</link>
<ttl>3600</ttl>
<description>Řada tvůrců každý den či týden bere nové téma, novou výzvu. A pak vypráví příběh. </description>
<itunes:summary>Řada tvůrců každý den či týden bere nové téma, novou výzvu. A pak vypráví příběh.</itunes:summary>
<itunes:category text="Performing Arts"/>
<image href="https://listorypad.vasekcerny.cz/static/logo.png?v2"/>
<itunes:image href="https://listorypad.vasekcerny.cz/static/logo.png?v2"/>
<googleplay:image href="https://listorypad.vasekcerny.cz/static/logo.png?v2"/>
<itunes:keywords>Listorypad, storytelling</itunes:keywords>
<itunes:author>Listorypad jednotliví autoři</itunes:author>
<googleplay:author>Listorypad a jednotliví autoři</googleplay:author>
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

  echo '<item>'."\n";
  echo '<title>'.xmlescape($Title).'</title>'."\n";
  $d=xmlescape($Description);
  echo '<description>'.$d.'</description>'."\n";
  echo '<itunes:subtitle>'.$d.'</itunes:subtitle>'."\n";
  echo '<itunes:summary>'.$d.'</itunes:summary>'."\n";
  echo '<itunes:duration>'.$Duration.'</itunes:duration>'."\n";
  echo '<pubDate>';
  $k=new DateTime($Date);
  echo $k->format(DateTime::RFC822);
  echo '</pubDate>'."\n";
  echo '<enclosure url="https://listorypad.vasekcerny.cz/'.$Url.'" type="'.$Mimetype.'" length="'.$Filesize.'"/>'."\n";
  echo '<guid isPermaLink="false">https:/listorypad.vasekcerny.cz/#'.$Url.'</guid>'."\n";
  echo '<link>https:/listorypad.vasekcerny.cz/#'.$Url.'</link>'."\n";
  echo '<author>'.xmlescape($Author).'</author>'."\n";
  if(strlen($Keywords)){
      echo '<itunes:keywords>'.$Keywords.'</itunes:keywords>'."\n";
  }
  echo '</item>'."\n"."\n" ;

}


$posts=getPosts();
$users=getUsers();
$events=getEvents();
$topics=getTopics();
foreach($posts as $pid=>$post){
  Render(
    $post["name"],
    $users[$post['author']]['name']." vypráví v rámci akce ".$events[$post["event"]]["name"]   ." na téma ".$topics[$post['topic']],
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
  "listorypad.vasekcerny.cz",
  $post['mimetype'],
  ""
);

}


?>
</channel>
</rss>
