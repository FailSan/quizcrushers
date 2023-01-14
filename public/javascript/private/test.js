//FUNZIONI OPZIONI
let testForm = document.querySelector("#testForm");
let testOptions = testForm.testOptions;

testForm.addEventListener("submit", optionSubmit);
for(let i = 0; i < testOptions.length; i++) {
    testOptions[i].addEventListener('change', enableSubmit);
}

function enableSubmit(event) {
    event.preventDefault();

    if(testOptions.value == "") {
        testForm.elements["optionSubmit"].disabled = true;
    } else {
        testForm.elements["optionSubmit"].disabled = false;
    }
}

function optionSubmit(event) {
    event.preventDefault();

    let test_id = document.querySelector("[data-test-id]").dataset.testId;
    let option_id = testOptions.value;

    fetch("/test/" + test_id + "/option/" + option_id, {
        method: "get",
    }).then(onResponse, onError).then(showAnswers);
}

function showAnswers(jsonData) {
    if(jsonData.error !== undefined) {
        showErrors(jsonData.error);
    } else {
        let optionData = jsonData.success;
        
        for(let i = 0; i < optionData.length; i++) {
            let option_id = optionData[i]["id"];
            let correct_flag = optionData[i]["correct"];
            let optionLabel = document.querySelector("[data-option-id='" + option_id + "']");
    
            if(correct_flag) {
                optionLabel.classList.add("optionCorrect");
            } else {
                optionLabel.classList.add("optionNotCorrect");
            }
        }
        for(let i = 0; i < testOptions.length; i++) {
            testOptions[i].disabled = true;
        }
        testForm.elements["optionSubmit"].disabled = true;
    }
}

//FUNZIONI AJAX
function onResponse(response) {
    console.log("OK Status=", response.ok);
    console.log("HTTP Status=", response.status);
    
    let jsonData = response.json();
    return jsonData;
}

function onError(error) {
    console.log(error);
}

//FUNZIONI POTERI
let test_id = document.querySelector("[data-test-id]").dataset.testId;
let powerImages = document.querySelectorAll("[data-power-id] img");
for(let i = 0; i < powerImages.length; i++) {
    powerImages[i].addEventListener("click", usePower);
}

function usePower(event) {
    event.preventDefault();
    let power_id = event.currentTarget.parentElement.dataset.powerId;

    fetch("/test/" + test_id + "/power/" + power_id, {
        method: "get"
    }).then(onResponse, onError).then(showPower);
}

function showPower(jsonData) {
    console.log(jsonData);

    if(jsonData.error !== undefined) {
        showErrors(jsonData.error);
    } else {
        let power_id = jsonData.power;
        let powerResult = jsonData.powerResult;

        switch(power_id) {
            case 1:
                onChangePower(powerResult);
                break;
            case 2:
                onPercentagePower(powerResult);
                break;
            case 3:
                onStopwatchPower(powerResult);
                break;
            case 4:
                onGuillotinePower(powerResult);
                break;
            default:
                showErrors("Il potere non è stato riconosciuto.");
                break;
        }
    }
}

function onChangePower(powerResult) {
    if(powerResult !== undefined) {
        showResults("Il prossimo Quesito sarà più semplice. Aggiornamento tra 2 secondi.");
        setTimeout(function() {
            location.reload();
        }, 3000);
    }
}

function onPercentagePower(powerResult) {
    if(powerResult !== undefined) {
        for(let option in powerResult) {
            let currentLabel = document.querySelector("[data-option-id='" + option + "']");
            let currentPercentage = currentLabel.querySelector(".optionPercentage");
            
            currentPercentage.style.setProperty("--percentage", powerResult[option] + "%");
            currentPercentage.classList.remove("hidden");
            currentPercentage.textContent = "Il " + powerResult[option] + "% degli utenti ha scelto questa opzione.";
        }
    }
}

function onStopwatchPower(powerResult) {
    if(powerResult !== undefined) {
        showResults("Il cronometro è fermo. Aggiornamento tra 2 secondi.");
        setTimeout(function() {
            location.reload();
        }, 3000);
    }
}

function onGuillotinePower(powerResult) {
    if(powerResult !== undefined) {
        for(let option in powerResult) {
            let currentLabel = document.querySelector("[data-option-id='" + powerResult[option] + "']");
            let currentInput = currentLabel.querySelector("input");
            
            currentLabel.classList.add("hidden");
            currentInput.disabled = true;
        }
    }
}

//FUNZIONI DIALOGO

var dialogBox = document.querySelector(".dialogBox");

function showErrors(errors) {
    dialogBox.classList.remove("hidden", "success");
    dialogBox.classList.add("error");
    dialogBox.textContent = errors;
    for(let i = 0; i < powerImages.length; i++) {
        powerImages[i].removeEventListener("click", usePower);
    }

    setTimeout(function() {
        dialogBox.classList.add("hidden");
        for(let i = 0; i < powerImages.length; i++) {
            powerImages[i].addEventListener("click", usePower);
        }
    }, 3000);
}

function showResults(results) {
    dialogBox.classList.remove("hidden", "error");
    dialogBox.classList.add("success");
    dialogBox.textContent = results;
    for(let i = 0; i < powerImages.length; i++) {
        powerImages[i].removeEventListener("click", usePower);
    }

    setTimeout(function() {
        dialogBox.classList.add("hidden");
        for(let i = 0; i < powerImages.length; i++) {
            powerImages[i].addEventListener("click", usePower);
        }
    }, 3000);
}