import Swal from 'sweetalert2'



initMenus();
initForm();

function initForm() {
	const form = document.querySelector('.js-form');
	const next = form.querySelectorAll('.js-submit-next');
	const ctaForm = document.querySelector('.js-cta-form');


	const emailInput = form.querySelector('input[name="email"]');
	const emailInputCta = ctaForm.querySelector('input[name="email"]');

	if (ctaForm) {
		ctaForm.addEventListener('submit', (event) => {
			event.preventDefault();
			emailInput.value = emailInputCta.value;
			form.scrollIntoView({
				behavior: 'smooth'
			});
			return false;
		});
	}

	// docs: https://developers.hubspot.com/docs/methods/forms/submit_form_v3

	document.addEventListener( 'wpcf7submit', ( event )  => {

		let formData = {
			"fields": [],
			"legalConsentOptions": {
				"consent": {
					"consentToProcess": true,
					"text": "I agree to allow Icecream Company to store and process my personal data.",
					"communications": [
						{
							"value": true,
							"subscriptionTypeId": 999,
							"text": "I agree to receive marketing communications from Icecream Company."
						}
					]
				}
			},
		};

		let inputs = event.detail.inputs;

		for ( let i = 0; i < inputs.length; i++ ) {
			formData.fields.push({'name': inputs[i].name.replace('[]',''), 'value':inputs[i].value});
		}


		let request = new XMLHttpRequest();
		let url = `https://api.hsforms.com/submissions/v3/integration/submit/${form.dataset.portal}/${form.dataset.form}`;

		request.onreadystatechange = () => {
			if (request.readyState === XMLHttpRequest.DONE) {
				console.log(request);
				if (request.status !== 200) {
					Swal.fire( 'Ooops!', JSON.parse(request.responseText).message || 'Error', 'error')
				} else {
					Swal.fire('Delicious', JSON.parse(request.responseText).inlineMessage, 'success');
				}

			}
		}

		request.open("POST", url);
		request.setRequestHeader('Content-Type', 'application/json');
		request.send(JSON.stringify(formData));



	}, false );

	for(let i = 0; i < next.length; i++) {
		next[i].addEventListener('click', (event) => {
			event.preventDefault();
			let currentPart = next[i].closest('.form-part');
			let nextPart = currentPart.nextElementSibling;

			const required = currentPart.querySelectorAll('.wpcf7-validates-as-required');

			let hasErrors = false;
			for(let i = 0; i < required.length; i++) {
				let field = required[i].closest('.form-item');
				let error = field.querySelector('.error');
				field.classList.remove('has-errors');
				if (error) {
					error.remove();
				}

				if (! required[i].value) {
					field.classList.add('has-errors');
					field.innerHTML += '<div class="error">This field is required.</div>';
					hasErrors = true;
				}

				if (required[i].value && required[i].type === 'email' && !validateEmail(required[i].value)) {
					field.classList.add('has-errors');
					field.innerHTML += '<div class="error">This is not a valid email.</div>';
					hasErrors = true;
				}

			}


			if (!hasErrors) {
				currentPart.classList.remove('active');
				nextPart.classList.add('active');
			}

		});
	}
}

function validateEmail(email) {
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(String(email).toLowerCase());
}

function initMenus() {
    const navItems = document.querySelectorAll('.js-hoverable');
    const hover = document.querySelector('.js-hover');

    for(let i = 0; i < navItems.length; i++) {

		navItems[i].addEventListener('mouseover', (event) => {

			hover.style.transform = `translateX(${i*80}px)`;
		});
	}
}


