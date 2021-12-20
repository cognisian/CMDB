Event.observe(window, 'load',  function() {

	new Ajax.Autocompleter('switch-name', 'device-switch-suggestions',
		'/lookup/names', {
			minChars : 3,
			frequency : 0.3,
			indicator : 'indicator2'
		});
});
