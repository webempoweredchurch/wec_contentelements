tt_content.slideshow = COA
tt_content.slideshow {
	# Show the standard content element header.
	5 = < lib.stdheader

	# Include the necessary Javascript and CSS libraries.
	10 = HEADERDATA
	10.value (
		<script src="typo3conf/ext/wec_contentelements/slideshow/res/jquery.cycle.all.min.js"></script>
	)

	# Include the Javascript settings for the gallery.
	20 = COA
	20 {
		wrap = <script type="text/javascript">|</script>
		10 = TEXT
		10.value (

				jQuery(document).ready(function() {
					jQuery('.rotator').cycle({
		)

		20 = TEXT
		20.value (
			fx: '{ t3datastructure : pi_flexform->transition }',
			fit: 1,
			pause: 1,
			height: 300
		)
		20.insertData = 1

		100 = TEXT
		100.value (
					});
				});
		)
	}

	# Draw each image in the slideshow.
	30 = FFSECTION
	30.rootPath = t3datastructure : pi_flexform->images/el
	30 {
		wrap = <div class="rotator"> | </div
		
		10 = IMAGE
		10.file.import.data = flexformSection : image/el/path
		10.file.width.data = register:containerWidth
		10.file.height = 300c
		10.stdWrap.typolink.parameter.data = flexformSection : image/el/link
	}
}
