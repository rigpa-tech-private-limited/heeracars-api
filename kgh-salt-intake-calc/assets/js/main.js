(function ($) {

	skel.breakpoints({
		xlarge: '(max-width: 1680px)',
		large: '(max-width: 1280px)',
		medium: '(max-width: 980px)',
		small: '(max-width: 736px)',
		xsmall: '(max-width: 480px)'
	});

	$(function () {

		var $window = $(window),
			$body = $('body');

		// Disable animations/transitions until the page has loaded.
		$body.addClass('is-loading');

		$window.on('load', function () {
			window.setTimeout(function () {
				$body.removeClass('is-loading');
			}, 100);
		});

		// Fix: Placeholder polyfill.
		$('form').placeholder();

		// Prioritize "important" elements on medium.
		skel.on('+medium -medium', function () {
			$.prioritize(
				'.important\\28 medium\\29',
				skel.breakpoint('medium').active
			);
		});

		// Scrolly.
		$('.scrolly').scrolly();

	});

	$(document).ready(function () {

		$('.submit').click(function () {
			validateForm();
		});
		$('.form-submit').click(function () {

			var isNameValid = false;
			var isMobileValid = false;
			var isAgeValid = false;
			var isGenderValid = false;
			var isHeightValid = false;
			var isWeightValid = false;
			var isSodiumValid = false;
			var isCreatinineValid = false;
			var nameReg = /^[A-Za-z]+$/;
			var name = $.trim($('#name').val());
			var numberReg = /^[0-9]+$/;
			var mobile = $.trim($('#mobile').val());
			var age = $.trim($('#age').val());
			var gender = $(".select-selected").text();
			var height = $.trim($('#height').val());
			var weight = $.trim($('#weight').val());
			var sodium = $.trim($('#sodium').val());
			var creatinine = $.trim($('#creatinine').val());

			$('.error-msg').html("&nbsp;");
			if (name === '') {
				$('#name').nextAll('.error-msg:first').text('Please enter your name');
				isNameValid = false;
			} else if (!nameReg.test(name)) {
				$('#name').nextAll('.error-msg:first').text('Please enter a valid name');
				isNameValid = false;
			} else {
				$('#name').nextAll('.error-msg:first').html("&nbsp;");
				isNameValid = true;
			}

			if (mobile === '') {
				$('#mobile').nextAll('.error-msg:first').text('Please enter your mobile number');
				isMobileValid = false;
			} else if (!numberReg.test(mobile)) {
				$('#mobile').nextAll('.error-msg:first').text('Please enter a valid mobile number');
				isMobileValid = false;
			} else {
				$('#mobile').nextAll('.error-msg:first').html("&nbsp;");
				isMobileValid = true;
			}

			if (age === '') {
				$('#age').nextAll('.error-msg:first').text('Please enter your age');
				isAgeValid = false;
			} else if (!numberReg.test(age)) {
				$('#age').nextAll('.error-msg:first').text('Please enter a valid age');
				isAgeValid = false;
			} else {
				$('#age').nextAll('.error-msg:first').html("&nbsp;");
				isAgeValid = true;
			}

			if (gender === 'Select Gender') {
				$('#gender').nextAll('.error-msg:first').text('Please select gender');
				isGenderValid = false;
			} else {
				$('#height').nextAll('.error-msg:first').html("&nbsp;");
				isGenderValid = true;
			}

			if (height === '') {
				$('#height').nextAll('.error-msg:first').text('Please enter your height');
				isHeightValid = false;
			} else {
				$('#height').nextAll('.error-msg:first').html("&nbsp;");
				isHeightValid = true;
			}

			if (weight === '') {
				$('#weight').nextAll('.error-msg:first').text('Please enter your weight');
				isWeightValid = false;
			} else {
				$('#weight').nextAll('.error-msg:first').html("&nbsp;");
				isWeightValid = true;
			}

			if (sodium === '') {
				$('#sodium').nextAll('.error-msg:first').text('Please enter sodium value');
				isSodiumValid = false;
			} else {
				$('#sodium').nextAll('.error-msg:first').html("&nbsp;");
				isSodiumValid = true;
			}

			if (creatinine === '') {
				$('#creatinine').nextAll('.error-msg:first').text('Please enter creatinine value');
				isCreatinineValid = false;
			} else {
				$('#creatinine').nextAll('.error-msg:first').html("&nbsp;");
				isCreatinineValid = true;
			}
			if (isGenderValid && isNameValid && isMobileValid && isAgeValid && isHeightValid && isWeightValid && isSodiumValid && isCreatinineValid && age > 0 && weight > 0 && height > 0 && sodium > 0 && creatinine > 0) {

				var bmi;
				var heightInM2 = (height * height);
				console.log("heightInM2", heightInM2);
				bmi = (weight / heightInM2).toFixed(2);
				console.log("calculateBMI", bmi);
				var sodiumVal;
				var saltIntakeVal;
				var predictedCr;
				var predicted24hrNa;
				// var gender = $('input[name="gender"]:checked').val();
				console.log("gender", gender);
				if (gender == 'Male') {
					predictedCr = (-4.72 * age) + (8.58 * weight) + (5.09 * (height * 100)) - 74.5;
					predicted24hrNa = (183.5 - (3.75 * bmi)) + (17.62 * (sodium /creatinine)) + 71.4;
				} else if (gender == 'Female') {
					predictedCr = (12.63 * age) + (15.12 * weight) + (7.39 * (height * 100)) - 79.9;
					predicted24hrNa = (183.5 - (3.75 * bmi)) + (17.62 * (sodium /creatinine));
				}
				console.log("predictedCr", predictedCr);
				var squareRootOfNaCr;
				var squareRootOfNaCrVal;
				squareRootOfNaCrVal = (sodium / creatinine);
				squareRootOfNaCr = Math.sqrt(squareRootOfNaCrVal);
				sodiumVal = 16.3 * squareRootOfNaCr * (predictedCr * 0.0884);
				console.log("sodiumVal", sodiumVal);
				var sodiumResult = parseFloat(sodiumVal).toFixed(2);
				saltIntakeVal = parseFloat(predicted24hrNa/17).toFixed(2);
				console.log("predicted24hrNa", gender, "=>", predicted24hrNa);
				console.log("calculateSodiumResult", sodiumResult);

				$('#name').val("");
				$('#mobile').val("");
				$('#age').val("");
				$('#height').val("");
				$('#weight').val("");
				$('#bmi').val("");
				$('#sodium').val("");
				$('#creatinine').val("");
				Swal.fire({
					title: '<strong><u>Prediction</u></strong>',
					icon: 'success',
					html: 'Your current estimated salt intake  based on the provided details is <b>' + saltIntakeVal + ' grams/day</b>' +
						'<br><span class="result-help-txt">The prescribed salt intake as per WHO Standards is  5  grams/day</span>' +
						'<br><br>' +
						'<div class="diet-workout-holder">' +
						'<div class="diet-holder">' +
						'<div class="diet-icon-div">' +
						'<i class="fa fa-cutlery"></i>' +
						'<h4>Diet</h4>' +
						'</div>' +
						'<div class="diet-txt-div">' +
						'<p> - Use Low Sodium Salt  Available in market <br> - Reduce the intake of  Snack /Bakery products like cake/Biscuits/Bread <br> - Reduce the intake of Pickle, Pappad and Tinned Products</p>' +
						'</div>' +
						'</div>' +
						'<div class="workout-holder">' +
						'<div class="workout-icon-div">' +
						'<i class="fa fa-sun-o"></i>' +
						'<h4>Workout</h4>' +
						'</div>' +
						'<div class="workout-txt-div">' +
						'<p> - Regular Walking<br> - Mild Aerobic Exercise</p>' +
						'</div>' +
						'</div>' +
						'</div>' +
						'<div class="popup-bottom-help-txt">' +
						'<p class="p-txt">* This prediction is not valid for patients aged below 18 years and above 80 Years' +
						'</p>' +
						'<p class="p-txt">* This prediction is not valid for Chronic Kidney Disease or Diuretic people' +
						'</p>' +
						'</div>',
					showCloseButton: false,
					showCancelButton: false,
					focusConfirm: false,
					confirmButtonText: '<i class="fa fa-thumbs-up"></i> Ok!',
					confirmButtonAriaLabel: 'Thumbs up, Ok!',
					cancelButtonText: '<i class="fa fa-thumbs-down"></i>',
					cancelButtonAriaLabel: 'Thumbs down'
				});
			}

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