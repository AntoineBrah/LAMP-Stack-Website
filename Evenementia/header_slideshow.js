var header = document.querySelector('header');

var backgroundImage = ['party.jpg', 'camping.png', 'cruise.jpg', 'fireworks.jpg', 'ski.jpg', 'basket.jpg', 'stadium.jpg'];



function changeHeaderBackgroundImage(n){

    switch(n){
        case 0:
            header.style.backgroundPosition = '50% 40%';
            header.style.backgroundImage = 'url(' + 'slideshow-img/' + backgroundImage[n] + ')';
            break;
        case 1:
            header.style.backgroundPosition = '50% 10%';
            header.style.backgroundImage = 'url(' + 'slideshow-img/' + backgroundImage[n] + ')';
            break;
        case 2:
            header.style.backgroundPosition = '50% 40%';
            header.style.backgroundImage = 'url(' + 'slideshow-img/' + backgroundImage[n] + ')';
            break;
        case 3:
            header.style.backgroundPosition = '50% 10%';
            header.style.backgroundImage = 'url(' + 'slideshow-img/' + backgroundImage[n] + ')';
            break;
        case 4:
            header.style.backgroundPosition = '50% 40%';
            header.style.backgroundImage = 'url(' + 'slideshow-img/' + backgroundImage[n] + ')';
            break;
        case 5:
            header.style.backgroundPosition = '50% 10%';
            header.style.backgroundImage = 'url(' + 'slideshow-img/' + backgroundImage[n] + ')';
            break;
        case 6:
            header.style.backgroundPosition = '50% 100%';
            header.style.backgroundImage = 'url(' + 'slideshow-img/' + backgroundImage[n] + ')';
            break;
        default:
            console.log("Header crossfoading error");
    }
}

var n = 1;

setInterval(function(){

    //console.log('n = ' + n );

    if(n >= backgroundImage.length)
        n = 0;

    changeHeaderBackgroundImage(n);
    n++;

},5000);
