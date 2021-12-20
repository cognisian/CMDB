// Set the autocompleter for host names
Event.observe(window, 'load',  function() {
	new Ajax.Autocompleter('name', 'name-suggestions',
		'/lookup/names', {
			minChars : 3,
			frequency: 0.3,
			indicator: 'indicator'
		}
	)
});