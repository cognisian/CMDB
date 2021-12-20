//
// Allow selection/removal of local services
//
Event.observe(window, 'load',  function() {
	$('add-local-service').observe('click', function(event) {

		// Get the list of selected options
		var servElem = $('list-local-services');
		var items = new Array();
		$A(servElem.options).each(function(elem, index) {
			if (elem.selected) {
				items.push({
					'id' : elem.value,
					'text' : elem.text,
					'index' : index
				});
			}
		});

		// Add to local services
		var devServElem = $('local-services');
		items.each(function(elem, index) {
			var opt = document.createElement('option');
			opt.text = elem.text;
			opt.value = elem.id;
			opt.selected = true;
			try {
				devServElem.add(opt, null); // standards compliant; doesn't work in IE
			}
			catch(ex) {
				devServElem.add(opt); // IE only
			}
		});

		// Remove them list in reverse order as index change on removal
		items.reverse().each(function(elem, index) {
			servElem.remove(elem.index);
		});
	});

	$('del-local-service').observe('click', function(event) {

		// Get the list of selected options
		var devServElem = $('local-services');
		var items = new Array();
		$A(devServElem.options).each(function(elem, index) {
			if (elem.selected) {
				items.push({
					'id' : elem.value,
					'text' : elem.text,
					'index' : index
				});
			}
		});

		// Add to local services
		var servElem = $('list-local-services');
		items.each(function(elem, index) {
			var opt = document.createElement('option');
			opt.text = elem.text;
			opt.value = elem.id;
			try {
				servElem.add(opt, null); // standards compliant; doesn't work in IE
			}
			catch(ex) {
				servElem.add(opt); // IE only
			}
		});

		// Remove them list in reverse order as index change on removal
		items.reverse().each(function(elem, index) {
			devServElem.remove(elem.index);
		});
	});
});

//
// Allow selection of dependency
//
Event.observe(window, 'load',  function() {

	$('add-remote-service').observe('click', function(event) {

		// Get the list of selected options
		var servElem = $('list-remote-def-services');
		var items = new Array();
		$A(servElem.options).each(function(elem, index) {
			if (elem.selected) {
				items.push({
					'id' : elem.value,
					'text' : elem.text,
					'index' : index
				});
			}
		});

		// Add to local services
		var devServElem = $('remote-services-deps');
		items.each(function(elem, index) {
			var opt = document.createElement('option');
			opt.text = elem.text;
			opt.value = elem.id;
			opt.selected = true;
			try {
				devServElem.add(opt, null); // standards compliant; doesn't work in IE
			}
			catch(ex) {
				devServElem.add(opt); // IE only
			}
		});

		// Remove them list in reverse order as index change on removal
		console.log(items);
		items.reverse().each(function(elem, index) {
			servElem.remove(elem.index);
		});
	});

	new Ajax.Autocompleter('serv-dep-device-name', 'device-service-suggestions',
		'/lookup/names', {
			minChars : 3,
			frequency : 0.3,
			indicator : 'indicator3',
			afterUpdateElement : function(inputElem, selectedItem) {
				// Get services  for inputElem.getValue()
				new Ajax.Updater('list-remote-def-services', '/lookup/services', {
					method : 'get',
					parameters : {
						name : inputElem.getValue()
					}
				});
			}
		});
});