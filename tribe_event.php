<?php



/*

Template Name: Event categorys template

*/



get_header();





   

?>

<div class="l-main">

	<div class="l-main-h i-cf">

		<?php

    // TO SHOW THE PAGE CONTENTS

    while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->

        <div class="entry-content-page">

            <?php the_content(); ?> <!-- Page Content -->

        </div><!-- .entry-content-page -->



    <?php

    endwhile; //resetting the page loop

  ?>

  <div class="g-cols valign_top">

    <div class="vc_col-sm-8 wpb_column vc_column_container event-listcat"> <br/> <br/>

  <?php

  

  echo $country_data = country_dropdown();



  

	 $cat = $_GET['event'];	

	$countrySearch = $_GET['country'];	

	if(isset($cat) && !empty($cat)){



		$args = array(		

		'post_type'=>'tribe_events',

		'eventDisplay' => 'custom',

		 'tax_query'=> array(

                array(

                    'taxonomy' => 'tribe_events_cat',

                    'field' => 'slug',

                    'terms' => $cat

                )

            )

	);

	

	

	//$country_venue = get_posts( $args );

	$postslist = new WP_Query( $args );





	//Loops through venues and add ID to array

	if ( $postslist->have_posts() ) :

        while ( $postslist->have_posts() ) : $postslist->the_post(); 



			 $Id = get_the_ID();

		  	$locationId=get_post_meta($Id,'_EventVenueID',true);

			$Country=get_post_meta($locationId,'_VenueCountry',true);

			$Country = strtolower($Country);

			$countrySearch = strtolower($countrySearch);

			

			

							if(!empty($locationId))

							{

							$Address=get_post_meta($locationId,'_VenueAddress',true);

							$City=get_post_meta($locationId,'_VenueCity',true);

							

							$Zip=get_post_meta($locationId,'_VenueZip',true);

							$State=get_post_meta($locationId,'_VenueState',true);

							$fulladdress = $Address.' '.$City.' '.$Country.'-'.$Zip;

							}else{

							$fulladdress = 'No Location Found';

							}

							

			if(isset($countrySearch) && !empty($countrySearch) && $countrySearch == $Country){

			

				 $eventllist .='<div class="css-events-list" id="css-events-list" ><ul id="demoFour">

					<a href="'.site_url().'/venue/?id='.$locationId.'">

					<li class="">

								<h2 class="our-film">' . get_the_title() . '</h2> 

								<h2 class="film-subtitle"><p>'.$fulladdress.'</p></h2>

					</li></a>

					

					</ul></div>';

		

			}

			

			

			if(empty($countrySearch)){

						$eventllist .='<div class="css-events-list" id="css-events-list" ><ul id="demoFour">

					<a href="'.site_url().'/venue/?id='.$locationId.'">

					<li class="">

								<h2 class="our-film">' . get_the_title() . '</h2> 

								<h2 class="film-subtitle"><p>'.$fulladdress.'</p></h2>

					</li></a>

					

					</ul></div>';

			}

	endwhile;

	

	  

		$eventllist.='<div class="pagination-list">'.get_previous_posts_link('Previous');

		$eventllist.=get_next_posts_link( 'Next', $postslist->max_num_pages ).'</div>';

		$eventllist.=wp_reset_postdata();

	endif;  

		echo $eventllist;

		}

	

	

	?>

		</div>

	

	

	<div class="vc_col-sm-4 wpb_column vc_column_container">

	<div class="vc_column-inner">

	<div class="wpb_wrapper">

	<div class="wpb_widgetised_column wpb_content_element">

		<div class="wpb_wrapper custom-venuesidebar"><br/>


		

		</div>

	</div>

</div>

</div>

</div></div>

		</div>

	</div>

	

	

	<?php get_footer(); ?>

		<script type="text/javascript">	

		

			jQuery('.event-listcat select.em-search-country').change(function(){

		var countrycode = jQuery(this).find(":selected").text()

		var siteurl = '<?php echo site_url(); ?>';

		var cat = '<?php echo $cat; ?>';

		// similar behavior as clicking on a link

        window.location.href = siteurl+"/event-list/?event="+cat+"&country="+countrycode;

			});

		</script>