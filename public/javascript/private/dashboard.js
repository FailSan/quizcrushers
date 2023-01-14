//FUNZIONI SFIDA
var dialogBox = document.querySelector(".dialogBox");

function initListeners() {
    let challengeActionsLinks = document.querySelectorAll("[data-challenge-action]");
    let challengeTypeContainers = document.querySelectorAll(".challengeTypeLabel");
    let userRankContainers = document.querySelectorAll("[data-user-sel]");
    let rankFields = document.querySelector("#rankFilterForm").elements;

    for(let i = 0; i < challengeActionsLinks.length; i++) {
        challengeActionsLinks[i].addEventListener("click", challengeAction);
    }
    
    for(let i = 0; i < userRankContainers.length; i++) {
        userRankContainers[i].addEventListener("click", showActions);
    }

    for(let i = 0; i < challengeTypeContainers.length; i++) {
        challengeTypeContainers[i].addEventListener("click", showChallengeType);
    }

    for(let i = 0; i < rankFields.length; i++) {
        if(rankFields[i].nodeName.toLowerCase() == "fieldset")
            continue;
        else
            rankFields[i].addEventListener("input", updateRank);
    }
}
initListeners();

function showChallengeType(event) {
    event.preventDefault();

    let newLabel = event.currentTarget;
    let newType = newLabel.parentElement;

    let currentType = document.querySelector("[data-challenge-sel='1']");

    if(newType != currentType) {
        currentType.dataset.challengeSel = 0;
        newType.dataset.challengeSel = 1;
    }
}

function challengeAction(event) {
    event.preventDefault();
    let user_id = event.currentTarget.dataset.userId;
    let challenge_action = event.currentTarget.dataset.challengeAction;

    fetch("/challenge/" + challenge_action + "/" + user_id, {
        method: "get"
    }).then(onResponse, onError).then(onChallenge);
}

function onChallenge(jsonData) {

    if(jsonData.success != undefined) {
        dialogBox.classList.remove("hidden");
        dialogBox.classList.add("success");
        dialogBox.textContent = jsonData.success;
    }

    updateRank(true);
    updateChallenge(true);

    setTimeout(function() {
        dialogBox.classList.add("hidden");
    }, 3000);
}

//FUNZIONI CLASSIFICHE

function showActions(event) {
    event.preventDefault();

    let newContainer = event.currentTarget;
    let currentContainer = document.querySelector("[data-user-sel='1']");
    if(currentContainer != undefined) {
        currentContainer.dataset.userSel = 0;
    }
    if(newContainer != currentContainer) {
        newContainer.dataset.userSel = 1;
    } else {
        newContainer.dataset.userSel = 0;
    }
}

function filterRank(event) {
    event.preventDefault();

    let rankFilterForm = document.querySelector("#rankFilterForm");
    let showValue = rankFilterForm.elements["showRadio"].value;
    let sortValue = rankFilterForm.elements["sortRadio"].value;
    let stringValue = rankFilterForm.elements["stringInput"].value;

    

    updateRank(true, showValue, sortValue, stringValue);
}

//FUNZIONI AGGIORNAMENTO SFIDE E CLASSIFICHE
function updateRank(syncFlag) {

    if(syncFlag) {
        let rankFilterForm = document.querySelector("#rankFilterForm");
        let showValue = rankFilterForm.elements["showRadio"].value;
        let sortValue = rankFilterForm.elements["sortRadio"].value;
        let stringValue = rankFilterForm.elements["stringInput"].value;

        fetch("/user/rank/" + showValue + "/" + sortValue + "/" + stringValue, {
            method: "get"
        }).then(onResponse, onError).then(onRankUpdate);
    } else {
        setInterval(function() {         
            let rankFilterForm = document.querySelector("#rankFilterForm");
            let showValue = rankFilterForm.elements["showRadio"].value;
            let sortValue = rankFilterForm.elements["sortRadio"].value;
            let stringValue = rankFilterForm.elements["stringInput"].value;
            
            fetch("/user/rank/" + showValue + "/" + sortValue + "/" + stringValue, {
                method: "get"
            }).then(onResponse, onError).then(onRankUpdate);
        }, 30000);
    }
}
updateRank(false);

function onRankUpdate(jsonData) {
    let rankFrame = document.querySelector(".rankFrame");
    rankFrame.innerHTML = jsonData.view;
    initListeners();
}

function updateChallenge(syncFlag) {
    if(syncFlag) {
        fetch("/user/challenges/", {
            method: "get"
        }).then(onResponse, onError).then(onChallengeUpdate);
    } else {
        setInterval(function() {
            fetch("/user/challenges/", {
                method: "get"
            }).then(onResponse, onError).then(onChallengeUpdate);
        }, 30000);
    }
}
updateChallenge(false);

function onChallengeUpdate(jsonData) {
    let challengeFrame = document.querySelector(".challengeFrame");
    challengeFrame.innerHTML = jsonData.view;
    initListeners();
}

//FUNZIONI AJAX
function onResponse(response) {
    return response.json();
}

function onError(error) {
    console.log(error);
}

//FUNZIONI AGGIORNAMENTO POTERI
var powersCooldown = document.querySelectorAll("[data-power-id] .powerCooldown");
function updateCooldown() {
    setInterval(function() {
        for(let i = 0; i < powersCooldown.length; i++) {
            let power_id = powersCooldown[i].parentNode.dataset.powerId;
    
            fetch("/user/power/" + power_id, {
                method: "get"
            }).then(onResponse, onError).then(onPowerUpdate);
        }
    }, 30000);
}
updateCooldown();

function onPowerUpdate(jsonData) {
    if(jsonData.powerCooldown > 0) {
        let currentPower = jsonData.power;
        let newCooldown = jsonData.powerCooldown;

        let currentCooldown = document.querySelector("[data-power-id='" + currentPower + "'] .powerCooldown");
        currentCooldown.textContent = "Attendi " + newCooldown + " minuti.";
    }
}