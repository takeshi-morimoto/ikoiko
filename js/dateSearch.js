$(function(){
	// DOM要素が存在しない場合は処理を中断
	if ($('#dateSearch_inner').length === 0 || $('#next').length === 0 || $('#prev').length === 0) {
		return;
	}

	var pos = 0,
		set = 0;

	$('#next').on('click',function(){

		if ( pos == 2 ){

			set = pos * -100;

		} else {

			pos++;
			set = pos * -100;

		}

		$('#dateSearch_inner').animate({

				'left' : set + 5 + '%'
			},

				1000
		);
	});

	$('#prev').on('click',function(){

		if ( pos == 0 ){

			set = pos * -100;

		} else {

			pos--;
			set = pos * -100;

		}

		$('#dateSearch_inner').animate({

				'left' : set + 5 + '%'
			},

				1000
		);
	});


});