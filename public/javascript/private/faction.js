let factionFrame = document.querySelector(".factionFrame");
let factionsLinks = factionFrame.querySelectorAll(".factionChooser span");

for(let i = 0; i < factionsLinks.length; i++) {
    factionsLinks[i].addEventListener("click", showFaction);
}

function showFaction(event) {
    event.preventDefault();

    let currentFactionContainer = factionFrame.querySelector("[data-faction-sel='1']");
    let currentFaction = currentFactionContainer.dataset.faction;

    let newFaction = event.currentTarget.dataset.faction;
    let newFactionContainer = factionFrame.querySelector("[data-faction-sel='0']");

    if(newFaction != currentFaction) {
        factionFrame.classList.add(newFaction);
        factionFrame.classList.remove(currentFaction);

        currentFactionContainer.dataset.factionSel = 0;
        newFactionContainer.dataset.factionSel = 1;
    }
}

let darkDetailsLinks = document.querySelectorAll("[data-faction='dark'] [data-detail]");
let lightDetailsLinks = document.querySelectorAll("[data-faction='light'] [data-detail]");

for(let i = 0; i < darkDetailsLinks.length; i++) {
    darkDetailsLinks[i].addEventListener("click", showDetail);
    lightDetailsLinks[i].addEventListener("click", showDetail);
}

function showDetail(event) {
    event.preventDefault();

    let newDetailLabel = event.currentTarget;
    let newDetail = newDetailLabel.parentNode;

    let currentFaction = factionFrame.querySelector("[data-faction-sel='1']");
    let currentDetail = currentFaction.querySelector("[data-detail-sel='1']");

    if(!currentDetail) {
        newDetail.dataset.detailSel = 1;
    } else {
        if(newDetail != currentDetail) {
            newDetail.dataset.detailSel = 1;
            currentDetail.dataset.detailSel = 0;
        } else {
            currentDetail.dataset.detailSel = 0;
        }
    }
}

let darkAvatarsLinksContainer = document.querySelector("[data-faction='dark'] .avatarsLevels");
let darkAvatarsLinks = darkAvatarsLinksContainer.querySelectorAll("span");

let lightAvatarsLinksContainer = document.querySelector("[data-faction='light'] .avatarsLevels");
let lightAvatarsLinks = lightAvatarsLinksContainer.querySelectorAll("span");

for(let i = 0; i < darkAvatarsLinks.length; i++) {
    darkAvatarsLinks[i].addEventListener('click', showAvatars);
    lightAvatarsLinks[i].addEventListener('click', showAvatars);
}

function showAvatars(event) {
    event.preventDefault();

    let currentFaction = factionFrame.querySelector("[data-faction-sel='1']");

    let currentLink = currentFaction.querySelector(".avatarsLevels [data-level-sel='1']");
    let currentLevel = currentLink.dataset.level;
    let currentAvatars = currentFaction.querySelector(".avatarsContainer [data-level-sel='1']");

    let newLink = event.currentTarget;
    let newLevel = newLink.dataset.level;
    let newAvatars = currentFaction.querySelector(".avatarsContainer [data-level='" + newLevel + "']");

    if(newLevel != currentLevel) {
        currentAvatars.dataset.levelSel = 0;
        currentLink.dataset.levelSel = 0;

        newAvatars.dataset.levelSel = 1;
        newLink.dataset.levelSel = 1;
    }
}

let lightButton = document.querySelector("[data-faction='light'] button");
let darkButton = document.querySelector("[data-faction='dark'] button");

lightButton.addEventListener("click", factionChoice);
darkButton.addEventListener("click", factionChoice);

function factionChoice(event) {
    event.preventDefault;

    let currentButton = event.currentTarget;
    let currentFaction = currentButton.dataset.faction;
    let faction_id;
    switch (currentFaction) {
        case "light":
            faction_id = 1;
            break;
        case "dark":
            faction_id = 2;
            break;
        default:
            faction_id = 0;
            break;
    }

    fetch("/faction/"+faction_id , {
        method: "get"
    }).then(onResponse, onError).then(onFactionChoice);
}

function onResponse(response) {
    let jsonData = response.json();
    return jsonData;
}

function onError(error) {
    console.log(error);
}

function onFactionChoice(jsonData) {
    console.log(jsonData);
    if(jsonData.success != undefined)
        window.location.replace("/dashboard");
}