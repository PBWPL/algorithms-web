// Load File
function loadFile(e) {
    let file = e.target.files[0];
    if (!file) {
        return;
    }
    let reader = new FileReader();
    reader.onload = function (e) {
        let contents = e.target.result;
        processData(contents);
    };
    reader.readAsText(file);
}

// Save file
function saveFile() {
    let matrix = '', value = '';
    let filename = $("#filename").val();
    let n = $("#n").val() + "\n";
    for (let i = 0; i < n; i++) {
        for (let j = 0; j < n; j++) {
            let cell = $("#i" + i + "j" + j).val();
            value += cell + " ";
        }
        matrix += value + "\n";
        value = '';
    }

    matrix = matrix.substr(0, matrix.length - 2);

    let blob = new Blob([n + matrix], {type: "text/plain;charset=utf-8"});
    let reader = new FileReader();
    reader.onload = () => {
        console.log(reader.result);
    };
    reader.readAsText(blob);
    saveAs(blob, filename + ".txt");
}

// Save file copy
function saveFile1() {
    let matrix = '', value = '';
    let filename = $("#filename").val();
    let n = $("#n").val() + "\n";
    for (let i = 0; i < n; i++) {
        for (let j = 0; j < n; j++) {
            let cell = $("#i" + i + "j" + j).val();
            value += cell + " ";
        }
        matrix += value + "\n";
        value = '';
    }

    matrix = matrix.substr(0, matrix.length - 2);

    let blob = new Blob([n, matrix], {type: "text/plain;charset=utf-8"});
    let reader = new FileReader();
    reader.onload = () => {
        console.log(reader.result);
    };
    reader.readAsText(blob);
    saveAs(blob, filename + ".txt");
}

// Clear table
function clearTable() {
    $(".numbers").val('');
    console.log("Table is empty!");
}
