window.addEventListener('load', () => {
    if(window.innerWidth < 768) {
        for(let doc of document.getElementsByClassName('col-6')) {
            doc.classList.add('col-12')
        }
        for(let doc of document.getElementsByClassName('col-12')) {
            doc.classList.remove('col-6')
        }
    } else {
        for(let doc of document.getElementsByClassName('col-12')) {
            doc.classList.add('col-6')
        }
        for(let doc of document.getElementsByClassName('col-6')) {
            doc.classList.remove('col-12')
        }
    }
});

window.addEventListener('resize', () => {
    if(window.innerWidth < 768) {
        for(let doc of document.getElementsByClassName('col-6')) {
            doc.classList.add('col-12')
        }
        for(let doc of document.getElementsByClassName('col-12')) {
            doc.classList.remove('col-6')
        }
    } else {
        for(let doc of document.getElementsByClassName('col-12')) {
            doc.classList.add('col-6')
        }
        for(let doc of document.getElementsByClassName('col-6')) {
            doc.classList.remove('col-12')
        }
    }
});