let stripe = Stripe(stripe_pk)

function registerElements(elements, exampleName) {
  let formClass = '.' + exampleName

  let example = document.querySelector(formClass)

  let form = example.querySelector('#stripe_smt_form')
  let resetButton = example.querySelector('a.reset')
  let error = form.querySelector('.error')
  let errorMessage = error.querySelector('.message')

  function enableInputs() {
    Array.prototype.forEach.call(form.querySelectorAll("input[type='text'], input[type='email'], input[type='tel']"), function (input) {
      input.removeAttribute('disabled')
    })
  }

  function disableInputs() {
    Array.prototype.forEach.call(form.querySelectorAll("input[type='text'], input[type='email'], input[type='tel']"), function (input) {
      input.setAttribute('disabled', 'true')
    })
  }

  function triggerBrowserValidation() {
    // The only way to trigger HTML5 form validation UI is to fake a user submit
    // event.
    var submit = document.createElement('input')
    submit.type = 'submit'
    submit.style.display = 'none'
    form.appendChild(submit)
    submit.click()
    submit.remove()
  }

  // Listen for errors from each Element, and show error messages in the UI.
  let savedErrors = {}

  elements.forEach(function (element, idx) {
    element.on('change', function (event) {
      if (event.error) {
        error.classList.add('visible')
        savedErrors[idx] = event.error.message
        errorMessage.innerText = event.error.message
      } else {
        savedErrors[idx] = null

        // Loop over the saved errors and find the first one, if any.
        var nextError = Object.keys(savedErrors)
          .sort()
          .reduce(function (maybeFoundError, key) {
            return maybeFoundError || savedErrors[key]
          }, null)

        if (nextError) {
          // Now that they've fixed the current error, show another one.
          errorMessage.innerText = nextError
        } else {
          // The user fixed the last error; no more errors.
          error.classList.remove('visible')
        }
      }
    })
  })

  $(document).on('submit', '#stripe_smt_form', function (e) {
    e.preventDefault()

    // Trigger HTML5 validation UI on the form if any of the inputs fail
    // validation.
    var plainInputsValid = true
    Array.prototype.forEach.call(form.querySelectorAll('input'), function (input) {
      if (input.checkValidity && !input.checkValidity()) {
        plainInputsValid = false
        return
      }
    })
    if (!plainInputsValid) {
      triggerBrowserValidation()
      return
    }

    // Show a loading screen...
    example.classList.add('submitting')

    // Disable all inputs.
    disableInputs()

    // Gather additional customer data we may have collected in our form.
    var name = form.querySelector('#' + exampleName + '-name')
    var address1 = form.querySelector('#' + exampleName + '-address')
    var city = form.querySelector('#' + exampleName + '-city')
    var state = form.querySelector('#' + exampleName + '-state')
    var zip = form.querySelector('#' + exampleName + '-zip')

    var additionalData = {
      name: name ? name.value : undefined,
      address_line1: address1 ? address1.value : undefined,
      address_city: city ? city.value : undefined,
      address_state: state ? state.value : undefined,
      address_zip: zip ? zip.value : undefined
    }

    // Use Stripe.js to create a token. We only need to pass in one Element
    // from the Element group in order to create a token. We can also pass
    // in the additional customer data we collected in our form.
    stripe.createToken(elements[0], additionalData).then(function (result) {
      // Stop loading!

      if (result.token) {
        // If we received a token, show the token ID.

        example.querySelector('.stripe_token').value = result.token.id

        stripeFormSubmit()
      } else {
        // Otherwise, un-disable inputs.
        example.classList.remove('submitting')
        enableInputs()
      }
    })
  })

  resetButton.addEventListener('click', function (e) {
    e.preventDefault()
    // Resetting the form (instead of setting the value to `''` for each input)
    // helps us clear webkit autofill styles.
    form.reset()

    // Clear each Element.
    elements.forEach(function (element) {
      element.clear()
    })

    // Reset error state as well.
    error.classList.remove('visible')

    // Resetting the form does not un-disable inputs, so we need to do it separately:
    enableInputs()
    example.classList.remove('submitted')
  })

  function stripeFormSubmit() {
    var formData = new FormData(document.querySelector('#stripe_smt_form'))
    formData.append('name', $('#stripe_smt_form input[name=name]').val())
    formData.append('email', $('#stripe_smt_form input[name=email]').val())
    formData.append('address', $('#stripe_smt_form input[name=address]').val())
    formData.append('country', $('#stripe_smt_form input[name=country]').val())
    formData.append('city', $('#stripe_smt_form input[name=city]').val())
    formData.append('state', $('#stripe_smt_form input[name=state]').val())
    formData.append('zipcode', $('#stripe_smt_form input[name=zipcode]').val())
    formData.append('guest_email', $('#guest_email').val())

    $.ajax({
      url: '/cart/stripe/execute',
      method: 'POST',
      data: formData,
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData: false,
      success: function (result) {
        enableInputs()
        console.log(result)
        example.classList.remove('submitting')

        if (result.status === 1) {
          example.classList.add('submitted')
          window.setTimeout(function () {
            if (result.new) {
              window.location.href = '/cart?action=login'
            } else {
              window.location.href = '/account/todo/website'
            }
          }, 1000)
        } else if (result.status === 0) {
          error.classList.add('visible')
          savedErrors[0] = result.data
          errorMessage.innerText = result.data
        } else if (result.status === 2) {
          dispErrors(result.data)
        }
      }
    })
  }
}

$(function () {
  let elements = stripe.elements({
    fonts: [
      {
        cssSrc: 'https://rsms.me/inter/inter-ui.css',
        icons: {
          submenu: 'ui-icon-caret-1-e',
          type: 'icon'
        }
      }
    ],
    // Stripe's examples are localized to specific languages, but if
    // you wish to have Elements automatically detect your user's locale,
    // use `locale: 'auto'` instead.
    locale: 'auto'
  })

  /**
   * Card Element
   */
  let card = elements.create('card', {
    style: {
      base: {
        color: '#32325D',
        fontWeight: 500,
        fontFamily: 'Inter UI, Open Sans, Segoe UI, sans-serif',
        fontSize: '16px',
        fontSmoothing: 'antialiased',

        '::placeholder': {
          color: '#CFD7DF'
        }
      },
      invalid: {
        color: '#E25950'
      }
    }
  })

  card.mount('#example4-card')

  /**
   * Payment Request Element
   */
  var paymentRequest = stripe.paymentRequest({
    country: 'US',
    currency: 'usd',
    total: {
      amount: total * 100,
      label: 'Total'
    }
  })

  paymentRequest.on('token', function (result) {
    let example = document.querySelector('.example4')
    example.querySelector('.token').innerText = result.token.id
    example.classList.add('submitted')
    result.complete('success')
  })

  var paymentRequestElement = elements.create('paymentRequestButton', {
    paymentRequest: paymentRequest,
    style: {
      paymentRequestButton: {
        type: 'donate'
      }
    }
  })

  paymentRequest.canMakePayment().then(function (result) {
    if (result) {
        $('.example4 .card-only').hide();
        $('.example4 .payment-request-available').show();
      paymentRequestElement.mount('#example4-paymentRequest')
    }
  })

  registerElements([card, paymentRequestElement], 'example4')
})
