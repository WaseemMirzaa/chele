<?php
/**
 * Comments template.
 *
 * @package Chele
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$count = get_comments_number();
			if ( '1' === (string) $count ) {
				esc_html_e( 'One note', 'chele' );
			} else {
				/* translators: %s: comment count. */
				printf( esc_html__( '%s notes', 'chele' ), esc_html( number_format_i18n( $count ) ) );
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => __( '←', 'chele' ),
				'next_text' => __( '→', 'chele' ),
			)
		);
		?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'chele' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'        => __( 'Leave a note', 'chele' ),
			'class_submit'       => 'btn btn--primary',
			'label_submit'       => __( 'Post note', 'chele' ),
		)
	);
	?>
</div>
