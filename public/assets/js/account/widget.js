$(document).ready(function () {
    $('.new-click-badge').each(function() {
        var packageId = $(this).data('id');
        // Check if 'widget-list-' + packageId exists in localStorage
        if (!localStorage.getItem('widget-new-website-' + packageId)) {
            // Hide the element if the item exists in localStorage
            $(this).show();
        }
    });
})

$('.widget-toggle-btn').on('click', function () {
    var id = $(this).data('id')
    $('.widget-list-' + id).toggle()
})

$('.new-click-btn').on('click', function (e) {
    e.preventDefault()

    var id = $(this).data('id')
    localStorage.setItem('widget-new-website-' + id, '1')

    window.location.href = this.getAttribute('href');
})