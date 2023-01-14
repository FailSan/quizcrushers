var quizTypes = document.querySelectorAll(".quizTypeLabel");
for(let i = 0; i < quizTypes.length; i++) {
    quizTypes[i].addEventListener("click", showQuizCollection);
}

function showQuizCollection(event) {
    event.preventDefault();

    let newLabel = event.currentTarget;
    let newType = newLabel.parentElement;
    let isOpen = (newType.dataset.quizSel == 1) ? true : false;
    
    if(isOpen) {
        newType.dataset.quizSel = 0;
    } else {
        newType.dataset.quizSel = 1;
    }
}

var challengeTypes = document.querySelectorAll(".challengeTypeLabel");
for(let i = 0; i < challengeTypes.length; i++) {
    challengeTypes[i].addEventListener("click", showChallengeCollection);
}

function showChallengeCollection(event) {
    event.preventDefault();

    let newLabel = event.currentTarget;
    let newType = newLabel.parentElement;
    let isOpen = (newType.dataset.challengeSel == 1) ? true : false;
    
    if(isOpen) {
        newType.dataset.challengeSel = 0;
    } else {
        newType.dataset.challengeSel = 1;
    }
}