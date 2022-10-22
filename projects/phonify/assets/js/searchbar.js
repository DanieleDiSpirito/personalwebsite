const removeContent = () => {
    input_search = document.querySelector('.search-box > input[type=text]')
    if(input_search.value === ' ') {
        input_search.value = ''
    } else {
        input_search.value = ' '
    }
    search()
}

const search = () => {
    str_to_search = document.querySelector('.search-box > input[type=text]').value.trim().toLowerCase()
    for(let i = 0; i < document.querySelectorAll('.colonna .nomeprodotto').length; i++) {
        if(document.querySelectorAll('.colonna .nomeprodotto')[i].text.toLowerCase().includes(str_to_search)) {
            document.querySelectorAll('.colonna')[i].classList.remove('hidden')
        } else {
            document.querySelectorAll('.colonna')[i].classList.add('hidden')
        }
    }
}