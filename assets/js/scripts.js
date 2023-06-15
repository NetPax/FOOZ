(function () {
	'use strict';
	$(document).ready(function () {

		function getBooks() {
			$.ajax({
				url: "/wp-admin/admin-ajax.php",
				method: "post",
				timeout: 10000,
				data: {
					action: "get_books",
				},
				success: function (data) {
					try {
						JSON.parse(data);
					} catch (e) {
						console.log('JSON parse error!', e);
						return;
					}

					let booksJSON = JSON.parse(data);
					console.log(booksJSON);
				},
				error: function (e) {
					console.log('Error!', e);
				},
				beforeSend: function () {
					console.log('Loading books...');
				},
			});
		}

		getBooks();

	});
}(jQuery));
