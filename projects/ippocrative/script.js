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
                array[index-1].focus();
                return;
            }
            if(event.keyCode === 39) {
                array[index+1].focus();
                return;
            }
            if(after.length == 1 && before.length == 0) {
                try {
                    array[index+1].focus();
                    return;
                } catch(TypeError) {
                    return;
                }
            }
            if(event.keyCode === 8) {
                try {
                    array[index-1].focus();
                    return;
                } catch(TypeError) {
                    return;
                }
            }
            if(before.length === 1 && 
                ((event.keyCode >= 65 && event.keyCode <= 90) || (event.keyCode >= 97 && event.keyCode <= 122) || (event.keyCode >= 48 && event.keyCode <= 57))
                ) {
                try {
                    array[index+1].value =  String.fromCharCode(event.keyCode);
                    array[index+1].focus();
                    return;
                } catch(TypeError) {
                    return;
                }
            }
        });
    });
});