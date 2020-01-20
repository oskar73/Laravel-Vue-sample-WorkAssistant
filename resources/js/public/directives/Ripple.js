export default {
  // When the bound element is inserted into the DOM...
  beforeMount: function (el) {
    // listen for click events to trigger the ripple
    el.addEventListener(
      'click',
      function (e) {
        // Setup
        const target = el.getBoundingClientRect()
        const buttonSize = target.width > target.height ? target.width : target.height
        // remove any previous ripple containers
        const elements = document.getElementsByClassName('bz-ripple')
        while (elements[0]) {
          elements[0].parentNode.removeChild(elements[0])
        }
        // create the ripple container and append it to the target element
        const ripple = document.createElement('span')
        ripple.setAttribute('class', 'bz-ripple')
        el.appendChild(ripple)

        // set the ripple container to the click position and start the animation
        setTimeout(function () {
          ripple.style.width = buttonSize + 'px'
          ripple.style.height = buttonSize + 'px'
          ripple.style.top = e.offsetY - buttonSize / 2 + 'px'
          ripple.style.left = e.offsetX - buttonSize / 2 + 'px'
          ripple.setAttribute('class', 'bz-ripple bz-ripple-effect')
        }, 100)
      },
      false
    )
  }
}