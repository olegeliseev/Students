const headersList = [...document.querySelectorAll(".column-header")];

headersList.forEach(function(currentElement) {
    currentElement.addEventListener('click', function() {
        clearHeaderArrows(currentElement);
        setHeaderArrow(currentElement);
    });
});

function clearHeaderArrows(current) {
    headersList.forEach(function(e) {
        if(e == current) {
            return;
        }
        e.lastChild.textContent = '';
    })
}

function setHeaderArrow(current) {
    if(current.lastChild.textContent == '') {
        current.lastChild.textContent = '▼';
    } else if(current.lastChild.textContent == '▼') {
        current.lastChild.textContent = '▲';
    } else {
        current.lastChild.textContent = '';
    }
}