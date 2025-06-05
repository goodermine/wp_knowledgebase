<?php
/*
Template Name: Contact
*/

// It's highly recommended to move the JavaScript below to a separate .js file, 
// enqueue it using wp_enqueue_script, and pass translatable strings via wp_localize_script.
// For this example, the script remains inline for brevity but with a warning.
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('.btn-submit').click(function(e){
        e.preventDefault(); // Prevent default form submission to allow validation
        var $form = $(this).closest('form'); // More robust way to get the form
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var validationPassed = true;

        // Clear previous errors
        $('div.form-group', $form).removeClass('has-error');
        $('label.error', $form).remove();
        $('.form-control-feedback', $form).remove(); // Assuming Bootstrap-like feedback icons

        $('.required', $form).each(function(){
            var $input = $(this);
            var $parentGroup = $input.closest('.form-group');
            var inputVal = $input.val().trim();

            if(inputVal === ''){
                $parentGroup.addClass('has-error');
                $input.after('<label class="error help-block">This field is required.</label>'); // Translatable string needed
                validationPassed = false;
            } else if($input.hasClass('email') && !emailReg.test(inputVal)){
                $parentGroup.addClass('has-error');
                $input.after('<label class="error help-block">Enter a valid email address.</label>'); // Translatable string needed
                validationPassed = false;
            }
        });

        if (validationPassed) {
            $form.get(0).submit(); // Submit the native form element
        }
    });
});
</script>
<?php 
global $post; // $knowledgepress, $meta are not standard WP globals. Assumed from theme.
// Ensure Redux Framework is active or provide fallbacks
$contact_email_to_use = '';
$contact_subject_to_use = '';

if ( class_exists( 'ReduxFramework' ) ) { // Check if Redux is active
    // Ensure 'knowledgepress' is the correct Redux opt_name.
    // The following line assumes 'knowledgepress' is the Redux opt_name, 
    // and $meta contains specific fields for this page.
    // This is a common Redux pattern but might vary.
    // $meta = redux_post_meta( 'knowledgepress', get_the_ID() ); 
    // For safety, let's assume you have a function or direct Redux API call:
    // Example: $redux_options = get_option('knowledgepress_options'); // if 'knowledgepress_options' is opt_name
    // $contact_email_to_use = isset($redux_options['contact_page_email_to'][get_the_ID()]) ? $redux_options['contact_page_email_to'][get_the_ID()] : '';
    // This part is highly dependent on your Redux setup. For now, we'll try to use the provided meta logic with a check.
    if (function_exists('redux_post_meta')) {
        $meta = redux_post_meta( 'knowledgepress', get_the_ID() ); // Assuming 'knowledgepress' is the Redux opt_name
        $contact_email_to_use = isset($meta['contact_email']) ? $meta['contact_email'] : '';
        $contact_subject_to_use = isset($meta['contact_subject']) ? $meta['contact_subject'] : '';
    }
}


$emailSent     = false;
$hasError      = false;
$errorMessages = array();

// Define placeholder values for sticky form
$posted_name = '';
$posted_email = '';
$posted_comments = '';


if(isset($_POST['submitted'])) {
    // Verify nonce
    if ( !isset( $_POST['knowledge_contact_form_nonce'] ) || !wp_verify_nonce( $_POST['knowledge_contact_form_nonce'], 'knowledge_contact_form_action' ) ) {
        $errorMessages[] = __('Security check failed. Please try again.', 'knowledgepress'); // Use consistent text domain
        $hasError = true;
    } else {
        // Sanitize inputs
        $posted_name     = isset($_POST['contactName']) ? sanitize_text_field(trim($_POST['contactName'])) : '';
        $posted_email    = isset($_POST['email']) ? sanitize_email(trim($_POST['email'])) : '';
        $posted_comments = isset($_POST['comments']) ? sanitize_textarea_field(trim($_POST['comments'])) : '';

        // Server-side validation
        if ( empty($posted_name) ) {
            $errorMessages[] = __('Please enter your name.', 'knowledgepress');
            $hasError = true;
        }
        if ( !is_email( $posted_email ) ) {
            $errorMessages[] = __('Please enter a valid email address.', 'knowledgepress');
            $hasError = true;
        }
        if ( empty($posted_comments) ) {
            $errorMessages[] = __('Please enter your message.', 'knowledgepress');
            $hasError = true;
        }

        if ( !$hasError ) {
            $emailTo = $contact_email_to_use; 
            if ( empty($emailTo) || !is_email($emailTo) ){ // Validate Redux email
                $emailTo = get_option('admin_email');
            }
            
            $subject = $contact_subject_to_use;
            if ( empty($subject) ) { 
                // Sanitize name before using in subject if it comes from user input
                $subject = '[Contact Form] From ' . $posted_name; 
            }
            
            $body = "Name: $posted_name \n\nEmail: $posted_email \n\nComments: $posted_comments";
            // Headers: Ensure $posted_name and $posted_email are sanitized.
            $headers = array(
                'From: ' . $posted_name . ' <' . $posted_email . '>',
                'Reply-To: ' . $posted_email
            );
            
            if ( wp_mail($emailTo, $subject, $body, $headers) ) {
                $emailSent = true;
                // Clear form values after successful submission
                $posted_name = '';
                $posted_email = '';
                $posted_comments = '';
            } else {
                $errorMessages[] = __('There was a problem sending your email. Please try again later.', 'knowledgepress');
                $hasError = true;
            }
        }
    }
}
    
?>

<?php get_template_part('templates/content', 'page'); ?>

<?php if($emailSent) : ?>
    <div class="alert alert-success">
        <?php _e('Thank you, your email was sent successfully.', 'knowledgepress'); // Use consistent text domain ?>
    </div>
<?php else : ?>

    <?php if($hasError && !empty($errorMessages)) : ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach($errorMessages as $msg) : ?>
                    <li><?php echo esc_html($msg); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="row contact-form" action="<?php the_permalink(); ?>" method="post">
      <fieldset>
        <?php wp_nonce_field('knowledge_contact_form_action', 'knowledge_contact_form_nonce'); ?>
        <div class="col-sm-6 form-group">
          <label for="contactNameInput" class="sr-only"><?php _e( 'Name', 'knowledgepress' ); ?></label>
          <input type="text" name="contactName" id="contactNameInput" placeholder="<?php esc_attr_e( 'Name', 'knowledgepress' ); ?>" value="<?php echo esc_attr($posted_name);?>" class="required requiredField form-control input-lg">
        </div>
        <div class="col-sm-6 form-group">
          <label for="emailInput" class="sr-only"><?php _e( 'Email', 'knowledgepress' ); ?></label>
          <input type="text" name="email" id="emailInput" placeholder="<?php esc_attr_e( 'Email', 'knowledgepress' ); ?>" value="<?php echo esc_attr($posted_email);?>" class="required requiredField email form-control input-lg">
        </div>
        <div class="col-sm-12 form-group">
          <label for="commentsTextarea" class="sr-only"><?php _e( 'Message', 'knowledgepress' ); ?></label>
          <textarea name="comments" id="commentsTextarea" rows="7" placeholder="<?php esc_attr_e( 'Message', 'knowledgepress' ); ?>" cols="30" class="required requiredField form-control"><?php echo esc_textarea($posted_comments); ?></textarea>
        </div>
        <div class="col-sm-12">
            <input type="hidden" name="submitted" id="submitted" value="true" />
            <button type="submit" class="btn btn-primary btn-submit"><?php esc_html_e('Send Email', 'knowledgepress'); // Use consistent text domain ?></button>
        </div>
      </fieldset>
    </form>
<?php endif; ?>