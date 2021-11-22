<div>
	<label><?php esc_html_e('Event Date', 'outside-tech'); ?></label>
	<input type="date" name="event[event_date]" value="<?php echo esc_attr($event_data['event_date']); ?>" placeholder="Event Date Here">
</div>

<div>
	<label><?php esc_html_e('Venue', 'outside-tech'); ?></label>
	<input type="text" name="event[venue]" value="<?php echo esc_attr($event_data['venue']); ?>" placeholder="Event Venue Here">
</div>

<div>
	<label><?php esc_html_e('Event Short Description', 'outside-tech'); ?></label>
	<textarea name="event[short_desc]" placeholder="Event Venue Here"><?php echo esc_html($event_data['short_desc']); ?></textarea>
</div>