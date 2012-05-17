tt_content.vimeo = COA
tt_content.vimeo {
	10 = < lib.stdheader

	20 = TEXT
	20.value (
		<iframe src="http://player.vimeo.com/video/{t3datastructure : pi_flexform->vimeoID}?title=0&amp;byline=0&amp;portrait=0" width="{t3datastructure : pi_flexform->width}" height="{t3datastructure : pi_flexform->height}" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
	)
	20.insertData = 1
}