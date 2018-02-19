jQuery.fn.easyPieChart = function(options) {
	return this.each(function() {
		var instanceOptions;

		if (!jQuery.data(this, 'easyPieChart')) {
			instanceOptions = jQuery.extend({}, options, jQuery(this).data());
			jQuery.data(this, 'easyPieChart', new EasyPieChart(this, instanceOptions));
		}
	});
};
