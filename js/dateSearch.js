$(function(){

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