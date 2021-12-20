//
// Allow selection/removal of local services
//
Event.observe(window, 'load',  function() {
	$('add-parent-device').observe('click', function(event) {

		// Get the remote device name
		var parentDevice = $F('parent-device-name');

		// Add to local services
		var servElem = $('parent-devices');
		var opt = document.createElement('option');
		opt.text = parentDevice;
		opt.value = parentDevice;
		try {
			servElem.add(opt, null); // standards compliant; doesn't work in IE
		}
		catch(ex) {
			servElem.add(opt); // IE only
		}
	});

	new Ajax.Autocompleter('parent-device-name', 'parent-device-suggestions',
		'/lookup/names', {
			minChars : 3,
			frequency : 0.3,
			indicator : 'indicator1'
		});
});