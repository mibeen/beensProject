function view_modal(href, title) {
	$('#viewModal').modal('show').on('hidden.bs.modal', function (e) {
		$("#viewModalTitle").empty();
		$("#viewModalFrame").attr("src", "");
		// $('body').removeClass('body-fixed'); // ios11 modal 내부 input 이슈 처리
	});

	$('#viewModal').modal('show').on('shown.bs.modal', function (e) {
		$('#viewModalLoading').show();
		if (title != null) $("#viewModalTitle").text(title);
		if(href.indexOf('?') > 0) {
			$("#viewModalFrame").attr("src", href + '&pim=1');
		} else {
			$("#viewModalFrame").attr("src", href + '?pim=1');
		}
		$('#viewModalFrame').load(function() {
			$('#viewModalLoading').hide();
		});
		// $('body').addClass('body-fixed'); // ios11 modal 내부 input 이슈 처리
	});
	return false;
}

$(document).ready(function () {

	var view_modal_height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

	$('#viewModalFrame').height(parseInt(view_modal_height - 140));

	$(window).resize(function () {
		view_modal_height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
		$('#viewModalFrame').height(parseInt(view_modal_height - 140));
	});
});
