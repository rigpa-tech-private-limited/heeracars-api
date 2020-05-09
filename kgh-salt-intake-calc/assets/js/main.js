(function($) {

	skel.breakpoints({
		xlarge: '(max-width: 1680px)',
		large: '(max-width: 1280px)',
		medium: '(max-width: 980px)',
		small: '(max-width: 736px)',
		xsmall: '(max-width: 480px)'
	});

	$(function() {

		var	$window = $(window),
			$body = $('body');

		// Disable animations/transitions until the page has loaded.
			$body.addClass('is-loading');

			$window.on('load', function() {
				window.setTimeout(function() {
					$body.removeClass('is-loading');
				}, 100);
			});

		// Fix: Placeholder polyfill.
			$('form').placeholder();

		// Prioritize "important" elements on medium.
			skel.on('+medium -medium', function() {
				$.prioritize(
					'.important\\28 medium\\29',
					skel.breakpoint('medium').active
				);
			});

		// Scrolly.
			$('.scrolly').scrolly();

	});
	
	$(document).ready(function(){
		
		$('.submit').click(function(){
			validateForm();   
		});
		$('form').submit(function () {

			var isFormValid = false;
			var nameReg = /^[A-Za-z]+$/;
			var name = $.trim($('#name').val());
			var numberReg =  /^[0-9]+$/;
			var mobile = $.trim($('#mobile').val());
			var age = $.trim($('#age').val());
			var height = $.trim($('#height').val());
			var weight = $.trim($('#weight').val());
			var sodium = $.trim($('#sodium').val());
			var creatinine = $.trim($('#creatinine').val());
		
			$('.error-msg').html("&nbsp;");
			if(name === ''){
				$('#name').nextAll('.error-msg:first').text('Please enter your name');
				isFormValid = false;
			}  else if(!nameReg.test(name)){
				$('#name').nextAll('.error-msg:first').text('Please enter a valid name');
				isFormValid = false;
			} else {
				$('#name').nextAll('.error-msg:first').html("&nbsp;");
				isFormValid = true;
			}

			if(mobile === ''){
				$('#mobile').nextAll('.error-msg:first').text('Please enter your mobile number');
				isFormValid = false;
			}  else if(!numberReg.test(mobile)){
				$('#mobile').nextAll('.error-msg:first').text('Please enter a valid mobile number');
				isFormValid = false;
			} else {
				$('#mobile').nextAll('.error-msg:first').html("&nbsp;");
				isFormValid = true;
			}

			if(age === ''){
				$('#age').nextAll('.error-msg:first').text('Please enter your age');
				isFormValid = false;
			}  else if(!numberReg.test(age)){
				$('#age').nextAll('.error-msg:first').text('Please enter a valid age');
				isFormValid = false;
			} else {
				$('#age').nextAll('.error-msg:first').html("&nbsp;");
				isFormValid = true;
			}

			if(height === ''){
				$('#height').nextAll('.error-msg:first').text('Please enter your height');
				isFormValid = false;
			} else {
				$('#height').nextAll('.error-msg:first').html("&nbsp;");
				isFormValid = true;
			}

			if(weight === ''){
				$('#weight').nextAll('.error-msg:first').text('Please enter your weight');
				isFormValid = false;
			} else {
				$('#weight').nextAll('.error-msg:first').html("&nbsp;");
				isFormValid = true;
			}

			if(sodium === ''){
				$('#sodium').nextAll('.error-msg:first').text('Please enter sodium value');
				isFormValid = false;
			} else {
				$('#sodium').nextAll('.error-msg:first').html("&nbsp;");
				isFormValid = true;
			}

			if(creatinine === ''){
				$('#creatinine').nextAll('.error-msg:first').text('Please enter creatinine value');
				isFormValid = false;
			} else {
				$('#creatinine').nextAll('.error-msg:first').html("&nbsp;");
				isFormValid = true;
			}
			Swal.fire({
				title: '<strong><u>Prediction</u></strong>',
				icon: 'success',
				html:
					'Your current estimated salt intake  based on the Provided details is <b>5 g/day</b>, '+
					'<br><span class="result-help-txt">The prescribed salt intake as per WHO Standards is  5  grams/day</span>'+
					'<br><br>'+
					'<div class="diet-workout-holder">'+
					'<div class="diet-holder">'+
					'<div class="diet-icon-div">'+
					'<i class="fa fa-cutlery"></i>'+
						'<h4>Diet</h4>'+
						'</div>'+
						'<div class="diet-txt-div">'+
							'<p>Use Low Sodium Salt  Available in market<br>Reduce the intake of  Snack /Bakery products like cake  /Biscuits/Bread<br>Reduce the intake of Pickle ,Pappad and Tinned Products</p>'+
						'</div>'+
					'</div>'+
					'<div class="workout-holder">'+
						'<div class="workout-icon-div">'+
						'<i class="fa fa-sun-o"></i>'+
						'<h4>Workout</h4>'+
						'</div>'+
						'<div class="workout-txt-div">'+
							'<p>Regular Walking<br>Mild Aerobic Exercise</p>'+
						'</div>'+
					'</div>'+
					'</div>'+
					'<div class="popup-bottom-help-txt">'+
					'<p class="p-txt">* This prediction is not valid for patients aged below 18 years and above 80 Years'+
					'</p>'+
					'<p class="p-txt">* This prediction is not valid for Chronic Kidney Disease or Diuretic people'+
					'</p>'+
					'</div>',
				showCloseButton: false,
				showCancelButton: false,
				focusConfirm: false,
				confirmButtonText:
					'<i class="fa fa-thumbs-up"></i> Ok!',
				confirmButtonAriaLabel: 'Thumbs up, Ok!',
				cancelButtonText:
					'<i class="fa fa-thumbs-down"></i>',
				cancelButtonAriaLabel: 'Thumbs down'
				});
			return false;
		});
		
		});

})(jQuery);
function calculateBMI() {
			
	var height = $.trim($('#height').val());
	var weight = $.trim($('#weight').val());
	if (height > 0 && weight > 0) {
	  var bmi;
	  var heightInM2 = (height * height);
	  console.log("heightInM2", heightInM2);
	  bmi = (weight / heightInM2).toFixed(2);
	  console.log("calculateBMI", bmi);
	  $('#bmi').val(bmi);
	} else {
		$('#bmi').val("");
	}
  }