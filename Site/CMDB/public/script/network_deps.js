//
// Allow selection/removal of local services
//
Event.observe(window, 'load',  function() {
	$('add-remote-interface').observe('click', function(event) {

		// Get the list of selected options
		var intfElem = $('list-remote-def-interfaces');
		var items = new Array();
		$A(intfElem.options).each(function(elem, index) {
			if (elem.selected) {
				items.push({
					'id' : elem.value,
					'text' : elem.text,
					'index' : index
				});
			}
		});

		// Add interface dependency
		var devIntfElem = $('remote-interfaces-deps');
		items.each(function(elem, index) {
			var opt = document.createElement('option');
			opt.text = elem.text;
			opt.value = elem.id;
			opt.selected = true;
			try {
				devIntfElem.add(opt, null); // standards compliant; doesn't work in IE
			}
			catch(ex) {
				devIntfElem.add(opt); // IE only
			}
		});

		// Remove them list in reverse order as index change on removal
		items.reverse().each(function(elem, index) {
			intfElem.remove(elem.index);
		});
	});

	new Ajax.Autocompleter('intf-dep-device-name', 'device-interface-suggestions',
		'/lookup/names', {
			minChars : 3,
			frequency : 0.3,
			indicator : 'indicator2',
			afterUpdateElement : function(inputElem, selectedItem) {
				// Get services  for inputElem.getValue()
				new Ajax.Updater('list-remote-def-interfaces', '/lookup/interfaces', {
					method : 'get',
					parameters : {
						name : inputElem.getValue()
					}
				});
			}
		});
});