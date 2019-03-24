//require <jquery.packed.js>
//require <xatajax.form.core.js>
/* globals jQuery, Xatajax, DATAFACE_SITE_HREF, registerXatafaceDecorator */
(function($) {

	registerXatafaceDecorator(function(node) {

		var $rows = function() { return $('.listing').not('[id^="projects?id="]'); },
				$sortBtn = $('.sort-icon', node),
				params = {
					'-action': 'export_json',
					'-table': 'projects',
					'-mode': 'list',
					'-limit': '1000',
					'-sort': 'sort'
				},
				records;

	$.get(DATAFACE_SITE_HREF, params)
		.then(function(res) {

			if (res && res.length > 0) {

				records = res;

				for(var i = 0; i < records.length; i++) {

						records[i].sort = i * 10;

				}
			} else {
				console.warn('Something went wrong...');
			}
		});


		$sortBtn.on('click', function(e) {

			var $btn = $(this),
					$record = $btn.closest('.listing'),
					$prev = $record.prev().prev('.listing'),
					$next = $record.next().next('.listing'),
					data = {
						id: $btn.data('record'),
						sort: ($btn.data('direction') === 'up' ? -15
										: $btn.data('direction') === 'down' ? 15
											: 0)
					};

			e.preventDefault();

			if(!/is_subproject asc, sort asc/
					.test($('[data-xataface-query]').data('xatafaceQuery')['-sort'])) {
				return (
					window.confirm('Sorry, the list needs to be correctly sorted first.\nSort it now?') &&
					(window.location = 'https://admin.katiegilbertson.com')
				);
			}

			if($btn.data('direction') === 'up') {
				$record.add($('[id$="id=' + data.id + '-row"]')).insertBefore($prev);
			}
			if($btn.data('direction') === 'down') {
				$record.add($('[id$="id=' + data.id + '-row"]')).insertAfter($next);
			}

			$rows().each(function(i) {
				$(this)
					.add($(this).find('.even, .odd'))
					.add($(this).next('.even, .odd'))
					.removeClass('even odd')
					.addClass(function() {
						return i % 2 === 0 ? 'even' : 'odd';
					});
			})

			$('.sort-icon').attr('disabled', true);

			postSortUpdate(data);
		});

		function postSortUpdate(data) {

			for(var i = 0; i < records.length; i++) {
				if(records[i].id == data.id) {
					records[i].sort += data.sort;
					break;
				}
			}

			$.post('//api.katiegilbertson.com/update-sort.php', { records: records })
				.then(function(response) {
					// returns the updated list
					records = response;
					if(records.length) {
						$('.sort-icon').removeAttr('disabled');
					} else {
						console.warn('Something went wrong...');
					}
			});
		}

	});
})(jQuery);
