/**
 * Defines 2 utility classes:
 */

/** Configurable -> A mixin class which allows classes to set options in a uniform
 * manner via a class property (DEFAULT_OPTIONS) and method (setOptions).
 * Secondly, these options are inheritable and if 2 classes in hierarchy chain set
 * same property then the farthest down the the chain takes precedence.
 */
var Configurable = {
	/**
	 * Set the options given an assoc array of name/value pairs
	 *
	 * @param options The object of name/value pairs.
	 */
	setOptions: function(options) {

		this.options = {};

		// Build inheritence chain
		var constructor = this.constructor;
		if (constructor.superclass) {

			var chain = [];
			var klass = constructor;
			while (klass = klass.superclass) {
				chain.push(klass);
			}
			chain = chain.reverse();

			// Accumulate the options from the inheritance hierarchy
			for (var i = 0, len = chain.length; i < len; i++) {
				Object.extend(this.options, klass.DEFAULT_OPTIONS || {});
			}
		}

		// Copy default options of the current class over previous options
		Object.extend(this.options, constructor.DEFAULT_OPTIONS);

		return Object.extend(this.options, options || {});
	}
};

/**
 * Trackable -> A mixin class which allows a class to wrap a DOM element node. The
 * register() method must be called by the implementing class which contains a
 * property this.element which is a reference to the DOM node.  The destroy() method
 * is implemented by the class and is used to perform any cleanup such as
 * deregistering any event handlers.
 */
var Trackable = {

	/**
	 * Registers a DOM node to be tracked by this class.
	 */
	register: function() {

		// Get the element and its ID to be wrapped, if any
		if (!this.element) {
			return false;
		}
		var id = this.element.identify();

		// Get the class that is wrapping a DOM node and check if
		// it already has instances of DOM nodes, if not initialize
		var c = this.constructor;
		if (!c.instances) {
			c.instances = {};
		}

		// Check if class already has a DOM node and has implemented destroy()
		// if so then cleanup and remove previous instance of DOM node
		if (c.instances[id] && c.instances[id].destroy) {
			c.instances[id].destroy();
		}

		// Save reference to class containing DOM node
		c.instances[id] = this;

		return true;
	}
};