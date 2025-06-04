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
/**
 * Scientific comment callback function
 */
if (!function_exists('masterchef_scientific_comment')) :
    function masterchef_scientific_comment($comment, $args, $depth) {
        $tag = ('div' === $args['style']) ? 'div' : 'li';
        $is_dutch = strpos(get_locale(), 'nl') !== false;
        ?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class('scientific-comment'); ?>>
            <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
                <div class="comment-meta scientific-metadata">
                    <div class="comment-author">
                        <?php
                        if (0 != $args['avatar_size']) {
                            echo '<div class="scientific-avatar">';
                            echo get_avatar($comment, $args['avatar_size']);
                            echo '</div>';
                        }
                        ?>
                        <div class="scientific-author-info">
                            <span class="scientific-author-name"><?php echo get_comment_author_link(); ?></span>
                            <span class="scientific-timestamp">
                                <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                                    <time datetime="<?php comment_time('c'); ?>">
                                        <?php
                                        printf(
                                            $is_dutch ? '%1$s om %2$s' : '%1$s at %2$s',
                                            get_comment_date(),
                                            get_comment_time()
                                        );
                                        ?>
                                    </time>
                                </a>
                                <span class="scientific-id">#<?php echo esc_html(substr(md5($comment->comment_ID . $comment->comment_author), 0, 8)); ?></span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="comment-content scientific-data">
                    <?php comment_text(); ?>
                </div>

                <div class="comment-metadata scientific-actions">
                    <?php
                    edit_comment_link(
                        $is_dutch ? 'Wijzig' : 'Edit',
                        '<span class="scientific-edit">',
                        '</span>'
                    );
                    ?>
                    <?php
                    comment_reply_link(
                        array_merge(
                            $args,
                            array(
                                'add_below' => 'div-comment',
                                'depth'     => $depth,
                                'max_depth' => $args['max_depth'],
                                'before'    => '<span class="scientific-reply">',
                                'after'     => '</span>',
                            )
                        )
                    );
                    ?>
                </div>
            </article>
        </<?php echo $tag; ?>>
        <?php
    }
endif;
?>