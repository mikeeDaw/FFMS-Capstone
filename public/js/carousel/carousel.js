

var wrapper = $('.carousel-wrapper')
var slider = $('#slider')
var itemsLength = $('.carItem').length
var buttons = $('.btns').map(function() {
    return this.innerHTML;
}).get().join();

var currentPosition = 0;
var currentMargin = 0;
var slidesPerPage = 0;
var slidesCount = itemsLength - slidesPerPage;
var wrapperWidth = wrapper.width();
var prevKeyActive = false;
var nextKeyActive = true;
var limit = 0

if (wrapperWidth < 440) {
    currentPosition = itemPosition - 1;
} else {
    if (wrapperWidth < 550) {
        currentPosition = Math.ceil(itemPosition/2) - 1;
    } else {
        if (wrapperWidth < 1110) {
            currentPosition = Math.ceil(itemPosition/3) - 1;
        } else {
            currentPosition = Math.ceil(itemPosition/4) - 1;
        }
    }
}

function setParams(width){

    if (width < 440) {
        slidesPerPage = 1; // 8
        limit = 8;
    } else {
        if (width < 550) {
            slidesPerPage = 2; // 4
            limit = 4;
        } else {
            if (width < 1110) {
                slidesPerPage = 3; // 2
                limit = 2;
            } else {
                slidesPerPage = 4;
                limit = 2;
            }
        }
    }

    slidesCount = itemsLength - slidesPerPage;

    if( currentPosition > slidesCount){
        currentPosition -= slidesPerPage
    }

    currentMargin = - currentPosition * (11 * slidesPerPage);
    $('#slider').css('marginLeft', currentMargin + '%');

    console.log(currentMargin, currentPosition, itemsLength, slidesPerPage, slidesCount)
    if(currentPosition > 0){
        $('#prevBtn').removeClass('inactive');
    }
    if(currentPosition < slidesCount){
        $('#nextBtn').removeClass('inactive');
    }
    if (currentPosition >= slidesCount) {
        $('#nextBtn').addClass('inactive');
    }
}

setParams(wrapperWidth);

$( window ).on('resize', function(){
    wrapperWidth = wrapper.width();
    setParams(wrapperWidth);
})



$("#prevBtn").on('click', function(){
    if (currentPosition != 0) {
        currentMargin += (11 * slidesPerPage);
        $('#slider').css('marginLeft', currentMargin  + '%')
        currentPosition--;
    }
    if (currentPosition == 0) {
        $('#prevBtn').addClass('inactive');
        currentMargin = 0;
        $('#slider').css('marginLeft', currentMargin  + '%')
    }
    if (currentPosition < limit) {
        $('#nextBtn').removeClass('inactive');
    }
    console.log(currentPosition, currentMargin, (50 / slidesPerPage));
})

$("#nextBtn").on('click', function(){
    if (currentPosition != slidesCount) {
        currentMargin -= (11 * slidesPerPage);
        $('#slider').css('marginLeft', currentMargin + '%')
        currentPosition++;
    }
    if (currentPosition === limit) {
        $('#nextBtn').addClass('inactive');
    }
    if (currentPosition > 0) {
        $('#prevBtn').removeClass('inactive');
    }

    console.log(currentPosition, currentMargin, slidesPerPage, slidesCount);
})
