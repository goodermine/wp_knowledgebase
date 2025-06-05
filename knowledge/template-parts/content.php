<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Knowledge
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if ( is_singular() ) : // This condition might be less relevant if content-single.php is always used for singular.
            the_title( '<h1 class="entry-title">', '</h1>' );
        else :
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
        endif;

        if ( 'post' === get_post_type() ) :
            ?>
            <div class="entry-meta">
                <?php
                knowledge_posted_on();
                knowledge_posted_by();
                ?>
            </div><?php endif; ?>
    </header><?php knowledge_post_thumbnail(); ?>

    <div class="entry-content">
        <?php
        the_excerpt(); // For archives/blog feeds
        /* Optional: Removed wp_link_pages based on suggestion, as excerpts typically aren't paginated.
        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'knowledge' ),
                'after'  => '</div>',
            )
        );
        */
        ?>
    </div><footer class="entry-footer">
        <?php knowledge_entry_footer(); ?>
        <?php if ( ! is_singular() ) : ?>
            <a href="<?php echo esc_url( get_permalink() ); ?>" class="read-more-link"><?php esc_html_e( 'Read More', 'knowledge' ); ?></a>
        <?php endif; ?>
    </footer></article>```

---

### File 2: `content-none.php`

**Purpose:** Displays a message when no posts are found for a given query (e.g., empty archive, no search results).

**Analysis:**

* **Structure & Semantics:** Uses `<section class="no-results not-found">` with a `<header class="page-header">` and `<h1>` for the title. `div.page-content` holds the conditional messages. This is semantically appropriate.
* **Title:** `esc_html_e( 'Nothing Found', 'knowledge' );` is correctly used.
* **Conditional Logic:**
    * `is_home() && current_user_can( 'publish_posts' )`: Provides a helpful link to create a new post. The link `admin_url( 'post-new.php' )` is correctly escaped with `esc_url()`. The translatable string uses `wp_kses()` to allow an `<a>` tag with an `href` attribute, which is the correct and secure way to include HTML in translatable strings.
    * `is_search()`: Displays a message for no search results and includes the search form via `get_search_form()`. `esc_html_e()` is used for the message.
    * `else` (default no content): Displays a generic message and includes `get_search_form()`. `esc_html_e()` is used.
* **Security:** All text output is properly internationalized and escaped (`esc_html_e()`, `wp_kses()`). URLs are escaped (`esc_url()`).
* **Accessibility:** Clear messages are provided. The structure is simple and accessible.
* **Internationalization:** Text domain 'knowledge' used correctly. `__( 'Ready to publish...', 'knowledge' )` with `wp_kses` is correctly formatted for translators.

**Categorized Findings (`content-none.php`):**

* No critical, high, or medium issues found. This file is well-implemented.

**Actionable Recommendations / Updated Code (`content-none.php`):**

No code changes are necessary. The file adheres to best practices.

```php
<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Knowledge
 */

?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'knowledge' ); ?></h1>
    </header><div class="page-content">
        <?php
        if ( is_home() && current_user_can( 'publish_posts' ) ) :

            printf(
                '<p>' . wp_kses(
                    /* translators: 1: Link to new post. */
                    __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'knowledge' ),
                    array(
                        'a' => array(
                            'href' => array(),
                        ),
                    )
                ) . '</p>',
                esc_url( admin_url( 'post-new.php' ) )
            );

        elseif ( is_search() ) :
            ?>

            <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'knowledge' ); ?></p>
            <?php
            get_search_form();

        else :
            ?>

            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'knowledge' ); ?></p>
            <?php
            get_search_form();

        endif;
        ?>
    </div></section>```

---

### File 3: `content-page.php`

**Purpose:** Displays the content for static pages (used by `page.php`).

**Analysis:**

* **Structure & Semantics:** Standard `<article>` structure with dynamic ID and classes. `<header>`, `div.entry-content`, and conditional `<footer>`.
* **Title Display:** `the_title( '<h1 class="entry-title">', '</h1>' );` is correctly used for page titles.
* **Post Thumbnail:** Calls `knowledge_post_thumbnail()`.
* **Content Display:** `the_content()` is used to display the full page content. `wp_link_pages()` is correctly included for handling multi-page content.
* **Edit Link:** The `edit_post_link()` is displayed conditionally using `if ( get_edit_post_link() )`. The link text includes screen reader text for better accessibility, `__( 'Edit <span class="screen-reader-text">%s</span>', 'knowledge' )`, and this string is correctly processed with `wp_kses()` to allow the `<span>` with a `class` attribute. `wp_kses_post( get_the_title() )` is used for the post title variable, which is appropriate in this context.
* **Security:** `the_title()`, `the_content()`, `edit_post_link()` handle their own escaping. `wp_kses()` and `wp_kses_post()` are used correctly.
* **Accessibility:** Semantic HTML. Screen reader text in the edit link is excellent.
* **Internationalization:** Text domain 'knowledge' used correctly.

**Categorized Findings (`content-page.php`):**

<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Knowledge
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
    </header><?php knowledge_post_thumbnail(); ?>

    <div class="entry-content">
        <?php