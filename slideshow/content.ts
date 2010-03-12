tt_content.slideshow = COA
tt_content.slideshow {
	# Show the standard content element header.
	5 = < lib.stdheader

	# Include the necessary Javascript and CSS libraries.
	10 = HEADERDATA
	10.value (
		<script src="typo3conf/ext/wec_contentelements/slideshow/res/jquery.cycle.all.min.js"></script>
		<style>
			#nav { margin: 0; padding: 0; }
			#nav li { width: 100px; float: left; margin: 8px 16px 8px 0; list-style: none }
			#nav a { width: 100px; padding: 3px; display: block; border: 1px solid #ccc; }
			#nav a.activeSlide { background: #88f }
			#nav a:focus { outline: none; }
			#nav img { border: none; display: block }
		</style>
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
			timeout: '{ t3datastructure : pi_flexform->displayTime}',
			fit: 1,
			pause: 1,
			pager: '#nav',
		)
		20.insertData = 1

		25 = TEXT
		25.value (
			pager: '#nav',
		)
		20.if.isTrue.data = t3datastructure : pi_flexform->showThumbnails

		30 = TEXT
		30.value (
			pagerAnchorBuilder: function(idx, slide) { 
		        // return selector string for existing anchor 
		        return '#nav li:eq(' + idx + ') a'; 
		    }
		)
		30.if.isTrue.data = t3datastructure : pi_flexform->showThumbnails

		100 = TEXT
		100.value (
					});
				});
		)
	}

	30 = CASE
	30 {
		key.data = t3datastructure : pi_flexform->resolution
		widescreen = LOAD_REGISTER
		widescreen {
			slideshowWidth.data = register : containerWidth
			tempSlideshowHeight.data = register : containerWidth
			tempSlideshowHeight.wrap = (|*.5625)
			slideshowHeight.data = register : tempSlideshowHeight
			slideshowHeight.prioriCalc = intval
		}
		standard = LOAD_REGISTER
		standard {
			slideshowWidth.data = register : containerWidth
			tempSlideshowHeight.data = register:containerWidth
			tempSlideshowHeight.wrap = (|*.75)+24
			slideshowHeight.data = register:tempSlideshowHeight
			slideshowHeight.prioriCalc = intval
		}
	 	custom = LOAD_REGISTER
		custom {
			slideshowWidth.data = t3datastructure : pi_flexform->width
			slideshowHeight.data = t3datastructure : pi_flexform->height
		}
	}

	# Draw each image in the slideshow.
	40 = FFSECTION
	40.rootPath = t3datastructure : pi_flexform->images/el
	40 {
		wrap = <div class="rotator"> | </div
		
		10 = IMAGE
		10.file.import.data = flexformSection : image/el/path
		10.file.width {
			data = register : slideshowWidth
			wrap = |c
		}
		10.file.height {
			data = register : slideshowHeight
			wrap = |c
		}
		10.stdWrap.typolink.parameter.data = flexformSection : image/el/link
	}

	# Draw each image in the slideshow.
	50 = FFSECTION
	50.rootPath = t3datastructure : pi_flexform->images/el
	50 {
		if.isTrue.data = t3datastructure : pi_flexform->showThumbnails
		stdWrap.dataWrap = <ul id="nav" style="width: {register : slideshowWidth}px"> | </ul>
		
		10 = IMAGE
		10.file.import.data = flexformSection : image/el/path
		10.file.width = 100c
		10.file.height = 100c
		10.wrap = <li><a href="#">|</a></li>
	}
	
	60 = HTML
	60.value = <div style="clear: both"></div>
}
