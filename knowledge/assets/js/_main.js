/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can 
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function($) {

// Use this variable to set up the common and page specific functions. If you 
// rename this variable, you will also need to rename the namespace below.
var Shoestrap = {
  // All pages
  common: {
    init: function() {
      // JavaScript to be fired on all pages
      
      // Offcanvas dropdown effect
      var $offCanvas = $('#offcanvas'),
          $dropdown  = $offCanvas.find('.dropdown');
      $dropdown.on('show.bs.dropdown', function() {
          $(this).find('.dropdown-menu').slideDown(350);
      }).on('hide.bs.dropdown', function(){
          $(this).find('.dropdown-menu').slideUp(350);
      });
      
      $(".entry-content").fitVids();
      $(".fitvids").fitVids();
      //$(".dslc-module-DSLC_Html").fitVids();
      
      $('.flexslider').flexslider({
        animation: "fade",              //String: Select your animation type, "fade" or "slide"
        slideshowSpeed: 7000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
        animationSpeed: 600,            //Integer: Set the speed of animations, in milliseconds
        controlNav: false,              //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
        directionNav: true,             //Boolean: Create navigation for previous/next navigation? (true/false)
        // smoothHeight: false,         // This line was redundant
        smoothHeight: true,             //{NEW} Boolean: Allow height of the slider to animate smoothly in horizontal mode  
      });

      /**
       * Live search
       */
      // Check if Autocomplete plugin is loaded
      if ($.Autocomplete) {
        jQuery.Autocomplete.prototype.suggest = function () {
          
            if (this.suggestions.length === 0) {
                this.hide();
                return;
            }

            var that = this,
                formatResult = that.options.formatResult,
                value = that.getQuery(that.currentValue),
                className = that.classes.suggestion,
                classSelected = that.classes.selected,
                container = $(that.suggestionsContainer),
                html = '';
            // Build suggestions inner HTML
            $.each(that.suggestions, function (i, suggestion) {
                //html += '<div class="' + className + suggestion.css + '" data-index="' + i + '"><p class="ls-'+suggestion.type_color+'">'+suggestion.type_label+'</p> <h4>'+suggestion.icon + formatResult(suggestion, value) + '</h4></div>';
                html += '<div class="' + className + (suggestion.css ? ' ' + suggestion.css : '') + '" data-index="' + i + '">' + (suggestion.icon || '') + '<h4>' + formatResult(suggestion, value) + '</h4></div>';
            });

            container.html(html).show();
            that.visible = true;

            // Select first value by default:
            if (that.options.autoSelectFirst) {
                that.selectedIndex = 0;
                container.children().first().addClass(classSelected);
            }
        };
      
        // Initialize ajax autocomplete:
        // Ensure 'siteAjax.ajaxurl' is localized from PHP (e.g., via wp_localize_script)
        if (typeof siteAjax !== 'undefined' && siteAjax.ajaxurl) {
            $('.searchajax').autocomplete({
                serviceUrl: siteAjax.ajaxurl, // Using localized ajaxurl
                params: {'action':'search_title'}, // This is the WordPress action hook
                minChars: 1,
                maxHeight: 450,
                onSelect: function(suggestion) {
                //  $('#content').html('<h2>Redirecting ... </h2>');
                    window.location = suggestion.data.url;
                }
            });
        } else {
            console.warn('Live Search: siteAjax.ajaxurl is not defined. AJAX search may not work.');
        }
      }
      
    }
  },
  // Home page
  home: {
    init: function() {
      // JavaScript to be fired on the home page
    }
  },
  // About us page, note the change from about-us to about_us.
  about_us: {
    init: function() {
      // JavaScript to be fired on the about us page
    }
  },
  // Single post page
  single_post: {
    init: function() {
      // Check if TOC plugin is loaded
      if ($.fn.toc) {
        $("#toc").toc();

        $( '.toc a' ).each( function () {
          var destination = '';
          $( this ).click( function( e ) {
            e.preventDefault();
            // go_to_elm = true; // Ensure go_to_elm is defined if used, or handle state differently
            var elementClicked = $( this ).attr( 'href' );
            var $targetElement = jQuery( 'body' ).find( elementClicked );
            if ($targetElement.length) {
                var elementOffset = $targetElement.offset();
                destination = elementOffset.top;
                jQuery( 'html,body' ).animate( { scrollTop: destination - 80 }, 300 );
                /*setTimeout(function(){
                    go_to_elm = false; // Ensure go_to_elm is defined if used
                }, 800);*/
            }
          } );
        });
      }

      // Voting
      // Ensure PAAV.base_url (pointing to admin-ajax.php) and PAAV.nonce are localized from PHP
      if (typeof PAAV !== 'undefined' && PAAV.base_url) {
          jQuery('a.like-btn').click(function(e){
              e.preventDefault();
              var $thisButton = jQuery(this);
              var post_id = $thisButton.data('post-id'); // Using data-post-id attribute
              var response_div = $thisButton.closest('.vote-response-area'); // Adjust this selector if needed

              jQuery.ajax({
                  url: PAAV.base_url,
                  type: 'POST',
                  data: {
                      action: 'knowledge_vote_like', // WordPress AJAX action for liking
                      post_id: post_id,
                      // security: PAAV.nonce, // Nonce should be localized and sent
                  },
                  beforeSend: function() {
                      $thisButton.prop('disabled', true);
                  },
                  success: function(data) {
                      response_div.hide().html(data).fadeIn(400);
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                      console.error("Vote like failed: " + textStatus, errorThrown);
                      response_div.html('<p class="text-danger">Voting failed. Please try again.</p>').fadeIn(400);
                  },
                  complete: function() {
                      $thisButton.prop('disabled', false);
                  }
              });
          });
          
          jQuery('a.dislike-btn').click(function(e){
              e.preventDefault();
              var $thisButton = jQuery(this);
              var post_id = $thisButton.data('post-id'); // Using data-post-id attribute
              var response_div = $thisButton.closest('.vote-response-area'); // Adjust this selector if needed

              jQuery.ajax({
                  url: PAAV.base_url,
                  type: 'POST',
                  data: {
                      action: 'knowledge_vote_dislike', // WordPress AJAX action for disliking
                      post_id: post_id,
                      // security: PAAV.nonce, // Nonce should be localized and sent
                  },
                  beforeSend: function() {
                      $thisButton.prop('disabled', true);
                  },
                  success: function(data) {
                      response_div.hide().html(data).fadeIn(400);
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                      console.error("Vote dislike failed: " + textStatus, errorThrown);
                      response_div.html('<p class="text-danger">Voting failed. Please try again.</p>').fadeIn(400);
                  },
                  complete: function() {
                      $thisButton.prop('disabled', false);
                  }
              });
          });
      } else {
          console.warn('Voting system: PAAV object with base_url (and nonce) is not defined.');
      }
    }
  }
};

// The routing fires all common scripts, followed by the page specific scripts.
// Add additional events for more control over timing e.g. a finalize event
var UTIL = {
  fire: function(func, funcname, args) {
    var namespace = Shoestrap;
    funcname = (funcname === undefined) ? 'init' : funcname;
    if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
      namespace[func][funcname](args);
    }
  },
  loadEvents: function() {
    UTIL.fire('common');

    $.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
      UTIL.fire(classnm);
    });
  }
};

$(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.