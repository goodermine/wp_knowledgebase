<?php
/**
 * Register sidebars and widgets
 */
function shoestrap_widgets_init() {
	$class        = '';
	$before_title = apply_filters( 'shoestrap_widgets_before_title', '<h3 class="widget-title">' );
	$after_title  = apply_filters( 'shoestrap_widgets_after_title', '</h3>' );

	// Sidebars
	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'knowledgepress' ),
		'id'            => 'sidebar-primary',
		'before_widget' => '<section id="%1$s" class="' . $class . 'widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => $before_title,
		'after_title'   => $after_title,
	));

	register_sidebar( array(
		'name'          => __( 'Secondary Sidebar', 'knowledgepress' ),
		'id'            => 'sidebar-secondary',
		'before_widget' => '<section id="%1$s" class="' . $class . 'widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => $before_title,
		'after_title'   => $after_title,
	));
}
add_action( 'widgets_init', 'shoestrap_widgets_init' );


/* ==========================================================================
   Custom recent post
   ========================================================================== */

class Custom_Recent_Posts_Widget extends WP_Widget {

  function __construct() {
      $widget_ops = array(
      'classname'   => 'widget_recent_entries',
      'description' => __('Display a list of recent post entries from one or more categories.', 'knowledgepress')
    );
      parent::__construct('custom-recent-posts', __('KP Recent Posts', 'knowledgepress'), $widget_ops);
  }


  function widget($args, $instance) {

      extract( $args );

      $title = apply_filters( 'widget_title', empty($instance['title']) ? 'Recent Posts' : $instance['title'], $instance, $this->id_base);

      if ( ! $number = absint( $instance['number'] ) ) $number = 5;

      if( ! $cats = $instance["cats"] )  $cats='';


      // array to call recent posts.

      $crpw_args=array(

        'showposts' => $number,

        'category__in'=> $cats,

       // 'orderby' => 'comment_count'

       // 'post_type' => 'faq'
        );

      $crp_widget = null;

      $crp_widget = new WP_Query($crpw_args);


      echo $before_widget;


      // Widget title

      echo $before_title;

      echo $instance["title"];

      echo $after_title;


      // Post list in widget

      echo "<ul>\n";

    while ( $crp_widget->have_posts() )

    {

      $crp_widget->the_post();


    ?>

      <li class="list-post pa-post-format">

        <?php echo post_icon(); ?><a  href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>

      </li>

    <?php

    }

     wp_reset_query();

    echo "</ul>\n";

    echo $after_widget;

  }

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
          $instance['cats'] = $new_instance['cats'];
    $instance['number'] = absint($new_instance['number']);

            return $instance;
  }


  function form( $instance ) {
    $title = isset($instance['title']) ? esc_attr($instance['title']) : 'Recent Posts';
    $number = isset($instance['number']) ? absint($instance['number']) : 5;

?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'knowledgepress'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>



        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'knowledgepress'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>


         <p>
            <label for="<?php echo $this->get_field_id('cats'); ?>"><?php _e('Select categories to include in the recent posts list:', 'knowledgepress');?>

                <?php
                   $categories=  get_categories('hide_empty=0');
                     echo "<br/>";
                     foreach ($categories as $cat) {
                         $option='<input type="checkbox" id="'. $this->get_field_id( 'cats' ) .'[]" name="'. $this->get_field_name( 'cats' ) .'[]"';
                            if(isset($instance['cats'])) {
                              if (is_array($instance['cats'])) {
                                  foreach ($instance['cats'] as $cats) {
                                      if($cats==$cat->term_id) {
                                           $option=$option.' checked="checked"';
                                      }
                                  }
                              }
                            }
                            $option .= ' value="'.$cat->term_id.'" />';

                            $option .= $cat->cat_name;

                            $option .= '<br />';
                            echo $option;
                         }

                    ?>
            </label>
        </p>

<?php
  }
}

function crpw_register_widgets() {
  register_widget( 'Custom_Recent_Posts_Widget' );
}

add_action( 'widgets_init', 'crpw_register_widgets' );


/* ==========================================================================
   Custom popular posts
   ========================================================================== */

class Custom_Popular_Posts_Widget extends WP_Widget {

  function __construct() {
      $widget_ops = array(
      'classname'   => 'widget_recent_entries',
      'description' => __('Display a list of most commented post entries from one or more categories.', 'knowledgepress')
    );
      parent::__construct('custom-popular-posts', __('KP Popular By Comments', 'knowledgepress'), $widget_ops);
  }


  function widget($args, $instance) {

      extract( $args );

      $title = apply_filters( 'widget_title', empty($instance['title']) ? 'Popular Posts' : $instance['title'], $instance, $this->id_base);

      if ( ! $number = absint( $instance['number'] ) ) $number = 5;

      if( ! $cats = $instance["cats"] )  $cats='';


      // array to call recent posts.

      $cppw_args=array(

        'showposts' => $number,

        'category__in'=> $cats,

        'orderby' => 'comment_count'

       // 'post_type' => 'faq'
        );

      $cpp_widget = null;

      $cpp_widget = new WP_Query($cppw_args);


      echo $before_widget;


      // Widget title

      echo $before_title;

      echo $instance["title"];

      echo $after_title;


      // Post list in widget

      echo "<ul>\n";

    while ( $cpp_widget->have_posts() )

    {

      $cpp_widget->the_post();


    ?>

      <li class="list-post pa-post-format">

        <?php echo post_icon(); ?><a  href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>

      </li>

    <?php

    }

     wp_reset_query();

    echo "</ul>\n";

    echo $after_widget;

  }

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
          $instance['cats'] = $new_instance['cats'];
    $instance['number'] = absint($new_instance['number']);

            return $instance;
  }


  function form( $instance ) {
    $title = isset($instance['title']) ? esc_attr($instance['title']) : 'Popular Posts';
    $number = isset($instance['number']) ? absint($instance['number']) : 5;

?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'knowledgepress'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>



        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'knowledgepress'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>


         <p>
            <label for="<?php echo $this->get_field_id('cats'); ?>"><?php _e('Select categories to include in the popular posts list:', 'knowledgepress');?>

                <?php
                   $categories=  get_categories('hide_empty=0');
                     echo "<br/>";
                     foreach ($categories as $cat) {
                         $option='<input type="checkbox" id="'. $this->get_field_id( 'cats' ) .'[]" name="'. $this->get_field_name( 'cats' ) .'[]"';
                            if(isset($instance['cats'])) {
                              if (is_array($instance['cats'])) {
                                  foreach ($instance['cats'] as $cats) {
                                      if($cats==$cat->term_id) {
                                           $option=$option.' checked="checked"';
                                      }
                                  }
                              }
                            }
                            $option .= ' value="'.$cat->term_id.'" />';

                            $option .= $cat->cat_name;

                            $option .= '<br />';
                            echo $option;
                         }

                    ?>
            </label>
        </p>

<?php
  }
}

function cppw_register_widgets() {
  register_widget( 'Custom_Popular_Posts_Widget' );
}

add_action( 'widgets_init', 'cppw_register_widgets' );


/* ==========================================================================
   Twitter
   ========================================================================== */

add_action( 'widgets_init', function(){
	register_widget( 'pressapps_tweet_widget' );
});
// add_action( 'widgets_init', 'pressapps_tweets_widgets' );
//
// function pressapps_tweets_widgets() {
//   register_widget( '__construct' );
// }

class pressapps_tweet_widget extends WP_Widget {

  function __construct() {

    $widget_ops = array( 'classname' => 'pressapps_tweet_widget', 'description' => __('A widget that displays your latest tweets.', 'knowledgepress' ) );
    $control_ops = array( 'width' => 220, 'height' => 350, 'id_base' => 'pressapps_tweet_widget' );
    parent::__construct( 'pressapps_tweet_widget', __('KP Twitter', 'knowledgepress' ), $widget_ops, $control_ops );
  }

  function hyperlink_callback($matches){

    if(count($matches)>0){
        return '<a href="' . $matches[0] . '">' . $matches[0] . '</a>';
    }

  }

  function username_callback($matches){
        if(count($matches)>0){
            return '<a href="http://twitter.com/' . $matches[1] . '">' . $matches[0] . '</a>';
        }
    }



  function widget( $args, $instance ) {
    extract( $args );

    $title = apply_filters('widget_title', $instance['title'] );
    $pressapps_twitter_username = $instance['username'];
    $pressapps_twitter_postcount = $instance['postcount'];
    $tweettext = $instance['tweettext'];

    if(empty($instance['consumer_key']) || empty($instance['consumer_secret']))
        return ;

    require_once( 'twitter-api.php' );

    $credentials = array(
        'consumer_key'    =>  $instance['consumer_key'],
        'consumer_secret' =>  $instance['consumer_secret']
    );

    $twitter_api = new Wp_Twitter_Api( $credentials );

    $query      = "count={$pressapps_twitter_postcount}&include_entities=true&include_rts=true&screen_name={$pressapps_twitter_username}";
    $result     = $twitter_api->query( $query );
    echo $before_widget;

    if ( $title ) { echo $before_title . $title . $after_title; }

    $id = rand(0,999);

    if(count($result)>0){
    ?>
        <ul id="twitter_update_list_<?php echo $id; ?>" class="twitter">
            <?php

                for($i=0;$i<count($result);$i++){
                    if(empty($result[$i]->text))
                        continue;
                    /**
                     * Linking the Hyper Link
                     */
                    $result[$i]->text = preg_replace_callback("((https?|s?ftp|ssh)\:\/\/[^\"\s\<\>]*[^.,;'\">\:\s\<\>\)\]\!])",array($this,'hyperlink_callback'),$result[$i]->text);
                    /**
                     * Linking the User
                     */
                    $result[$i]->text = preg_replace_callback("/\B@([_a-z0-9]+)/i",array($this,'username_callback'),$result[$i]->text);
                    ?>
                    <li>
                        <span><i class="kp-twitter"></i> <?php echo $result[$i]->text; ?></span>
                    </li>
                    <?php
                }

            ?>
        </ul>
        <?php
    }
    ?>
    <?php if( !empty($tweettext) ) { ?>
        <a href="http://twitter.com/<?php echo $pressapps_twitter_username; ?>" class="twitter-link"><?php echo $tweettext; ?></a>
    <?php } ?>

    <?php

    echo $after_widget;
  }

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    $instance['title']              = strip_tags( $new_instance['title'] );
    $instance['username']           = strip_tags( $new_instance['username'] );
    $instance['postcount']          = strip_tags( $new_instance['postcount'] );
    $instance['tweettext']          = strip_tags( $new_instance['tweettext'] );
    $instance['consumer_key']       = strip_tags( $new_instance['consumer_key'] );
    $instance['consumer_secret']    = strip_tags( $new_instance['consumer_secret'] );

    return $instance;
  }

  function form( $instance ) {

    $defaults = array(
        'title'             => 'Latest Tweets',
        'username'          => '',
        'postcount'         => '4',
        'tweettext'         => 'Follow Us on Twitter',
        'consumer_key'      => '',
        'consumer_secret'   => '',
    );
    $instance = wp_parse_args( (array) $instance, $defaults );

    ?>

    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'knowledgepress' ) ?></label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e('Twitter Username:', 'knowledgepress' ) ?></label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'postcount' ); ?>"><?php _e('Number of tweets (maximum 20)', 'knowledgepress' ) ?></label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'postcount' ); ?>" name="<?php echo $this->get_field_name( 'postcount' ); ?>" value="<?php echo $instance['postcount']; ?>" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'tweettext' ); ?>"><?php _e('Follow Us on Twitter Text', 'knowledgepress' ) ?></label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'tweettext' ); ?>" name="<?php echo $this->get_field_name( 'tweettext' ); ?>" value="<?php echo $instance['tweettext']; ?>" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'consumer_key' ); ?>"><?php _e('Consumer Key', 'knowledgepress' ) ?></label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'consumer_key' ); ?>" name="<?php echo $this->get_field_name( 'consumer_key' ); ?>" value="<?php echo $instance['consumer_key']; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'consumer_secret' ); ?>"><?php _e('Consumer Secret', 'knowledgepress' ) ?></label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'consumer_secret' ); ?>" name="<?php echo $this->get_field_name( 'consumer_secret' ); ?>" value="<?php echo $instance['consumer_secret']; ?>" />
    </p>

  <?php
  }
}


/**
 * Most popular widget by page views
 */
class Most_Popular_Widget extends WP_Widget {
  public function __construct() {
    parent::__construct( 'most_popular_widget', 'KP Popular By Views', array( 'description' => 'Display your most popular articles by views.' ) );
  }

  public function form( $instance ) {
    $defaults = $this->default_options( $instance );
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label><br />
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $defaults['title']; ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'number' ); ?>">Number of posts to show:</label><br />
      <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $defaults['number']; ?>" size="3">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'timeline' ); ?>">Timeline:</label><br />
      <select id="<?php echo $this->get_field_id( 'timeline' ); ?>" name="<?php echo $this->get_field_name( 'timeline' ); ?>">
        <option value="all_time"<?php if ( $defaults['timeline'] == 'all_time' ) echo "selected"; ?>>All time</option>
        <option value="monthly"<?php if ( $defaults['timeline'] == 'monthly' ) echo "selected"; ?>>Past month</option>
        <option value="weekly"<?php if ( $defaults['timeline'] == 'weekly' ) echo "selected"; ?>>Past week</option>
        <option value="daily"<?php if ( $defaults['timeline'] == 'daily' ) echo "selected"; ?>>Today</option>
      </select>
    </p>
    <?php
  }

  private function default_options( $instance ) {
    if ( isset( $instance[ 'title' ] ) )
      $options['title'] = esc_attr( $instance[ 'title' ] );
    else
      $options['title'] = 'Popular posts';

    if ( isset( $instance[ 'number' ] ) )
      $options['number'] = (int) $instance[ 'number' ];
    else
      $options['number'] = 5;

    if ( isset( $instance[ 'timeline' ] ) )
      $options['timeline'] = esc_attr( $instance[ 'timeline' ] );
    else
      $options['timeline'] = 'all_time';

    return $options;
  }

  public function update( $new, $old ) {
    $instance = wp_parse_args( $new, $old );
    return $instance;
  }

  public function widget( $args, $instance ) {
    // Find default args
    extract( $args );

    // Get our posts
    $defaults     = $this->default_options( $instance );
    $options['limit'] = (int) $defaults[ 'number' ];
    $options['range'] = $defaults['timeline'];
    $options['post_type'] = 'post';

    $posts = pa_get_popular( $options );

    // Display the widget
    echo $before_widget;
    if ( $defaults['title'] ) echo $before_title . $defaults['title'] . $after_title;
    echo '<ul>';
    global $post;
    foreach ( $posts as $post ):
      setup_postdata( $post );
      ?>
      <li class="list-post pa-post-format"><?php echo post_icon(); ?><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></li>
      <?php
    endforeach;
    echo '</ul>';
    echo $after_widget;

    // Reset post data
    wp_reset_postdata();
  }
}


function pa_most_popular_widget() {
  register_widget( 'Most_Popular_Widget' );
}

add_action( 'widgets_init', 'pa_most_popular_widget' );



/**
 * Most popular widget by votes
 */
class Most_voted_Widget extends WP_Widget {
  public function __construct() {
    parent::__construct( 'most_voted_widget', 'KP Popular By Votes', array( 'description' => 'Display your most popular articles by votes.' ) );
  }

  public function form( $instance ) {
    $defaults = $this->default_options( $instance );
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label><br />
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $defaults['title']; ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'number' ); ?>">Number of posts to show:</label><br />
      <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $defaults['number']; ?>" size="3">
    </p>
    <?php
  }

  private function default_options( $instance ) {
    if ( isset( $instance[ 'title' ] ) )
      $options['title'] = esc_attr( $instance[ 'title' ] );
    else
      $options['title'] = 'Popular posts';

    if ( isset( $instance[ 'number' ] ) )
      $options['number'] = (int) $instance[ 'number' ];
    else
      $options['number'] = 5;

    return $options;
  }

  public function update( $new, $old ) {
    $instance = wp_parse_args( $new, $old );
    return $instance;
  }

  public function widget( $args, $instance ) {
    // Find default args
    extract( $args );

    // Get our posts
    $defaults     = $this->default_options( $instance );
    $options['limit'] = (int) $defaults[ 'number' ];





    $args=array(
      'orderby' => 'meta_value_num',
      'order'   => 'DESC',
      'meta_key'  => '_votes_likes',
      'post_type' => 'post',
      'posts_per_page' => $options['limit'],
    );

    $most_voted = null;
    $most_voted = new WP_Query($args);






    // Display the widget
    echo $before_widget;
    if ( $defaults['title'] ) echo $before_title . $defaults['title'] . $after_title;
    echo '<ul>';

    while ( $most_voted->have_posts() )  {
      $most_voted->the_post();
      ?>
      <li class="list-post pa-post-format"><?php echo post_icon(); ?><a  href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
    <?php }

    echo '</ul>';
    echo $after_widget;

    // Reset post data
    wp_reset_query();
  }
}

function pa_most_voted_widget() {
  register_widget( 'Most_Voted_Widget' );
}

add_action( 'widgets_init', 'pa_most_voted_widget' );
