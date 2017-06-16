jQuery(document).ready(function() {
	var tab = '';
	var slideType = '';
	var slideTimeType = '';

	var tabs = jQuery(".tab-content", ".admin-custom");
	var delBtn = jQuery(".ui-icon-trash", ".tab-content", ".admin-custom");

	var moveSlideUpBtn = jQuery(".admin-custom__arrow_up", ".admin-custom");
	var moveSlideDownBtn = jQuery(".admin-custom__arrow_down", ".admin-custom");

	jQuery(moveSlideUpBtn).click(function(){
		var current = jQuery(this).closest('.tab-content');
		current.prev().before(current);
	});
	jQuery(moveSlideDownBtn).click(function(){
		var current = jQuery(this).closest('.tab-content');
		current.next().after(current);
	});

	jQuery(delBtn).on('click', function (e) {
		e.preventDefault();

		var tabToDel = jQuery(this).closest('.tab-content');
		var slideName = jQuery(tabToDel).find('.admin-custom__slide-name').find('span').html();

		var isDeleteConfirmed = confirm('Вы уверены, что хотите удалить слайд ' + slideName + '?');

		if (isDeleteConfirmed) {
			tabToDel.remove();
		}

	});

	jQuery.each(tabs, function (index, tab) {
		var imgSrc = jQuery(".img-polaroid", tab).attr('src');
		var title = jQuery("input[name*='[name]']", tab).val();
		var time = jQuery("input[name*='[publication_end_date]']", tab).val();
		var isHided = jQuery("input[name*='[status]']:checked", tab).val() === 'hide';

		jQuery(".admin-custom__img-preview", tab).find("img").prop('src', imgSrc);
		jQuery(".admin-custom__slide-name", tab).find("span").html(title);

		if (time) {
			time = time.split(' ')[0];
			jQuery(".admin-custom__time", tab).find("span").html('Показ до ' + time);
		}

		if (isHided) {
			jQuery(".admin-custom__time", tab).find("span").html('Скрыт');
		}

	});

	jQuery(".admin-custom__table", ".tab-content", ".admin-custom").on('click', function (e) {
		if (jQuery(e.target).hasClass("ui-icon")) {
			return false;
		}

		jQuery(".tab-content", ".admin-custom").removeClass('active');

		var tab = jQuery(this).closest(".tab-content", ".admin-custom");
		jQuery(tab).toggleClass('active');

		slideType = jQuery("input[name*='[type]']:checked", tab).val();
		slideTimeType = jQuery("input[name*='[status]']:checked", tab).val();

		toggleTypeContainer(slideType, tab);
		toggleTimeContainer(slideTimeType, tab);

		var slideTypeBtns = jQuery("label[for*='_type']", tab);
		var slideTypeBtns2 = jQuery(".iCheck-helper", tab);
		jQuery(slideTypeBtns).click(function (e) {
			slideType = jQuery("input[name*='[type]']:checked", tab).val();
			toggleTypeContainer(slideType, tab);
		});

		jQuery(slideTypeBtns2).click(function (e) {
			slideType = jQuery("input[name*='[type]']:checked", tab).val();
			toggleTypeContainer(slideType, tab);
		});

		var slideTimeBtns = jQuery("label[for*='_status']", tab);
		jQuery(slideTimeBtns).click(function (e) {
			slideTimeType = jQuery("input[name*='[status]']:checked", tab).val();
			toggleTimeContainer(slideTimeType, tab);
		});
	});

	jQuery(".ui-icon", ".admin-custom__table", ".admin-custom").on('click', function () {
		jQuery(this).closest(".tab-content").removeClass('active');
	});

	function toggleTypeContainer(prop, tab) {
		if (prop === 'img') {
			jQuery("div[class*='_slides-media']", tab).show();
			jQuery("div[class*='_slides-url']", tab).show();
			jQuery("div[class*='_slides-text']", tab).hide();
		} else {
			jQuery("div[class*='_slides-text']", tab).show();
			jQuery("div[class*='_slides-media']", tab).hide();
			jQuery("div[class*='_slides-url']", tab).hide();
		}
	}

	function toggleTimeContainer(prop, tab) {
		if (prop === 'time') {
			jQuery("div[class*='_slides-is_user']", tab).show();
			jQuery("div[class*='_slides-delay']", tab).show();
			jQuery("div[class*='_slides-publication_start_date']", tab).show();
			jQuery("div[class*='_slides-publication_end_date']", tab).show();
		} else if (prop === 'hide') {
			jQuery("div[class*='_slides-publication_end_date']", tab).hide();
			jQuery("div[class*='_slides-publication_start_date']", tab).hide();
			jQuery("div[class*='_slides-is_user']", tab).hide();
			jQuery("div[class*='_slides-delay']", tab).hide();
		} else {
			jQuery("div[class*='_slides-is_user']", tab).show();
			jQuery("div[class*='_slides-delay']", tab).show();
			jQuery("div[class*='_slides-publication_end_date']", tab).hide();
			jQuery("div[class*='_slides-publication_start_date']", tab).hide();
		}
	}
});