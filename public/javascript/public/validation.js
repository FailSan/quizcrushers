var loginForm = document.querySelector("#loginForm");
loginForm.addEventListener('submit', validateLogin);

var registerForm = document.querySelector("#registerForm");
registerForm.addEventListener('submit', validateForm);
for(let i=0; i<registerForm.elements.length; i++) {
    if(registerForm.elements[i].nodeName.toLowerCase() == "fieldset")
        continue;
    registerForm.elements[i].addEventListener('input', validateInput);
}

var registerPages = registerForm.querySelectorAll("[data-reg-index]");
for(let i=0; i<registerPages.length; i++) {
    registerPages[i].addEventListener('change', validatePage);
}


function validateLogin(event) {
    event.preventDefault();

    let user = loginForm.elements["user"].value;
    let password = loginForm.elements["password"].value;
    let remember_token = loginForm.elements["remember_token"].value;
    let token = loginForm.elements["_token"].value;

    let postData = new FormData;
    postData.append("user", user);
    postData.append("password", password);
    postData.append("remember_token", remember_token);
    postData.append("_token", token);

    fetch("/login", {
        method: "post",
        body: postData
    }).then(onResponse, onError).then(onValidateLogin);
}

function validateInput(event) {
    event.preventDefault();

    let currentInput = event.currentTarget;
    let inputName = currentInput.name;
    let inputValue = currentInput.value;
    let token = registerForm.elements["_token"].value;

    let postData = new FormData;
    postData.append("_token", token);
    postData.append(inputName, inputValue);
    
    if(currentInput.name == "password") {
        let confirmPass = registerForm.elements["password_confirmation"].value;
        postData.append("password_confirmation", confirmPass);
    }
    if(currentInput.name == "password_confirmation") {
        let originalPass = registerForm.elements["password"].value;
        postData.append("password", originalPass);
    }

    fetch("/validateInput", {
        method: "post",
        body: postData
    }).then(onResponse, onError).then(onValidateInput);

}

function validatePage(event) {
    event.preventDefault();

    let currentFieldset = event.currentTarget;
    let postData = new FormData;

    switch(currentFieldset.dataset.regIndex) {
        case "0":
            let gender = registerForm.elements["gender"].value;
            let name = registerForm.elements["name"].value;
            let surname = registerForm.elements["surname"].value;
            let birthdate = registerForm.elements["birthdate"].value;

            postData.append("fieldset", 1);
            postData.append("gender", gender);
            postData.append("name", name);
            postData.append("surname", surname);
            postData.append("birthdate", birthdate);
            break;
        case "1":
            let username = registerForm.elements["username"].value;
            let email = registerForm.elements["email"].value;
            let password = registerForm.elements["password"].value;
            let password_confirmation = registerForm.elements["password_confirmation"].value;

            postData.append("fieldset", 2);
            postData.append("email", email);
            postData.append("username", username);
            postData.append("password", password);
            postData.append("password_confirmation", password_confirmation);
            break;
        case "2":
            let degree = registerForm.elements["degree"].value;
            let degree_vote = registerForm.elements["degree_vote"].value;
            let math_vote = registerForm.elements["math_vote"].value;
            let physics_vote = registerForm.elements["physics_vote"].value;

            postData.append("fieldset", 3);
            postData.append("degree", degree);
            postData.append("degree_vote", degree_vote);
            postData.append("math_vote", math_vote);
            postData.append("physics_vote", physics_vote);
            break;
        default:
            break;
    }

    let token = registerForm.elements["_token"].value;
    postData.append("_token", token);

    fetch("/validateInput", {
        method: "post",
        body: postData
    }).then(onResponse, onError).then(onValidatePage);

}

function validateForm(event) {
    event.preventDefault();

    let gender = registerForm.elements["gender"].value;
    let name = registerForm.elements["name"].value;
    let surname = registerForm.elements["surname"].value;
    let birthdate = registerForm.elements["birthdate"].value;

    let email = registerForm.elements["email"].value;
    let username = registerForm.elements["username"].value;
    let password = registerForm.elements["password"].value;
    let password_confirmation = registerForm.elements["password_confirmation"].value;

    let degree = registerForm.elements["degree"].value;
    let degree_vote = registerForm.elements["degree_vote"].value;
    let math_vote = registerForm.elements["math_vote"].value;
    let physics_vote = registerForm.elements["physics_vote"].value;

    let token = registerForm.elements["_token"].value;

    let postData = new FormData;
    
    postData.append("gender", gender);
    postData.append("name", name);
    postData.append("surname", surname);
    postData.append("birthdate", birthdate);

    postData.append("email", email);
    postData.append("username", username);
    postData.append("password", password);
    postData.append("password_confirmation", password_confirmation);
    
    postData.append("degree", degree);
    postData.append("degree_vote", degree_vote);
    postData.append("math_vote", math_vote);
    postData.append("physics_vote", physics_vote);
        
    postData.append("_token", token);

    fetch("/validateForm", {
        method: "post",
        body: postData
    }).then(onResponse, onError).then(onValidateForm);
}

function onValidateLogin(json) {

    let loginSection = document.querySelector("[data-section-index='1']");
    let dialogSection = document.querySelector("[data-section-index='3']");

    loginSection.dataset.sectionSel = 0;
    dialogSection.dataset.sectionSel = 1;

    if (json.error) {
        dialogSection.textContent = json.error;
        
        setTimeout(function() {
            loginSection.dataset.sectionSel = 1;
            dialogSection.dataset.sectionSel = 0;
        }, 3000);

    } else {
        dialogSection.textContent = "Login effettuato con successo. Verrai reindirizzato tra un secondo.";

        setTimeout(function() {
            window.location.replace("/dashboard");
        }, 3000);
    }
}

function onValidateInput(json) {
    if(json.error) {
        for(let inputName in json.error) {
            let currentInput = registerForm.elements[inputName];
            let currentLabel = currentInput.nextElementSibling;

            currentInput.classList.add("notValidated");
            currentLabel.classList.add("notValidated");
            currentLabel.textContent = "";
            for(let error in json.error[inputName]) {
                currentLabel.textContent = currentLabel.textContent + json.error[inputName][error];
            }
        }
    } else if(json.success) {
        for(let inputName in json.success) {
            if(inputName != "gender") {
                let currentInput = registerForm.elements[inputName];
                let currentLabel = currentInput.nextElementSibling;

                currentInput.classList.remove("notValidated");
                currentLabel.classList.remove("notValidated");
                currentLabel.textContent = "";
            }
        }
    }
}

function onValidatePage(json) {
    let currentFieldset = registerForm.querySelector("[data-reg-sel='1']");
    let currentIndex = currentFieldset.dataset.regIndex;

    let fieldsetCircles = document.querySelectorAll("[data-section-index='2'] [data-circle-index]");
    let currentCircle = fieldsetCircles[currentIndex];

    if(json.error) {
        currentCircle.classList.add("error");
        currentCircle.textContent = "Errore";
        currentCircle.classList.remove("correct");

        for(let i=(parseInt(currentIndex) + 1); i<fieldsetCircles.length; i++) {
            fieldsetCircles[i].classList.add("disabled");
        }
    } else {
        currentCircle.classList.add("correct");
        currentCircle.textContent = "Ok";
        currentCircle.classList.remove("error");

        if((parseInt(currentIndex) + 1) < fieldsetCircles.length)
            fieldsetCircles[(parseInt(currentIndex) + 1)].classList.remove("disabled");
    }
}

function onValidateForm(json) {

    let loginSection = document.querySelector("[data-section-index='1']");
    let registerSection = document.querySelector("[data-section-index='2']");
    let dialogSection = document.querySelector("[data-section-index='3']");

    registerSection.dataset.sectionSel = 0;
    dialogSection.dataset.sectionSel = 1;

    if (json.error) {
        dialogSection.firstChild.textContent = json.error;
        dialogSection.firstChild.classList.add("notValidated");
        
        setTimeout(function() {
            registerSection.dataset.sectionSel = 1;
            dialogSection.dataset.sectionSel = 0;
        }, 3000);

    } else {
        dialogSection.firstChild.textContent = json.success;
        dialogSection.firstChild.classList.remove("notValidated");

        setTimeout(function() {
            loginSection.dataset.sectionSel = 1;
            registerSection.dataset.sectionSel = 0;
            dialogSection.dataset.sectionSel = 0;
        }, 3000);
    }
}

function onResponse(response) {
    let json = response.json();
    return json;
}

function onError(error) {
    console.log(error);
}