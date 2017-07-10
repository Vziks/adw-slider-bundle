$(function () {

	//WARNING! Most of selectors are hard coded due to back functionality

	var tab = '';
	var slideType = '';
	var slideTimeType = '';

	var slidesData = {};
	var dataObj = {};
	var slidesCurrentData = {};

	var tabs = $(".tab-content", ".admin-custom");
	var delBtn = $(".ui-icon-trash", ".tab-content", ".admin-custom");

	var moveSlideUpBtn = $(".admin-custom__arrow_up", ".admin-custom");
	var moveSlideDownBtn = $(".admin-custom__arrow_down", ".admin-custom");

	$(moveSlideUpBtn).click(function(){
		var currentSlide = $(this).closest('.tab-content');
		var currentIndex = $(currentSlide).index() + 1;

		if (currentIndex === 1) {
			return false;
		}

		var prevSlide = tabs[currentIndex - 2];

		var slidePosition = $('.admin-custom__position', currentSlide);
		var prevSlidePosition = $('.admin-custom__position', prevSlide);

		slidePosition.html(currentIndex - 1);
		prevSlidePosition.html(currentIndex);

		$("input[name*='[sort]']", currentSlide).val(currentIndex - 1);
		$("input[name*='[sort]']", prevSlide).val(currentIndex);

		currentSlide.prev().before(currentSlide);
		tabs = $(".tab-content", ".admin-custom");
	});

	$(moveSlideDownBtn).click(function(){
		var currentSlide = $(this).closest('.tab-content');
		var currentIndex = $(currentSlide).index() + 1;

		if (currentIndex === tabs.length) {
			return false;
		}

		var nextSlide = tabs[currentIndex];

		var slidePosition = $('.admin-custom__position', currentSlide);
		var nextSlidePosition = $('.admin-custom__position', nextSlide);

		slidePosition.html(currentIndex + 1);
		nextSlidePosition.html(currentIndex);

		$("input[name*='[sort]']", currentSlide).val(currentIndex + 1);
		$("input[name*='[sort]']", nextSlide).val(currentIndex);

		currentSlide.next().after(currentSlide);
		tabs = $(".tab-content", ".admin-custom");
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
	
	function checkForUpdates(currentTab) {
		var currentTabInputs = $(currentTab).find('input');
		var currentTabFileInput = $(currentTab).find("input[type='file']");
		var currentTabTextarea = $(currentTab).find('textarea');
		var currentTabSaveBtn = $(currentTab).find('.admin-custom__save-btn');

		var toggleSaveBtn = function () {
			if (currentTabSaveBtn.hasClass('disabled')) {
				currentTabSaveBtn.removeClass('disabled');
			}

			$(this).off('ifChanged', toggleSaveBtn);
			$(this).off('change', toggleSaveBtn);

			this !== currentTabFileInput && currentTabFileInput.off('change', toggleSaveBtn);
			this !== currentTabTextarea && currentTabTextarea.off('change', toggleSaveBtn);
		};

		$.each(currentTabInputs, function (index, tabInput) {
			$(tabInput).on('ifChanged', toggleSaveBtn);
			$(tabInput).on('change', toggleSaveBtn);
		});

		currentTabFileInput.on('change', toggleSaveBtn);
		currentTabTextarea.on('change', toggleSaveBtn);
		$(moveSlideUpBtn).on('click', toggleSaveBtn);
		$(moveSlideDownBtn).on('click', toggleSaveBtn);
	}

	function setSlideData(slide, type, id) {
		dataObj = type === 'current' ? slidesCurrentData : slidesData;

		var param = id;

		dataObj[param] = {};

		dataObj[param].sliderId = $(slide).find("div[class*='_slides-slider__id']").find('input').val();
		dataObj[param].slideId = id;
		dataObj[param].slidePosition = $("input[name*='[sort]']", slide).val();
		dataObj[param].isHTML = $("input[name*='[type]']:checked", slide).val() === 'text';
		dataObj[param].url = $("input[name*='[url]']", slide).val();
		dataObj[param].slideHTML = $("textarea[name*='[text]']", slide).val();
		dataObj[param].imageName = $(slide).find('.admin-custom__media-title').html();
		dataObj[param].imageSrc = $(".img-polaroid", slide).attr('src');
		dataObj[param].isHidden = $("input[name*='[status]']:checked", slide).val() === 'hide';
		dataObj[param].isTemporary = $("input[name*='[status]']:checked", slide).val() === 'time';
		dataObj[param].isForAuthorized = $("input[name*='[is_user]']:checked", slide).val() > 0;
		dataObj[param].geo = [];
		dataObj[param].startTime = $("input[name*='[publication_end_date]']", slide).val();
		dataObj[param].endTime = $("input[name*='[publication_end_date]']", slide).val();
		dataObj[param].interval = $("input[name*='[delay]']", slide).val();
		dataObj[param].slideName = $("input[name*='[name]']", slide).val();

		var chosenCities = $(slide).find('.select2-choices').find('div');
		var chosenCitiesVal = $(slide).find("div[id*='_citys_hidden_inputs_wrap']").find('input');

		var len = chosenCities.length;
		for (var i = 0; i < len; i++) {
			dataObj[param].geo.push({
				id: $(chosenCitiesVal[i]).val(),
				label: $(chosenCities[i]).html()
			});
		}

		slidesCurrentData = type === 'current' ? dataObj : slidesCurrentData;
		slidesData = type === 'current' ? slidesData : dataObj;
	}

	function resetSlideContent(slide, data) {
		var slideName = $(slide).find("input[name*='[name]']");

		var slidePosition = $(slide).find("input[name*='[sort]']");

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

		var geoContainer = $(slide).find("div[id*='_citys_hidden_inputs_wrap']");
		var select2CityInput = $(slide).find("input[id*='_autocomplete_input']");

		var isForAuthorizedBtn = $(slide).find("input[name*='[is_user]']");

		$(slideName).val(data.slideName);
		
		data.isHTML && $(isHTMLbtn).iCheck('check');
		!data.isHTML && $(isImgBtn).iCheck('check');

		if (data.slidePosition !== $(slidePosition).val()) {

			function swapNodes(a, b) {
				var aparent = a.parentNode;
				var asibling = a.nextSibling === b ? a : a.nextSibling;
				b.parentNode.insertBefore(a, b);
				aparent.insertBefore(b, asibling);
			}

			var currentSlidePosition = $(slidePosition).val();
			var slideToReplace = $( "input[name$='[sort]'][value = " + data.slidePosition + "]" ).closest('.tab-content');

			var slidePositionWrap = $('.admin-custom__position', slide);
			var prevSlide = tabs[$(slideToReplace).index()];

			swapNodes(slide[0], prevSlide);

			var prevSlidePosition = $('.admin-custom__position', prevSlide);

			slidePositionWrap.html(data.slidePosition);
			prevSlidePosition.html(currentSlidePosition);

			$("input[name*='[sort]']", slide).val(data.slidePosition);
			$("input[name*='[sort]']", prevSlide).val(currentSlidePosition);

			tabs = $(".tab-content", ".admin-custom");
		}

		if (!data.imageName) {
			$uploadForm.removeClass('active');
			slideImg.remove();
			slidePreviewImg.css('backgroundImage', null);
			slidePreviewTitle.remove();
		} else {
			slideImg.prop('src', data.imageSrc);
			slidePreviewImg.css('backgroundImage', 'url(' + data.imageSrc + ')');
			slidePreviewTitle.html(data.imageName);
		}

		$uploadForm.find('input[type="file"]').val('');

		!data.isTemporary && data.isHidden && $(isHiddenBtn).iCheck('check');
		!data.isHidden && data.isTemporary && $(isTemporaryBtn).iCheck('check');
		!data.isHidden && !data.isTemporary && $(isShownBtn).iCheck('check');

		intervalContainer.val(data.interval);
		urlContainer.val(data.url);
		htmlContainer.val(data.slideHTML);

		data.isForAuthorized && $(isForAuthorizedBtn).iCheck('check');
		!data.isForAuthorized && $(isForAuthorizedBtn).iCheck('uncheck');

		$(startTimeContainer).val(data.startTime);
		$(endTimeContainer).val(data.endTime);

		if (data.geo.length) {
			var sessionId = $(geoContainer).attr('id').split('_')[0];
			var sessionNum = $(geoContainer).attr('id').split('_')[2];

			var removedItems = [];
			var itemsToAppend = data.geo;

			$(select2CityInput).on('change', function (e) {
				$(geoContainer).children().remove();

				if (undefined !== e.removed && null !== e.removed) {
					if ($.inArray( e.removed, removedItems ) === -1) {
						removedItems.push(e.removed);
					}

					var length = removedItems.length;
					for (var i = 0; i < length; i++) {
						$(geoContainer).find('[value="'+data.geo[i].id +'"]').remove();

						itemsToAppend = itemsToAppend.filter(function(item) { return item !== removedItems[i]; });
					}
				}

				for (var j = 0; j < itemsToAppend.length; j++) {
					$(geoContainer).append('<input type="hidden" name="' + sessionId + '[slides][' + sessionNum + '][citys][]" value="' + itemsToAppend[j].id + '">');
				}

			});

			$(select2CityInput).empty().select2('data', data.geo).change();
		}

		var currentTabSaveBtn = $(slide).find('.admin-custom__save-btn');
		!$(currentTabSaveBtn).hasClass('disabled') && $(currentTabSaveBtn).addClass('disabled') && checkForUpdates(slide);
	}

	function getInitialSlideData(id) {
		if (!slidesData[id]) {
			return false;
		}

		return slidesData[id];
	}

	function getCurrentSlideData(id) {
		if (!slidesCurrentData[id]) {
			return false;
		}

		return slidesCurrentData[id];
	}

	function updatePreviewLine(slideWrap) {
		var previewImg = $(".admin-custom__img-preview", slideWrap).find("img");
		var previewHTML = $(".admin-custom__img-preview", slideWrap).find("span");
		var imgSrc = $(".img-polaroid", slideWrap).attr('src');
		var isHTML = $("input[name*='[type]']:checked", slideWrap).val() === 'text';

		var nameContainer = $(".admin-custom__slide-name", slideWrap).find("span");
		var name = $("input[name*='[name]']", slideWrap).val();

		var isHidden = $("input[name*='[status]']:checked", slideWrap).val() === 'hide';
		var isTemporary = $("input[name*='[status]']:checked", slideWrap).val() === 'time';
		var timeContainer = $(".admin-custom__time", slideWrap).find("span");
		var time = $("input[name*='[publication_end_date]']", slideWrap).val();

		if (time || isHidden || isTemporary) {
			if (isHidden && $(timeContainer).html() !== 'Скрыт') {
				$(timeContainer).html('Скрыт');
			} else if (isTemporary && $(timeContainer).html() !== 'Показ до ' +  time) {
				time = time.split(':')[0];
				time = time.substring(0, time.length - 2);
				$(timeContainer).html('Показ до ' + time);
			} else {
				$(timeContainer).html('');
			}
		}

		$(nameContainer).html() !== name && $(nameContainer).html(name);

		isHTML && $(previewImg).prop('src', '') && $(previewHTML).show();
		!isHTML && $(previewHTML).hide() && $(previewImg).attr('src') !== imgSrc && $(previewImg).prop('src', imgSrc);
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

	$(".tab-content", ".admin-custom").on('click', function (e) {
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

		var slideTypeBtns = $("input[name*='[type]']", tab);
		var slideTimeTypeBtns = $("input[name*='[status]']", tab);

		$(slideTypeBtns).on('ifChecked', function (e) {
			slideType = $(this).val();
			toggleTypeContainer(slideType, tab);
		});
		$(slideTimeTypeBtns).on('ifChecked', function (e) {
			slideTimeType = $(this).val();
			toggleTimeContainer(slideTimeType, tab);
		});

		updateSlide(tab);

		var tabId = $(tab).find("div[class*='_slides-id']").find('input').val();

		!getInitialSlideData(tabId) && setSlideData(tab, false, tabId);
		checkForUpdates(tab);
	});

	$(".admin-custom__save-btn", ".admin-custom").on('click', function () {
		if ($(this).hasClass('disabled')) {
			return false;
		}

		var urlToSendData = $(this).data('url');
		var currentTab = $(this).closest(".tab-content");
		var that = this;

		var tabId = $(currentTab).find("div[class*='_slides-id']").find('input').val();

		setSlideData(currentTab, 'current', tabId);
		var dataToSend = getCurrentSlideData(tabId);

		$.ajax({
			type: "post",
			data: dataToSend,
			url: urlToSendData,
			dataType: 'json',
			beforeSend: function () {
				$(that).addClass('disabled');
			}
		}).done(function (data) {
			if (data.response) {

				if (data.response.slideId) {
					$(currentTab).find("div[class*='_slides-id']").find('input').val(data.response.slideId);
				}

				$(currentTab).removeClass('active');
				updatePreviewLine(currentTab);

				setSlideData(currentTab, false, tabId);
			} else {
				$(that).removeClass('disabled');
				console.log('error');
			}
		}).fail(function () {
			$(that).removeClass('disabled');
			console.log('error');
		});

	});

	$(".admin-custom__reset-btn", ".admin-custom").on('click', function () {
		var currentTab = $(this).closest(".tab-content");
		var tabId = $(currentTab).find("div[class*='_slides-id']").find('input').val();

		var initialData = getInitialSlideData(tabId);
		resetSlideContent(currentTab, initialData);
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