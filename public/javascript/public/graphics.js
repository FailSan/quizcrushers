var navBar = document.querySelector("navbar");
var sectionLinks = navBar.querySelectorAll("a");
for(let i=1; i<sectionLinks.length; i++) {
    sectionLinks[i].addEventListener("click", showSection);
}

var descContainer = document.querySelector("[data-section-index='0']");
var descPages = descContainer.querySelectorAll("[data-desc-index]");
var descSliderContainer = descContainer.querySelector(".circleSlider");
var descSliderCircles = descSliderContainer.querySelectorAll("span");
for(let i=0; i<descSliderCircles.length; i++) {
    descSliderCircles[i].addEventListener("click", showDescPage)
}

var regContainer = document.querySelector("[data-section-index='2']");
var regPages = regContainer.querySelectorAll("[data-reg-index]");
var regSliderContainer = regContainer.querySelector(".circleSlider");
var regSliderCircles = regSliderContainer.querySelectorAll("span");
for(let i=0; i<regSliderCircles.length; i++) {
    regSliderCircles[i].addEventListener("click", showRegPage)
}

function showSection(event) {
    event.preventDefault();

    let currentLink = event.currentTarget;
    let sectionIndex = currentLink.dataset.linkIndex;

    let newSection = document.querySelector("[data-section-index='" + sectionIndex + "']");
    let prevSection = document.querySelector("[data-section-sel='1']");

    prevSection.dataset.sectionSel = 0;
    newSection.dataset.sectionSel = 1;

    if(prevSection.dataset.sectionIndex == 0) {
        descPages[0].dataset.descSel = 1;
        descPages[1].dataset.descSel = 0;
        descPages[2].dataset.descSel = 0;

        descSliderCircles[0].dataset.circleSel = 1;
        descSliderCircles[1].dataset.circleSel = 0;
        descSliderCircles[2].dataset.circleSel = 0;
    }

    if(prevSection.dataset.sectionIndex == 2) {
        regPages[0].dataset.regSel = 1;
        regPages[1].dataset.regSel = 0;
        regPages[2].dataset.regSel = 0;

        regSliderCircles[0].dataset.circleSel = 1;
        regSliderCircles[1].dataset.circleSel = 0;
        regSliderCircles[2].dataset.circleSel = 0;
    }
}

function showDescPage(event) {
    event.preventDefault();

    let currentCircle = event.currentTarget;
    let pageIndex = currentCircle.dataset.circleIndex;

    let newPage = descContainer.querySelector("[data-desc-index='" + pageIndex + "']");
    let newCircle = descContainer.querySelector("[data-circle-index='" + pageIndex + "']")

    let previousPage = descContainer.querySelector("[data-desc-sel='1']");
    let previousCircle = descContainer.querySelector("[data-circle-sel='1']");
    
    previousPage.dataset.descSel = 0;
    previousCircle.dataset.circleSel = 0;

    newPage.dataset.descSel = 1;
    newCircle.dataset.circleSel = 1;
}

function showRegPage(event) {
    event.preventDefault();

    let currentCircle = event.currentTarget;
    let pageIndex = currentCircle.dataset.circleIndex;

    let newPage = regContainer.querySelector("[data-reg-index='" + pageIndex + "']");
    let newCircle = regContainer.querySelector("[data-circle-index='" + pageIndex + "']")

    let previousPage = regContainer.querySelector("[data-reg-sel='1']");
    let previousCircle = regContainer.querySelector("[data-circle-sel='1']");
    
    previousPage.dataset.regSel = 0;
    previousCircle.dataset.circleSel = 0;

    newPage.dataset.regSel = 1;
    newCircle.dataset.circleSel = 1;
}