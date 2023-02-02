/**
* @package     Avisa  Music Database
* @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

jQuery(function($) {

	// Tags
	$(document).ready(function () {

		// Actors
		$('#actors_chzn input').keyup(function(event) {

			if (this.value && this.value.length >= 3 && (event.which === 13 || event.which === 188)) {

				var highlighted = $('#actors_chzn').find('li.active-result.highlighted').first();

				if (event.which === 13 && highlighted.text() !== '') {
					var customOptionValue = '#new#' + highlighted.text();
					$('#actors option').filter(function () { return $(this).val() == customOptionValue; }).remove();

					var tagOption = $('#actors option').filter(function () { return $(this).html() == highlighted.text(); });
					tagOption.attr('selected', 'selected');
				} else {
					var customTag = this.value;

					var tagOption = $('#actors option').filter(function () { return $(this).html() == customTag; });
					if (tagOption.text() !== '') {
						tagOption.attr('selected', 'selected');
					} else {
						var option = $('<option>');
						option.text(this.value).val('#new#' + this.value);
						option.attr('selected','selected');
						$('#actors').append(option);
					}
				}

				this.value = '';
				$('#actors').trigger('liszt:updated');
				event.preventDefault();

			}
		});

		// Actors
		$('#directors_chzn input').keyup(function(event) {

			if (this.value && this.value.length >= 3 && (event.which === 13 || event.which === 188)) {

				var highlighted = $('#directors_chzn').find('li.active-result.highlighted').first();

				if (event.which === 13 && highlighted.text() !== '') {
					var customOptionValue = '#new#' + highlighted.text();
					$('#directors option').filter(function () { return $(this).val() == customOptionValue; }).remove();

					var tagOption = $('#directors option').filter(function () { return $(this).html() == highlighted.text(); });
					tagOption.attr('selected', 'selected');
				} else {
					var customTag = this.value;

					var tagOption = $('#directors option').filter(function () { return $(this).html() == customTag; });
					if (tagOption.text() !== '') {
						tagOption.attr('selected', 'selected');
					} else {
						var option = $('<option>');
						option.text(this.value).val('#new#' + this.value);
						option.attr('selected','selected');
						$('#directors').append(option);
					}
				}

				this.value = '';
				$('#directors').trigger('liszt:updated');
				event.preventDefault();

			}
		});
	});
});