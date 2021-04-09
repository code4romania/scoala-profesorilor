$( document ).ready(function() {
    let jsScript = $("#partial-carousel-multiple-courses");
    let id = jsScript.attr("data-id");

    $('#carousel-multiple-courses-' + id).on('slide.bs.carousel', function (e) {
        var $e = $(e.relatedTarget);
        var idx = $e.index();
        var itemsPerSlide = 1;
        var totalItems = $('.carousel-item-' + id).length;

        if (idx >= totalItems-(itemsPerSlide-1)) {
            var it = itemsPerSlide - (totalItems - idx);
            for (var i=0; i<it; i++) {
                // append slides to end
                if (e.direction=="left") {
                    $('.carousel-item-' + id).eq(i).appendTo('.carousel-inner-' + id);
                } else {
                    $('.carousel-item-' + id).eq(0).appendTo('.carousel-inner-' + id);
                }
            }
        }
    });
});
