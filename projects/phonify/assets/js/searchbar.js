const removeContent = () => {
    input_search = document.querySelector('.search-box > input[type=text]')
    if(input_search.value === ' ') {
        input_search.value = '';
    } else {
        input_search.value = ' ';
    }
}
