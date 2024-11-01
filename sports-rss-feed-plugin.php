<?php
/*
Plugin Name: Sports News Rss Feed
Plugin URI: http://www.guyro.com/sports-news-rss-plugin
Description: Adds a customizable widget which displays the latest sports related news from all over the world.
Version: 1.0
Author: Guy Roman
Author URI: http://www.guyro.com
License: GPL3
*/

function sportsnews()
{
  $options = get_option("widget_sportsnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Sports News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Feed 
  $rss = simplexml_load_file( 
  'http://sports.yahoo.com/top/rss.xml'); 
  ?> 
  
  <ul> 
  
  <?php 
  // max number of news slots, with 0 (zero) all display
  $max_news = $options['news'];
  // maximum length to which a title may be reduced if necessary,
  $max_length = $options['chars'];
  
  // RSS Elements 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Title
    $title = $i->title;
    // Length of title
    $length = strlen($title);
    // if the title is longer than the previously defined maximum length,
    // it'll he shortened and "..." added, or it'll output normaly
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_sportsnews($args)
{
  extract($args);
  
  $options = get_option("widget_sportsnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Sports News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  sportsnews();
  echo $after_widget;
}

function sportsnews_control()
{
  $options = get_option("widget_sportsnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Sports News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['sportsnews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['sportsnews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['sportsnews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['sportsnews-CharCount']);
    update_option("widget_sportsnews", $options);
  }
?> 
  <p>
    <label for="sportsnews-WidgetTitle">Widget Title: </label>
    <input type="text" id="sportsnews-WidgetTitle" name="sportsnews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="sportsnews-NewsCount">Max. News: </label>
    <input type="text" id="sportsnews-NewsCount" name="sportsnews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="sportsnews-CharCount">Max. Characters: </label>
    <input type="text" id="sportsnews-CharCount" name="sportsnews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="sportsnews-Submit"  name="sportsnews-Submit" value="1" />
  </p>
  
<?php
}

function sportsnews_init()
{
  register_sidebar_widget(__('Sports News'), 'widget_sportsnews');    
  register_widget_control('Sports News', 'sportsnews_control', 300, 200);
}
add_action("plugins_loaded", "sportsnews_init");
?>