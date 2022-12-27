/* al posto dell'if uso
    condizione &&
        codice che verrà eseguito se la condizione è vera
*/

nMossa = 0
mosse = [] // tutte le mosse dell'utente
buttons = document.querySelectorAll('button[value="1"]') // tutte le caselle
buttons.forEach((button) => {
    button.addEventListener('click', () => {
        if(button.value == 1) { // nè O nè 1
            changeValue(button, 'X')
            nMossa++
            mosse.push([
                parseInt(button.name / 3), // n. riga
                button.name % 3 // n. colonna
            ])
            CPU() // turno della CPU
        }
    })
})

const CPU = () => {
    switch(nMossa) {
        case 1:
            primaMossa()
            break /* 
            PRIMA MOSSA (case 1):

                - se la X è al centro, la O andrà in alto a sinistra
                ...       O..
                .X.  -->  .X.
                ...       ...

                - se la X è nei vertici, la O andrà al centro
                X..       X..
                ...  -->  .O.
                ...       ...

                - se la X è negli spigoli:
                1)  .X.       OX.
                    ...  -->  ...
                    ...       ...
                2)  ...       O..
                    X..  -->  X..
                    ...       ...
                3)  ...       ..O
                    ..X  -->  ..X
                    ...       ...
                4)  ...       .O.
                    ...  -->  ...
                    .X.       .X.               
            */
        
        case 5:
            patta()
            break
        default: /*
            - celle vuote: 1, celle con X: 3, celle con O: 5 (1, 3, 5 possono essere sostituiti con qualsiasi valore purchè coprimi tra loro)
            - CALCOLO IL PRODOTTO PER OGNI DIAGONARE, RIGA E COLONNA
                casi:    
                1. se il prodotto è 25 (5*5*1) allora basterà mettere la O dove c'è l'1 e la CPU vince
                2. se il prodotto è 9 (3*3*1) allora bisognerà inserire la O dove c'è l'1 per impedire la vittoria dell'utente
                3. se il prodotto è 3*3*5 o 3*5*5 non si può far nulla, la riga/colonna/diagonale è già piena
                4. se il prodotto è 1*1*1, 1*1*3, 1*1*5 o 1*3*5 ... caso particolare (riga 109) */
            
            fine = false;
            // 25, 9
            [5 * 5 * 1, 3 * 3 * 1].forEach((numero) => {
                // colonne
                for(i = 0; i < 3 && !fine; i++) {
                    if(prodotto(buttons, 0 + i, 3 + i, 6 + i) == numero) {
                        fine = true;
                        [0 + i, 3 + i, 6 + i].forEach((posizione) => {
                            buttons[posizione].value == 1 &&
                                changeValue(buttons[posizione], 'O', numero)
                        })
                    }
                }
                // righe
                for(i = 0; i < 3 && !fine; i++) {
                    if(prodotto(buttons, 0 + i*3, 1 + i*3, 2 + i*3) == numero) {
                        fine = true;
                        [0 + i*3, 1 + i*3, 2 + i*3].forEach((posizione) => {
                            buttons[posizione].value == 1 &&
                                changeValue(buttons[posizione], 'O', numero)
                        })
                    }
                }
                // controllo sulla diagonale principale
                if(prodotto(buttons, 0, 4, 8) == numero && !fine) {
                    fine = true;
                    [0, 4, 8].forEach((posizione) => {
                        buttons[posizione].value == 1 &&
                        changeValue(buttons[posizione], 'O', numero)
                    })
                }
                // controllo sulla diagonale secondaria
                if(prodotto(buttons, 2, 4, 6) == numero && !fine) {
                    fine = true;
                    [2, 4, 6].forEach((posizione) => {
                        buttons[posizione].value == 1 &&
                            changeValue(buttons[posizione], 'O', numero)
                    })
                }
            })

            // caso 4
            if(!fine) {
                if(buttons[4].value == 3) {
                    posizione = mosse[nMossa-1][0]*3 + 3-mosse[nMossa-1][1] - 1
                    if(buttons[posizione].value == 1) {
                        changeValue(buttons[posizione], 'O')
                        fine = true
                    }
                } else {
                    for(let i of [4, 1, 3, 5, 7]) {
                        if(buttons[i].value == 1) {
                            changeValue(buttons[i], 'O')
                            fine = true
                            break
                        }
                    }
                }

                if(!fine) {
                    for(i = 0; i < 9; i++) {
                        if(buttons[i].value == 1) {
                            changeValue(buttons[i], 'O')
                            break
                        }
                    }
                }
            }
    }
}

dict = {'': 1, 'X': 3, 'O': 5}
const changeValue = (element, value, number = 0) => {
    element.textContent = value
    element.value = dict[value]
    if(number === 25) {
        vittoriaCPU()
    }
}

const primaMossa = () => {
    // X al centro
    if(areEquals(mosse[nMossa-1], [1,1])) {
        changeValue(buttons[0], 'O')
        return
    }
    // X nei vertici
    if(mosse[nMossa - 1][0] != 1 && mosse[nMossa - 1][1] != 1) {
        changeValue(buttons[4], 'O')
        return
    }
    // X negli spigoli
    if(areEquals(mosse[nMossa-1], [0,1])) {
        changeValue(buttons[0], 'O')
        return
    }
    if(areEquals(mosse[nMossa-1], [1,0])) {
        changeValue(buttons[0], 'O')
        return
    }
    if(areEquals(mosse[nMossa-1], [1,2])) {
        changeValue(buttons[2], 'O')
        return
    }
    if(areEquals(mosse[nMossa-1], [2,1])) {
        changeValue(buttons[1], 'O')
        return
    }
}

const prodotto = (listaElementi, ...posizioni) => {
    res = 1 // risultato
    posizioni.forEach((posizione) => {
        res *= listaElementi[posizione].value
    })
    return res
}

const areEquals = (arr1, arr2) => {
    if (arr1.length !== arr2.length) return false;
    for (var i = 0, len = arr1.length; i < len; i++) {
        if (arr1[i] !== arr2[i]){
            return false;
        }
    }
    return true; 
}

const vittoriaCPU = () => {
    title = document.querySelector('div#title')
    title.textContent = 'Hai perso!'
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