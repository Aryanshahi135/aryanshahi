<?php
/**
 * The template part for top header
 *
 * @package Marketing Agency 
 * @subpackage marketing-agency
 * @since marketing-agency 1.0
 */
?>

<div class="top-bar text-center text-lg-left text-md-left">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-3">
        <?php if( get_theme_mod('marketing_agency_email_address_text') != '' || get_theme_mod('marketing_agency_email_address') != '' ){ ?>
          <p class="py-2 py-lg-3 py-md-3 mb-0"><i class="far fa-envelope me-2"></i><?php echo esc_html(get_theme_mod('marketing_agency_email_address_text',''));?>: <a href="mailto:<?php echo esc_attr(get_theme_mod('marketing_agency_email_address',''));?>"><?php echo esc_html(get_theme_mod('marketing_agency_email_address',''));?></a></p>
        <?php } ?>
      </div>
      <div class="col-lg-2 col-md-2">
        <?php if( get_theme_mod('marketing_agency_phone_number_text') != '' || get_theme_mod('marketing_agency_phone_number') != '' ){ ?>
          <p class="py-2 py-lg-3 py-md-3 mb-0"><i class="fas fa-phone me-2"></i><?php echo esc_html(get_theme_mod('marketing_agency_phone_number_text',''));?>: <a href="tel:<?php echo esc_attr( get_theme_mod('marketing_agency_phone_number','') ); ?>"><?php echo esc_html(get_theme_mod('marketing_agency_phone_number',''));?></a></p>
        <?php } ?>
      </div>
      <div class="col-lg-7 col-md-7">
        <div class="row">
          <div class="col-lg-9 col-md-7 text-lg-right text-center py-0 py-lg-0 py-md-3">
            <?php dynamic_sidebar('social-links'); ?>
          </div>
          <div class="col-lg-3 col-md-5 text-lg-right text-center mb-2 mb-lg-0 mt-lg-0 mb-md-2 mt-md-2">
            <?php if( get_theme_mod('marketing_agency_getstarted_link') != '' ||  get_theme_mod('marketing_agency_getstarted_text') != ''){ ?>
              <a class="getstarted-btn px-4 py-3" href="<?php echo esc_url(get_theme_mod('marketing_agency_getstarted_link',''));?>"><?php echo esc_html(get_theme_mod('marketing_agency_getstarted_text',''));?></a>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>