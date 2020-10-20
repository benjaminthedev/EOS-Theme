<?php
/*
Template Name: event-template
*/

get_header();
?>

<div id="tribe-events-pg-template" class="tribe-events-pg-template">
<div class="l-main" style="width: 100%; margin-top: 30px">

	<div class="l-main-h i-cf">

		<main class="l-content" itemprop="mainContentOfPage">
		<?php tribe_events_before_html(); ?>
        <?php tribe_get_view(); ?>
        <?php tribe_events_after_html(); ?>
        
        </main>
        
        </div>
    </div>
    
</div> <!-- #tribe-events-pg-template -->
<?php
get_footer();
