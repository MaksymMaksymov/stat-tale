var width = 512;
var counter = 0;
var spriteBuilding = new Image();
spriteBuilding.src = "img/buildings.png";
spriteBuilding.addEventListener('load', incrementCounter, false);
var spriteSpecialization = new Image();
spriteSpecialization.src = "img/specialization.png";
spriteSpecialization.addEventListener('load', incrementCounter, false);
var spriteWalls = new Image();
spriteWalls.src = "img/walls.png";
spriteWalls.addEventListener('load', incrementCounter, false);

function incrementCounter() {
    counter++;
    if (counter == 3) {
        for (let dxRace = 0; dxRace < 5; dxRace++) {
            for (let dyTrade = 0; dyTrade < 4; dyTrade++) {
                for (let dxSize = 0; dxSize < 5; dxSize++) {
                    for (let dySpec = 0; dySpec < 9; dySpec++) {
                        drawPlace(dxRace, dyTrade, dySpec, dxSize);
                    }
                    drawPlace(dxRace, dyTrade, -1, dxSize);
                }
            }
        }
    }
}

function drawPlace(dxRace, dyTrade, dySpec, dxSize) {
    let canvasElement = document.createElement("canvas");
    canvasElement.width = width;
    canvasElement.height = width;
    var ctx = canvasElement.getContext('2d');
    ctx.drawImage(spriteBuilding, dxRace * width, dyTrade * width, width, width, 0, 0, width, width);
    if (dySpec != -1) {
        ctx.drawImage(spriteSpecialization, dxRace * width, dySpec * width, width, width, 0, 0, width, width);
    }
    ctx.drawImage(spriteWalls, dxSize * width, 0, width, width, 0, 0, width, width);
    document.querySelector(".container").appendChild(canvasElement);
}