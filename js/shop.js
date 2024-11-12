$(function(){


	/* カテゴリーのドロップダウンリスト
	/*-------------------------------------------*/

		var DDL 		= $('#headerRow2'),
			DDL_button	= DDL.find('.showDDL'),
			DDL_list	= DDL.find('.dropDownList'),
			DDL_a		= DDL_list.find('.primaryLi').find('>a'),
			DDL_secondUl= DDL_list.find('ul.secondaryCat'),
			ather 		= $("*:not(#dropDownList)");
			duration_1	= 200;

		// 高さの調整
		DDL_secondUl.each(function(){

			var r = DDL_secondUl.index(this);
			$(this).css( 'top', r * -100 + '%' );
		});

		DDL_button.stop(true).on('mouseover', function(){

			DDL_list.slideToggle(300);

		});


		DDL_a.stop(true).on('mouseover', function(e){

			e.preventDefault();
			var thisUl = $(this).next('ul');

			DDL_secondUl.css( { 'display' : 'none' } );
			thisUl.animate( { width: 'show' }, 300 );
			
			
		});

		DDL_list.stop(true).on('mouseleave', function(e){

			DDL_list.css( { 'display' : 'none' } );

			
		});


	/* グローバルカテゴリーメニューの装飾
	/*-------------------------------------------*/

		var gCat 		= $('#gCat'),
			gCatLi		= gCat.find('li'),
			gCatDiv		= gCatLi.find('div');

		gCatDiv.stop(true)

			.on('mouseover', function(){

				$(this).find('a').animate({ 

					'top' : '-60px'

				}, duration_1 );

				$(this).next('small').animate({ 

					'opacity' : 1

				}, duration_1 );

			})

			.on('mouseout', function(){

				$(this).find('a').animate({ 

					'top' : '0px'

				}, duration_1 );

				$(this).next('small').animate({ 

					'opacity' : 0

				}, duration_1 );

			});



	/* スティッキーヘッダー
	/*-------------------------------------------*/

	var		headerObj 			= $('#headerWrap'),
			steckyHeader		= $('#StickyHeader'),
			scroll				= $(window).scrollTop(),
			headerHeight		= headerObj.height(),
			headerTop			= headerObj.offset().top;


	function enableStkHeader(){

		steckyHeader.removeClass("disableStk");

	}

	function disableStkHeader(){

		steckyHeader.addClass("disableStk");

	}

	// スクロールなどを監視して処理
	$(window).on('scroll',function(){

		// スクロール監視用の変数
		scroll = $(window).scrollTop();

		if ( scroll > headerTop + headerHeight ){

			enableStkHeader();
		}

		else if ( scroll < headerTop + headerHeight ){

			disableStkHeader();

		}


	});


});