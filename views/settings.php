<div class="rac-container">
	<div class="rac-header">
		<img src="<?php echo plugins_url( 'images/logo.png', dirname(__FILE__) ) ?>">
		<span><?php esc_html_e( 'Rex Ad Cards Setup', 'rex-ad-cards' ); ?></span>
	</div>
	
	<h1><?php esc_html_e( 'Your Rex Ad Cards', 'rex-ad-cards' ); ?></h1>
	<p><?php esc_html_e( 'Please copy your Rex Ad Card code snippet below.', 'rex-ad-cards' ); ?></p>
	
	<div class="rac-small">
		<?php echo sprintf( esc_html( __( '%sNote:%s To get your code snippet please visit the %s on Rex Ad Cards.', 'rex-ad-cards' ) ), '<b>', '</b>', '<a href="https://www.rexadcards.com/account">account page</a>' ); ?>
	</div>
	
	<hr>
	
	<?php
		if ( empty( $rac_api_key ) || empty( $rac_snippet_code ) ) {
	?>
	
	<form method="post" action="">
		<div class="rac-panel-header rac-panel-header-green"><?php esc_html_e( 'Code Snippet', 'rex-ad-cards' ); ?></div>
		<div class="rac-panel-body rac-panel-body-green">
			<textarea maxlength="8192" name="rac-code-snippet"></textarea>
			<div class="rac-small"><?php esc_html_e( 'Copy the code snippet from your Red Ad Cards account in the box above and hit save.', 'rex-ad-cards' ); ?></div>
		</div>
		<input type="submit" name="submit" value="Save changes" class="rac-btn">
	</form>
	
	<?php
		} else {
	?>
		
	<form method="post" action="">
		<div class="rac-panel-header rac-panel-header-green"><?php esc_html_e( 'Code Snippet', 'rex-ad-cards' ); ?></div>
		<div class="rac-panel-body rac-panel-body-green">
			<div class="rac-saved rac-saved-no-margin">Your code snippet has been saved!</div>
		</div>
		<input type="submit" name="submit_change" value="Change snippet code" class="rac-btn">
	</form>
	
	<?php
		}
	?>
	
	<div class="rac-panel-header rac-panel-header-purple"><?php esc_html_e( 'Shortcode', 'rex-ad-cards' ); ?></div>
	<div class="rac-panel-body rac-panel-body-purple">
		<span class="rac-shortcode-left">[rex-ad-cards-1]</span>
		<span class="rac-small"><?php esc_html_e( 'Add this shortcode anywhere you want the Rex Ad Card to appear.', 'rex-ad-cards' ); ?></span>
	</div>
</div>