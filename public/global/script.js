
// GLOBAL FUNCTION

function saveData(data, route) {
    $.post(route, data, function(response) {
        // console.log(response);
    });
    
    return data;
}