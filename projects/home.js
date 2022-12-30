const changePage = (pageName, method = 'self') => {
    window.open(`${pageName}`, `_${method}`);
}