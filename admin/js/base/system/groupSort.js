var groupSorting = function (settings) {
	this.settings = $.extend({
		'element$' : '.sortable',
		'handle' : '.sortHandle',
		'helper' : 'clone',
		'containment' : '.containment',
		'axys' : '',
		'start' : function() {},
		'update' : function() {}
	}, settings||{});

	this.action = '/admin/articles/changePriority/?';

	this.sortable = function () {
		this.settings.element$.sortable({
			handle: this.settings.handle,
			helper: this.settings.helper,
			containment: this.settings.containment,
			axis: this.settings.axys,
			start: $.proxy(this.start, this),
			update: $.proxy(this.update, this)
		})

		return this;
	}

	this.stylize = function () {
		this.settings.element$.find('.sortHandle')
			.append('<span class="ui-icon ui-icon-arrowthick-2-n-s">');
		return this;
	}

	this.start = function(event, ui) {
		var colspan = $('.ui-sortable-placeholder').parent().children('tr').children('td:last').index() + 1 ;
		$('.ui-sortable-placeholder').append('<td colspan="'+colspan+'"></td>');

		return this;
	}

	this.update = function(event, ui) {
		var query = '';
		var that = this;
		this.settings.element$.children( "tr" ).each(function (i){
				if ($(this).data('id') !== undefined && $(this).data('priority') !== undefined) {
					query += '&data['+$(this).data('id')+']='+$(this).data('priority');
					$(this).find(that.settings.handle).text(i);
				}
			});
		this.stylize()
			.savePriority(query);

		return this;
	}

	this.savePriority = function (query) {
		var that = this;
		$.ajax({
			beforeSend: $.proxy(that.before, that),
			error: $.proxy(that.error, that),
			url: that.action+query,
			type: that.method || 'post',
			dataType: 'json',
			success: $.proxy(that.success, that),
			complete: $.proxy(that.complete, that)
		});

		return this;
	}
}