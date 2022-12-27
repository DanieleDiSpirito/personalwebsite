turno = "X"
title = document.getElementById('title')
title.innerHTML = `Tocca a ${turno}`
document.title = `Tocca a ${turno}`
nMossa = 0;
buttons = document.querySelectorAll('button[value="1"]') // tutte le caselle
buttons.forEach((button) => {
    button.addEventListener('click', () => {
        if(button.value == 1) { // nè O nè 1
            changeValue(button, turno)
        }
    })
})

dict = {'': 1, 'X': 3, 'O': 5}
const changeValue = (element, value) => {
    element.textContent = value
    element.value = dict[value]
    nMossa++
    if(turno == "X") {
        turno = "O"
    } else {
        turno = "X"
    }
    title.innerHTML = `Tocca a ${turno}`
    document.title = `Tocca a ${turno}`
    check(); // controlla se qualcuno ha vinto o se c'è patta
}

const check = () => {
    for(i = 0; i < 3; i++) { // righe
        product = 1
        for(j = 0; j < 3; j++) {
            product *= buttons[j + i*3].value
        }
        if(product == 27) return vittoria('X')
        if(product == 125) return vittoria('O')
    }
    for(i = 0; i < 3; i++) { // colonne
        product = 1
        for(j = 0; j < 3; j++) {
            product *= buttons[j*3 + i].value
        }
        if(product == 27) return vittoria('X')
        if(product == 125) return vittoria('O')
    }
    for(i = 0; i < 2; i++) { // diagonali
        product = 1
        for(j = 0; j < 3; j++) {
            product *= buttons[(2*i)*(1-j) + j*4].value                
        }
        if(product == 27) return vittoria('X')
        if(product == 125) return vittoria('O')
    }
    if(nMossa == 9) return patta()
}

const vittoria = (turno) => {
    title = document.querySelector('div#title')
    title.textContent = `Ha vinto ${turno}!`
    title.setAttribute('style', 'color: darkred')
    disabilitaTutto()
}

const patta = () => {
    title = document.querySelector('div#title')
    title.textContent = 'Patta!'
    title.setAttribute('style', 'color: purple')
    disabilitaTutto()
}

const disabilitaTutto = () => {
    buttons.forEach((btn) => {
        btn.disabled = true;
    })
    document.querySelector('button[value="0"]').removeAttribute('style')
}