tt_content.youtube = COA
tt_content.youtube {
	10 = < lib.stdheader

	20 = TEXT
	20.value (
		<iframe width="{t3datastructure : pi_flexform->width}" height="{t3datastructure : pi_flexform->height}" src="http://www.youtube.com/embed/{t3datastructure : pi_flexform->youtubeID}" frameborder="0" allowfullscreen></iframe>
	)
	20.insertData = 1
}