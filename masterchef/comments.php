<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * we will not load the comments
 */
if (post_password_required()) {
    return;
}

// Get language-based labels
$is_dutch = strpos(get_locale(), 'nl') !== false;

$comments_title = $is_dutch ? 'Observaties & Analyse' : 'Observations & Analysis';
$comments_closed_message = $is_dutch ? 'Observaties zijn gesloten.' : 'Observations are closed.';
$no_comments_message = $is_dutch ? 'Geen observaties beschikbaar voor dit experiment.' : 'No observations available for this experiment.';
$leave_comment_message = $is_dutch ? 'Documenteer uw observaties' : 'Document your observations';
$one_comment = $is_dutch ? '1 observatie' : '1 observation';
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <div class="comments-header section-header">
            <h3 class="comments-title">
                <?php
                $comments_number = get_comments_number();
                if ($comments_number === '1') {
                    echo esc_html($one_comment);
                } else {
                    echo sprintf(
                        $is_dutch ? '%d observaties' : '%d observations',
                        $comments_number
                    );
                }
                ?>
                <span class="scientific-counter">[n = <?php echo esc_html($comments_number); ?>]</span>
            </h3>
        </div>

        <div class="comments-list-container">
            <ul class="comment-list">
                <?php
                wp_list_comments(array(
                    'style'       => 'ul',
                    'short_ping'  => true,
                    'avatar_size' => 60,
                    'callback'    => 'masterchef_scientific_comment',
                ));
                ?>
            </ul>
            
            <?php
            // Are there comments to navigate through?
            if (get_comment_pages_count() > 1 && get_option('page_comments')) :
            ?>
            <nav class="comment-navigation" role="navigation">
                <div class="nav-previous"><?php previous_comments_link($is_dutch ? '← Oudere observaties' : '← Older observations'); ?></div>
                <div class="nav-next"><?php next_comments_link($is_dutch ? 'Nieuwere observaties →' : 'Newer observations →'); ?></div>
            </nav>
            <?php endif; ?>
        </div>

        <?php
        // If comments are closed and there are comments, show note
        if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
        ?>
        <p class="no-comments"><?php echo esc_html($comments_closed_message); ?></p>
        <?php endif; ?>

    <?php elseif (comments_open()) : ?>
        <div class="no-comments-yet">
            <p class="scientific-note"><?php echo esc_html($no_comments_message); ?></p>
        </div>
    <?php endif; ?>
    
    <?php if (!comments_open() && !is_user_logged_in()) : ?>
        <div class="comments-login-required">
            <p class="scientific-note">
                <?php 
                $login_url = wp_login_url(get_permalink());
                if ($is_dutch) {
                    echo sprintf(
                        'Je moet <a href="%s">ingelogd zijn</a> om observaties te documenteren.',
                        esc_url($login_url)
                    );
                } else {
                    echo sprintf(
                        'You must be <a href="%s">logged in</a> to document observations.',
                        esc_url($login_url)
                    );
                }
                ?>
            </p>
        </div>
    <?php endif; ?>

    <?php
    // Custom comment form
    $comment_form_args = array(
        'title_reply'          => $leave_comment_message,
        'title_reply_to'       => $is_dutch ? 'Reageer op %s' : 'Reply to %s',
        'cancel_reply_link'    => $is_dutch ? 'Annuleer reactie' : 'Cancel reply',
        'label_submit'         => $is_dutch ? 'Documenteer' : 'Document',
        'comment_notes_before' => '<p class="scientific-notice">' . ($is_dutch ? 'Uw email adres wordt niet gepubliceerd.' : 'Your email address will not be published.') . '</p>',
        'comment_field'        => '<div class="comment-form-comment">
                                    <label for="comment">' . ($is_dutch ? 'Observaties' : 'Observations') . '*</label>
                                    <textarea id="comment" name="comment" cols="45" rows="8" required="required"></textarea>
                                  </div>',
        'class_form'           => 'comment-form scientific-form',
        'class_submit'         => 'submit scientific-button',
    );
    
    comment_form($comment_form_args);
    ?>
</div>

<?php
// The masterchef_scientific_comment function is now defined in functions.php
?>