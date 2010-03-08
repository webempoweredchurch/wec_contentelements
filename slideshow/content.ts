tt_content.slideshow = COA
tt_content.slideshow {
	# Show the standard content element header.
	5 = < lib.stdheader

	# Include the necessary Javascript and CSS libraries.
	10 = HEADERDATA
	10.value (
		<script src="typo3conf/ext/contentelements/slideshow/res/jquery.js"></script>
		<script src="typo3conf/ext/contentelements/slideshow/res/jquery.galleryview-2.0-pack.js"></script>
		<script src="typo3conf/ext/contentelements/slideshow/res/jquery.timers-1.1.2.js"></script>
		<script src="typo3conf/ext/contentelements/slideshow/res/jquery.easing.1.3.js"></script>
		<!-- This is optional !-->
		<link rel=stylesheet type=text/css href="fileadmin/templates/skin_weatheredwood/temp_rotation/galleryview.css">
	)

	# Include the Javascript settings for the gallery.
	20 = COA
	20 {
		wrap = <script>|</script>
		10 = TEXT
		10.value (
				$(document).ready(function() {
					$('#gallery').galleryView( {
		)
		
		20 = TEXT
		20.value (
						panel_width: 400,
		)
		20.insertData = 1

		30 = TEXT
		30.value (
						panel_height: 181,
						frame_width: 0,
						frame_height: 0,
						overlay_color: '#222',
						overlay_text_color: 'white',
						caption_text_color: '#222',
						background_color: 'transparent',
						border: 'none',
						nav_theme: 'light',
						easing: 'easeInOutQuad',
						pause_on_hover: true,
						show_filmstrip: false
					});
				});
		)
	}

	# Draw each image in the slideshow.
	30 = FFSECTION
	30.rootPath = t3datastructure : pi_flexform->images/el
	30 {
		wrap = <ul id="gallery"> | </ul>
		
		10 = COA
		10 {
			wrap = <li> | </li>
			required = 1

			10 = TEXT
			10.data = flexformSection : image/el/title
			10.wrap = <span class="panel-overlay">|</span>

			20 = IMAGE
			20.file.import.data = flexformSection : image/el/path
			20.file.width.data = register:containerWidth
		}
	}
}
