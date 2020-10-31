<?php
header("Content-Type: text/xml; charset=UTF-8");
?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">
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
<itunes:keywords>Listorypad, storytelling</itunes:keywords>
<itunes:author>Listorypad.eu a jednotliví autoři</itunes:author>
<itunes:explicit>No</itunes:explicit>
<itunes:owner>
<itunes:email>vasek@vasekcerny.cz</itunes:email>
</itunes:owner>
<?php
function xmlescape($str){
  return str_replace( array('"',"'","<",">","&"),
  array("&quot;","&apos;","&lt;","&gt;","&amp;"),$str);
}
$posts=getPosts();
$users=getActiveUsers();
$topics=getTopics();
foreach($posts as $pid=>$post){
  echo '<item>';
  echo '<title>'.xmlescape($post["name"]). ' ('.$users[$post['author']]['name']. ' vypráví na téma '.$topics[$post['topic']].')</title>';
  $d=xmlescape($users[$post['author']]['name']." vypráví v rámci listorypadu na téma ".$topics[$post['topic']]);
  echo '<description>'.$d.'</description>';
  echo '<itunes:subtitle>'.$d.'</itunes:subtitle>';
  echo '<itunes:summary>'.$d.'</itunes:summary>';
  echo '<itunes:duration>'.$post['duration'].'</itunes:duration>';
  echo '<pubDate>'.$post['date'].'</pubDate>';
  echo '<enclosure url="https://listorypad.eu/'.$post['url'].'" type="audio/mpeg" length="33912640"/>';
  echo '<guid isPermaLink="false">https:/listorypad.eu/#'.$post['url'].'</guid>';
  echo '<link>https:/listorypad.eu/#'.$post['url'].'</link>';
  echo '</item>'."\n" ;
}
?>
</channel>
</rss>
