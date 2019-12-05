<?php

/**
*   Simple Responsive Slideshow
*
*   Responsive and customizable Joomla!3 module, slideshow based on FlexSlider 1.8
*
*   @version        2.5
*   @link           http://extensions.favthemes.com/simple-responsive-slideshow
*   @author         FavThemes - http://www.favthemes.com
*   @copyright      Copyright (C) 2012-2016 FavThemes.com (WooThemes for the original script). All Rights Reserved.
*   @license        Licensed under GNU/GPLv3, see http://www.gnu.org/licenses/gpl-3.0.html
*/

// no direct access

defined('_JEXEC') or die;

$animation = $params->get('animation');
$jqueryLoad = $params->get('jqueryLoad');
$slidedirection = $params->get('slideDirection');
$slideshowspeed = $params->get('slideshowSpeed');
$animationduration = $params->get('animationDuration');
$slideshow = ($params->get('slideshow') == 1) ? 'true' : 'false';
$directionnav = ($params->get('directionNav') == 1) ? 'true' : 'false';
$controlnav = ($params->get('controlNav') == 1) ? 'true' : 'false';
$keyboardnav = ($params->get('keyboardNav') == 1) ? 'true' : 'false';
$mousewheel = ($params->get('mousewheel') == 1) ? 'true' : 'false';
$randomize = ($params->get('randomize') == 1) ? 'true' : 'false';
$animationloop = ($params->get('animationLoop') == 1) ? 'true' : 'false';
$pauseonaction = ($params->get('pauseOnAction') == 1) ? 'true' : 'false';
$pauseonhover = ($params->get('pauseOnHover') == 1) ? 'true' : 'false';
$flexsliderBgColor = $params->get('flexsliderBgColor');

$rand = rand(10000,20000);

// Module CSS
JHTML::stylesheet('modules/mod_simple_responsive_slideshow/theme/css/simple-responsive-slideshow.css');
//JHTML::stylesheet('//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
// Module Scripts
if ($jqueryLoad) { JHtml::_('jquery.framework'); }
JHTML::script('modules/mod_simple_responsive_slideshow/theme/js/jquery.flexslider.js');

for ($i=1;$i<=10;$i++) {

	${'file'.$i} = $params->get('file'.$i)."?t=".time();
	${'file'.$i.'active'} = $params->get('file'.$i.'active');
	${'file'.$i.'link'} = $params->get('file'.$i.'link');
	${'file'.$i.'caption'} = $params->get('file'.$i.'caption');
	${'file'.$i.'alt'} = $params->get('file'.$i.'alt');

}

?>

<?php echo '<script type="text/javascript">'; ?>

<?php echo'jQuery(window).load(function() {
				jQuery(\'#favsimple-'.$rand.'\').flexslider({
				  animation: "'.$animation.'",
				  slideDirection: "'.$slidedirection.'",
				  slideshow: '.$slideshow.',';
				  if ($slideshow == "true") { echo '
			      slideshowSpeed: '.$slideshowspeed.',
				  animationDuration: '.$animationduration.','; }
				  echo 'directionNav: '.$directionnav.',
				  controlNav: '.$controlnav.',
				  keyboardNav: '.$keyboardnav.',
				  mousewheel: '.$mousewheel.',
				  randomize: '.$randomize.',
				  animationLoop: '.$animationloop.',
				  pauseOnAction: '.$pauseonaction.',
				  pauseOnHover: '.$pauseonhover.'
			  });
			});

jQuery(document).ready(function() {
   setTimeout(function() {
        var imgDefer = document.getElementsByTagName("img");
        for (var i=0; i<imgDefer.length; i++) {
                if(imgDefer[i].getAttribute("data-src")) {
                        imgDefer[i].setAttribute("src",imgDefer[i].getAttribute("data-src"));
                }
        }
    }, 50);
});
</script>'; ?>




<div id="favsimple-<?php echo $rand; ?>" class="flexslider"
	style="background-color: #<?php echo $flexsliderBgColor; ?>;"
>
  <ul class="slides">

		<?php for ($i=1;$i<=10;$i++) { if (${'file'.$i} && ${'file'.$i.'active'} && ${'file'.$i} != " ") { ?>

    	<li>
    		<?php if (${'file'.$i.'link'}) { ?> <a href="<?php echo ${'file'.$i.'link'}; ?>" class="modalizer"><img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" 
			data-src="<?php echo ${'file'.$i}; ?>" alt="<?php echo ${'file'.$i.'alt'}; ?>" /></a><?php } else { ?> <img src="<?php echo ${'file'.$i}; ?>" alt="<?php echo ${'file'.$i.'alt'}; ?>" /> <?php } ?>
    		<?php if (${'file'.$i.'caption'}) { ?> <p class="flex-caption"><?php echo ${'file'.$i.'caption'}; ?></p> <?php } ?>
    	</li>

		<?php } }?>

  </ul>
</div>
