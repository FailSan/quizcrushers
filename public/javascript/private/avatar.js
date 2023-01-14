let avatarGroups = document.querySelector(".avatarGroups");
let avatarGroupsLabels = avatarGroups.querySelectorAll(".avatarGroupLabel");

for(let i = 0; i < avatarGroupsLabels.length; i++)
    avatarGroupsLabels[i].addEventListener("click", showGroup);

function showGroup(event) {
    event.preventDefault();

    let newGroupLabel = event.currentTarget;
    let newGroup = newGroupLabel.parentNode;
    
    let currentGroup = avatarGroups.querySelector("[data-group-sel='1']");

    if(newGroup != currentGroup) {
        currentGroup.dataset.groupSel = 0;
        newGroup.dataset.groupSel = 1;
    }
}

let avatarImages = document.querySelectorAll("[data-available='1']");
for(let i = 0; i < avatarImages.length; i++) {
    avatarImages[i].addEventListener("click", showAvatar);
}

let avatarSelected = document.querySelector(".avatarDisplay img");
let choiceButton = document.querySelector(".avatarDisplay button");

function showAvatar(event) {
    event.preventDefault();

    let newAvatar = event.currentTarget;
    let currentAvatar = document.querySelector("[data-avatar-sel='1']");
    
    if (currentAvatar && currentAvatar != newAvatar) {
        currentAvatar.dataset.avatarSel = 0;
    }
    
    newAvatar.dataset.avatarSel = 1;
    choiceButton.classList.remove("hidden");
    avatarSelected.src = newAvatar.src.replace("mini_", "");
    avatarSelected.dataset.avatarId = newAvatar.dataset.avatarId;
}

choiceButton.addEventListener("click", avatarChoice);

function avatarChoice(event) {
    event.preventDefault();

    let id = avatarSelected.dataset.avatarId;

    fetch("/avatar/"+id , {
        method: "get"
    }).then(onResponse, onError).then(onAvatarChoice);
}

function onResponse(response) {
    let jsonData = response.json();
    return jsonData;
}

function onError(error) {
    console.log(error);
}

function onAvatarChoice(jsonData) {
    console.log(jsonData);

    if(jsonData.success != undefined)
        window.location.replace("/dashboard");
}