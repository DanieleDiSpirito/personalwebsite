const controlloForm = (name) => { // per cognome e nome
    form = document.getElementsByName(name)[0]
    if (formCorretto(form.value)) {
        form.classList.add('is-valid')
        form.classList.remove('is-invalid')
    } else {
        form.classList.add('is-invalid')
        form.classList.remove('is-valid')
    }
}

const formCorretto = (formValue) => {
    if (formValue === '') return false
    for(let ch of formValue) {
        if(!isValid(ch)) {
            return false
        }
    }
    return true
}

const isValid = (character) => {
    return /[A-Z \-\'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ]/i.test(character)
}

const hideHouse = () => {
    if(window.outerWidth <= 500) {
        houseIcon = document.getElementsByClassName('bi')[0]
        houseIcon.hidden = true
    } else {
        if(houseIcon.hidden) {
            houseIcon.hidden = false
        }
    }
}
/*const luogoNascitaValido = () => {
    let input = document.getElementsByName("nascita")[0]
    let luogoInserito = input.value.toUpperCase()
    let datalist = document.getElementsByTagName("datalist")[0]
    let optionFound = false
    for (let option of datalist.options) {
        if (luogoInserito === option.value.toUpperCase()) {
            optionFound = true
            break
        }
    }
    if (!optionFound) {
        input.classList.add('is-invalid')
    }
}

const controlloData = () => {
    return true
}

const controlloFinale = () => {
    luogoNascitaValido()
    controlloData()
    if(document.getElementsByClassName('is-invalid').length > 0) {
        let pulsanteInvia = document.getElementsByName('invia')[0]
        alert('invalid')
    }
}*/