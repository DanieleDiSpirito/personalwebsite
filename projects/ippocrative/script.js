document.addEventListener('DOMContentLoaded', () => { // everything is loaded
    var after, before;
    inputList = document.querySelectorAll('input[type=text][maxlength="1"]');
    
    inputList[0].focus();
    
    inputList.forEach((element, index, array) => {

        element.addEventListener('keydown', () => {
            before = element.value;
        });

        element.addEventListener('keyup',   (event) => {
            after = element.value;
            if(event.keyCode === 37) {
                elementAux = element;
                while(!(elementAux.previousElementSibling.tagName == 'INPUT' && elementAux.previousElementSibling.type == 'text')) {
                    elementAux = elementAux.previouslementSibling;
                }
                elementAux.previousElementSibling.focus();
                return;
            }
            if(event.keyCode === 39) {
                elementAux = element;
                while(!(elementAux.nextElementSibling.tagName == 'INPUT' && elementAux.nextElementSibling.type == 'text')) {
                    elementAux = elementAux.nextElementSibling;
                }
                elementAux.nextElementSibling.focus();
                return;
            }
            if(event.keyCode === 8) {
                try {
                    elementAux = element;
                    while(!(elementAux.previousElementSibling.tagName == 'INPUT' && elementAux.previousElementSibling.type == 'text')) {
                        elementAux = elementAux.previousElementSibling;
                    }
                    elementAux.previousElementSibling.focus();
                    return;
                } catch(TypeError) {
                    return;
                }
            }
            if(after.length == 1 && before.length == 0) {
                try {
                    elementAux = element;
                    while(!(elementAux.nextElementSibling.tagName == 'INPUT' && elementAux.nextElementSibling.type == 'text')) {
                        elementAux = elementAux.nextElementSibling;
                    }
                    elementAux.nextElementSibling.focus();
                    return;
                } catch(TypeError) {
                    return;
                }
            }
            if(before.length === 1 && 
                ((event.keyCode >= 65 && event.keyCode <= 90) || (event.keyCode >= 97 && event.keyCode <= 122) || (event.keyCode >= 48 && event.keyCode <= 57))
                ) {
                try {
                    elementAux = element;
                    while(!(elementAux.nextElementSibling.tagName == 'INPUT' && elementAux.nextElementSibling.type == 'text')) {
                        elementAux = elementAux.nextElementSibling;
                    }
                    elementAux.nextElementSibling.value = String.fromCharCode(event.keyCode);
                    elementAux.nextElementSibling.focus();
                    return;
                } catch(TypeError) {
                    return;
                }
            }
        });
    });
});