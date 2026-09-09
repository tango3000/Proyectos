function toggleMenu() {
    const menuIcon = document.querySelector('.menuIcon');
    const navbar = document.getElementById('navbar');
    menuIcon.classList.toggle('active');
    navbar.classList.toggle('active');
}

const slidershow = document.getElementById('sliderShow');
const slider = slidershow.getElementsByTagName('video');
var index = 0;

function nextSlide() {
    slider[index].classList.remove('active');
    index = (index + 1) % slider.length;
    slider[index].classList.add('active');
    console.log(index);
}

function prevSlide() {
    slider[index].classList.remove('active');
    index = (index - 1 + slider.length) % slider.length;
    slider[index].classList.add('active');
}

const sliderShowText = document.getElementById('sliderShowText');
const sliderText = sliderShowText.getElementsByTagName('div');
var i=0;

function nextSlideText() {
    sliderText[i].classList.remove('active');
    i = (i + 1) % sliderText.length;
    sliderText[i].classList.add('active');
}

function prevSlideText() {
    sliderText[i].classList.remove('active');
    i = (i - 1 + sliderText.length) % sliderText.length;
    sliderText[i].classList.add('active');
}