<?php
/**
 * Default Events Template
 * This file is the basic wrapper template for all the views if 'Default Events Template'
 * is selected in Events -> Settings -> Template -> Events Template.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/default-template.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/*
Template Name: Screenings
*/

get_header();
?>
<div id="tribe-events-pg-template" class="tribe-events-pg-template">
<div class="l-main" style="width: 100%; margin-top: 30px">

	<div class="l-main-h i-cf" style="width: 100%; ">

		<main class="l-content" itemprop="mainContentOfPage"  style="width: 100%; ">

            <!-- <p>1) Enter your postcode / zip code and click 'FIND EVENTS'<br />
    2) Then under "Narrow Your Results' in the bar below - use the distance dropdown to find the screenings nearest you</p>-->
            <div class="md-before-filter">
                <p>To find your nearest Cinema on the map below please zoom in on your location or double click on the map near to where you are located (but avoid double clicking on the markers)</p>
            </div>

            <?php tribe_events_before_html(); ?>
            <?php tribe_get_view(); ?>
            <?php tribe_events_after_html(); ?>
        
        </main>
        
        </div>
    </div>
    
</div> <!-- #tribe-events-pg-template -->
<?php
get_footer();
