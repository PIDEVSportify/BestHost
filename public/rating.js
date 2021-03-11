function rate5(index){
    document.getElementById("5".concat(index)).style.color = "orange";
    document.getElementById("4".concat(index)).style.color = "orange";
    document.getElementById("3".concat(index)).style.color = "orange";
    document.getElementById("2".concat(index)).style.color = "orange";
    document.getElementById("1".concat(index)).style.color = "orange";
}

function rate4(index){
    document.getElementById("5".concat(index)).style.color = "#9e9e9e";
    document.getElementById("4".concat(index)).style.color = "orange";
    document.getElementById("3".concat(index)).style.color = "orange";
    document.getElementById("2".concat(index)).style.color = "orange";
    document.getElementById("1".concat(index)).style.color = "orange";
}

function rate3(index){
    document.getElementById("5".concat(index)).style.color = "#9e9e9e";
    document.getElementById("4".concat(index)).style.color = "#9e9e9e";
    document.getElementById("3".concat(index)).style.color = "orange";
    document.getElementById("2".concat(index)).style.color = "orange";
    document.getElementById("1".concat(index)).style.color = "orange";
}

function rate2(index){
    document.getElementById("5".concat(index)).style.color = "#9e9e9e";
    document.getElementById("4".concat(index)).style.color = "#9e9e9e";
    document.getElementById("3".concat(index)).style.color = "#9e9e9e";
    document.getElementById("2".concat(index)).style.color = "orange";
    document.getElementById("1".concat(index)).style.color = "orange";
}

function rate1(index){
    document.getElementById("5".concat(index)).style.color = "#9e9e9e";
    document.getElementById("4".concat(index)).style.color = "#9e9e9e";
    document.getElementById("3".concat(index)).style.color = "#9e9e9e";
    document.getElementById("2".concat(index)).style.color = "#9e9e9e";
    document.getElementById("1".concat(index)).style.color = "orange";
}

function clean(index){
    document.getElementById("form".concat(index)).reset();
    document.getElementById("5".concat(index)).style.color = "#9e9e9e";
    document.getElementById("4".concat(index)).style.color = "#9e9e9e";
    document.getElementById("3".concat(index)).style.color = "#9e9e9e";
    document.getElementById("2".concat(index)).style.color = "#9e9e9e";
    document.getElementById("1".concat(index)).style.color = "#9e9e9e";
}

function average(aver,index){
    var note=Math.round(aver);

    if(note===1) {
        document.getElementById("55".concat(index)).style.color = "#9e9e9e";
        document.getElementById("44".concat(index)).style.color = "#9e9e9e";
        document.getElementById("33".concat(index)).style.color = "#9e9e9e";
        document.getElementById("22".concat(index)).style.color = "#9e9e9e";
        document.getElementById("11".concat(index)).style.color = "orange";
    }
    if(note===2){
        document.getElementById("55".concat(index)).style.color = "#9e9e9e";
        document.getElementById("44".concat(index)).style.color = "#9e9e9e";
        document.getElementById("33".concat(index)).style.color = "#9e9e9e";
        document.getElementById("22".concat(index)).style.color = "orange";
        document.getElementById("11".concat(index)).style.color = "orange";
    }
    if(note===3){
        document.getElementById("55".concat(index)).style.color = "#9e9e9e";
        document.getElementById("44".concat(index)).style.color = "#9e9e9e";
        document.getElementById("33".concat(index)).style.color = "orange";
        document.getElementById("22".concat(index)).style.color = "orange";
        document.getElementById("11".concat(index)).style.color = "orange";
    }
    if(note===4){
        document.getElementById("55".concat(index)).style.color = "#9e9e9e";
        document.getElementById("44".concat(index)).style.color = "orange";
        document.getElementById("33".concat(index)).style.color = "orange";
        document.getElementById("22".concat(index)).style.color = "orange";
        document.getElementById("11".concat(index)).style.color = "orange";
    }
    if(note===5){
        document.getElementById("55".concat(index)).style.color = "orange";
        document.getElementById("44".concat(index)).style.color = "orange";
        document.getElementById("33".concat(index)).style.color = "orange";
        document.getElementById("22".concat(index)).style.color = "orange";
        document.getElementById("11".concat(index)).style.color = "orange";
    }

}
window.onload = average;