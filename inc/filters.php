<?php 
	$terms = get_terms( array(
	    'taxonomy' => 'event_types',
	    'hide_empty' => false,
	) );

	$tags = get_terms( array(
	    'taxonomy' => 'event_tags',
	    'hide_empty' => false,
	) );

?>
<form method="POST" id="event-filter">
	<fieldset>
		<legend>Filters</legend>

		<label>Keyword: </label>
		<input type="text" id="fname" name="keyword"><br><br>

		<label for="">Event Types: </label>
			<?php foreach ($terms as $tkey => $tval) { ?>
				<input type="checkbox" name="event_type[]" value="<?php echo $tval->slug; ?>"><?php echo ucfirst($tval->name); ?>
			<?php } ?>
		<br><br>

		<label for="">Event Tags: </label>
			<?php foreach ($tags as $tgkey => $tgval) { ?>
				<input type="checkbox" name="event_tag[]" value="<?php echo $tgval->slug; ?>"><?php echo ucfirst($tgval->name); ?>
			<?php } ?>
		<br><br>
		
		<input type="submit" value="Submit">
	</fieldset>
</form>