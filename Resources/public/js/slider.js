$(function () {
	var tab = '';
	var slideType = '';
	var slideTimeType = '';

	var slidesData = {};

	var tabs = $(".tab-content", ".admin-custom");
	var delBtn = $(".ui-icon-trash", ".tab-content", ".admin-custom");

	var moveSlideUpBtn = $(".admin-custom__arrow_up", ".admin-custom");
	var moveSlideDownBtn = $(".admin-custom__arrow_down", ".admin-custom");

	$(moveSlideUpBtn).click(function(){
		var current = $(this).closest('.tab-content');
		current.prev().before(current);
	});
	$(moveSlideDownBtn).click(function(){
		var current = $(this).closest('.tab-content');
		current.next().after(current);
	});

	$(delBtn).on('click', function (e) {
		e.preventDefault();

		var tabToDel = $(this).closest('.tab-content');
		var slideName = $(tabToDel).find('.admin-custom__slide-name').find('span').html();

		var isDeleteConfirmed = confirm('Вы уверены, что хотите удалить слайд ' + slideName + '?');

		if (isDeleteConfirmed) {
			tabToDel.remove();
		}

	});

	function setInitialSlideData(slide) {
		var param = $(slide).index();

		slidesData[param] = {};
		slidesData[param].geo = [];

		slidesData[param].slide = slide[0];
		slidesData[param].slideName = $(slide[0]).find('.admin-custom__slide-name').find('span').html();
		slidesData[param].imageName = $(slide[0]).find('.admin-custom__media-title').html();
		slidesData[param].isHTML = $("input[name*='[type]']:checked", slide[0]).val() === 'text';
		slidesData[param].imgSrc = $(".img-polaroid", slide[0]).attr('src');
		slidesData[param].isHidden = $("input[name*='[status]']:checked", slide[0]).val() === 'hide';
		slidesData[param].isTemporary = $("input[name*='[status]']:checked", slide[0]).val() === 'time';
		slidesData[param].time = $("input[name*='[publication_end_date]']", slide[0]).val();
		slidesData[param].isForAuthorized = $("input[name*='[is_user]']:checked", slide[0]);
		slidesData[param].interval = $("input[name*='[delay]']", slide[0]).val();
		slidesData[param].url = $("input[name*='[url]']", slide[0]).val();
		slidesData[param].slideHTML = $("textarea[name*='[text]']", slide[0]).val();

		var chosenCities = $('.select2-container', '.select2-choices', slide[0]).find('div');
		if (chosenCities.length > 1) {
			$.each(chosenCities, function (city) {
				slidesData[param].geo.push($(city).html());
			});

		} else if (chosenCities.length) {
			slidesData[param].geo.push($(chosenCities).html());
		}
	}

	function resetSlideContent(slide, data) {
		var slideName = $(slide).find("input[name*='[name]']");

		var isHTMLbtn = $(slide).find("input[name*='[type]'][value='text']");
		var isImgBtn = $(slide).find("input[name*='[type]'][value='img']");
		
		var slideImg = $(slide).find(".img-polaroid");
		var slidePreviewImg = $(slide).find(".admin-custom__media-overlay");
		var slidePreviewTitle = $(slide).find(".admin-custom__media-title");
		var $uploadForm = $(slide).find(".admin-custom__media-view");

		var isHiddenBtn = $(slide).find("input[name*='[status]'][value='hide']");
		var isTemporaryBtn = $(slide).find("input[name*='[status]'][value='time']");
		var isShownBtn = $(slide).find("input[name*='[status]'][value='show']");

		var startTimeContainer = $(slide).find("input[name*='[publication_start_date]']");
		var endTimeContainer = $(slide).find("input[name*='[publication_end_date]']");

		var intervalContainer = $(slide).find("input[name*='[delay]']");
		var urlContainer = $(slide).find("input[name*='[url]']");
		var htmlContainer = $(slide).find("textarea[name*='[text]']");

		//var geoContainer = $(slide).find("input[name*='[is_user]']");

		var isForAuthorizedBtn = $(slide).find("input[name*='[is_user]']");

		$(slideName).val(data.slideName);

		$(isHTMLbtn).attr('checked', false);
		$(isImgBtn).attr('checked', false);
		
		data.isHTML && isHTMLbtn.attr('checked', 'checked') && $(isHTMLbtn).closest('label').click();
		!data.isHTML && isImgBtn.attr('checked', 'checked') && $(isImgBtn).closest('label').click();

		if (!data.imageName) {
			$uploadForm.removeClass('active');
			slideImg.remove();
			slidePreviewImg.css('backgroundImage', null);
			slidePreviewTitle.remove();
		} else {
			slideImg.prop('src', data.imgSrc);
			slidePreviewImg.css('backgroundImage', 'url(' + data.imgSrc + ')');
			slidePreviewTitle.html(data.imageName);
		}

		$uploadForm.find('input[type="file"]').val('');

		$(isHiddenBtn).attr('checked', false);
		$(isTemporaryBtn).attr('checked', false);
		$(isShownBtn).attr('checked', false);

		!data.isTemporary && data.isHidden && $(isHiddenBtn).attr('checked', true) && $(isHiddenBtn).closest('label').click();
		!data.isHidden && data.isTemporary && $(isTemporaryBtn).attr('checked', true) && $(isTemporaryBtn).closest('label').click();
		!data.isHidden && !data.isTemporary && $(isShownBtn).attr('checked', true) && $(isShownBtn).closest('label').click();

		intervalContainer.val(data.interval);
		urlContainer.val(data.url);
		htmlContainer.val(data.slideHTML);

		$(isForAuthorizedBtn).attr('checked', false);

		if (data.isForAuthorized.length) {
			$(isForAuthorizedBtn).attr('checked', true) && $(isForAuthorizedBtn).parent().addClass('checked');
		} else {
			$(isForAuthorizedBtn).attr('checked', false) && $(isForAuthorizedBtn).parent().removeClass('checked');
		}
	}

	function getInitialSlideData(slide) {
		var param = $(slide).index();

		if (!slidesData[param]) {
			return false;
		}

		return slidesData[param];
	}

	function updatePreviewLine(slideWrap) {
		var previewImg = $(".admin-custom__img-preview", slideWrap).find("img");
		var imgSrc = $(".img-polaroid", slideWrap).attr('src');
		var isHTML = $("input[name*='[type]']:checked", slideWrap).val() === 'text';

		var nameContainer = $(".admin-custom__slide-name", slideWrap).find("span");
		var name = $("input[name*='[name]']", slideWrap).val();

		var isHidden = $("input[name*='[status]']:checked", slideWrap).val() === 'hide';
		var timeContainer = $(".admin-custom__time", slideWrap).find("span");
		var time = $("input[name*='[publication_end_date]']", slideWrap).val();

		if (time || isHidden) {
			if (isHidden && $(timeContainer).html() !== 'Скрыт') {
				$(timeContainer).html('Скрыт');
			} else if ($(timeContainer).html() !== 'Показ до ' +  time) {
				time = time.split(' ')[0];
				$(timeContainer).html('Показ до ' + time);
			}
		}

		$(nameContainer).html() !== name && $(nameContainer).html(name);

		isHTML && $(previewImg).prop('src', '');
		$(previewImg).attr('src') !== imgSrc && $(previewImg).prop('src', imgSrc);
	}

	function updateSlide(slideWrap) {

		var activeTabImg = $(".img-polaroid", slideWrap);

		if (activeTabImg.length) {
			var previewOverlay = $(slideWrap).find('.admin-custom__media-overlay');
			$(previewOverlay).css('backgroundImage', 'url(' + $(activeTabImg).attr('src') + ')');
		}

	}

	$.each(tabs, function (index, tab) {

		updatePreviewLine(tab);

	});

	$(".admin-custom__table", ".tab-content", ".admin-custom").on('click', function (e) {
		if ($(e.target).hasClass("ui-icon")) {
			return false;
		}

		var activeTab = $(".tab-content.active", ".admin-custom");
		updatePreviewLine(activeTab);

		$(tabs).removeClass('active');

		var tab = $(this).closest(".tab-content", ".admin-custom");
		$(tab).addClass('active');

		slideType = $("input[name*='[type]']:checked", tab).val();
		slideTimeType = $("input[name*='[status]']:checked", tab).val();

		toggleTypeContainer(slideType, tab);
		toggleTimeContainer(slideTimeType, tab);

		var slideTypeBtns = $("label[for*='_type']", tab);
		var slideTypeBtns2 = $(".iCheck-helper", tab);
		$(slideTypeBtns).click(function (e) {
			slideType = $("input[name*='[type]']:checked", tab).val();
			toggleTypeContainer(slideType, tab);
		});

		$(slideTypeBtns2).click(function (e) {
			slideType = $("input[name*='[type]']:checked", tab).val();
			var slideStatus = $("input[name*='[status]']:checked", tab).val();

			toggleTypeContainer(slideType, tab);
			toggleTimeContainer(slideStatus, tab);

		});

		var slideTimeBtns = $("label[for*='_status']", tab);
		$(slideTimeBtns).click(function (e) {
			slideTimeType = $("input[name*='[status]']:checked", tab).val();
			toggleTimeContainer(slideTimeType, tab);
		});

		updateSlide(tab);
		!getInitialSlideData(tab) && setInitialSlideData(tab);
	});

	$(".admin-custom__save-btn", ".admin-custom").on('click', function () {
		var currentTab = $(this).closest(".tab-content");
		updatePreviewLine(currentTab);
		setInitialSlideData(currentTab);

		$(currentTab).removeClass('active');
	});

	$(".admin-custom__reset-btn", ".admin-custom").on('click', function () {
		var currentTab = $(this).closest(".tab-content");

		var initialData = getInitialSlideData(currentTab);

		//console.log(initialData);
		resetSlideContent(currentTab, initialData);
		//updatePreviewLine(currentTab);
	});

	function toggleTypeContainer(prop, tab) {
		if (prop === 'img') {
			$("div[class*='_slides-media']", tab).show();
			$("div[class*='_slides-url']", tab).show();
			$("div[class*='_slides-text']", tab).hide();
		} else {
			$("div[class*='_slides-text']", tab).show();
			$("div[class*='_slides-media']", tab).hide();
			$("div[class*='_slides-url']", tab).hide();
		}
	}

	function toggleTimeContainer(prop, tab) {
		if (prop === 'time') {
			$("div[class*='_slides-is_user']", tab).show();
			$("div[class*='_slides-delay']", tab).show();
			$("div[class*='_slides-citys']", tab).show();
			$("div[class*='_slides-name']", tab).show();
			$("div[class*='_slides-publication_start_date']", tab).show();
			$("div[class*='_slides-publication_end_date']", tab).show();
		} else if (prop === 'hide') {
			$("div[class*='_slides-publication_end_date']", tab).hide();
			$("div[class*='_slides-publication_start_date']", tab).hide();
			$("div[class*='_slides-is_user']", tab).hide();
			$("div[class*='_slides-delay']", tab).hide();
			$("div[class*='_slides-citys']", tab).hide();
			$("div[class*='_slides-name']", tab).hide();
		} else {
			$("div[class*='_slides-is_user']", tab).show();
			$("div[class*='_slides-delay']", tab).show();
			$("div[class*='_slides-citys']", tab).show();
			$("div[class*='_slides-name']", tab).show();
			$("div[class*='_slides-publication_end_date']", tab).hide();
			$("div[class*='_slides-publication_start_date']", tab).hide();
		}
	}
});