<?php
function knowledge_theme_scripts() {
  // Enqueue main stylesheet
  wp_enqueue_style(
    'knowledge-main',
    get_template_directory_uri() . '/assets/css/main.css',
    [],
    wp_get_theme()->get('Version')
  );

  // Editor styles (optional)
  wp_enqueue_style(
    'knowledge-editor',
    get_template_directory_uri() . '/assets/css/editor-style.css',
    ['knowledge-main'],
    wp_get_theme()->get('Version')
  );

  // Print stylesheet
  wp_enqueue_style(
    'knowledge-print',
    get_template_directory_uri() . '/assets/css/print.css',
    [],
    wp_get_theme()->get('Version'),
    'print'
  );

  // Enqueue JS
  wp_enqueue_script(
    'knowledge-scripts',
    get_template_directory_uri() . '/assets/js/scripts.js',
    ['jquery'],
    filemtime(get_template_directory() . '/assets/js/scripts.js'),
    true
  );
}
add_action('wp_enqueue_scripts', 'knowledge_theme_scripts');
