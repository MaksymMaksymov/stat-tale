var canvas = null;
var sprites = null;
var tmpCanvas = document.createElement("canvas");

function drawMap0_1(data, map) {
    const tileSize = 32;
    const spritePerLine = 12;
    canvas.width = tileSize * data.width;
    canvas.height = tileSize * data.height;
    const ctx = canvas.getContext('2d');
    const spriteImage = sprites.reduce(function(previousValue, currentValue, idx, arr) {
        if (currentValue.mapType == map) {
            return currentValue;
        }
        return previousValue;
    }, sprites[0]);
    tmpCanvas.width = tileSize;
    tmpCanvas.height = tileSize;
    for (let h = 0; h < data.height; h++) {
        for (let w = 0; w < data.width; w++) {
            if (typeof data.draw_info !== 'undefined') {
                let cellSprites = data.draw_info[h][w];
                let dx = w * tileSize;
                let dy = h * tileSize;
                for (let s = 0; s < cellSprites.length; s++) {
                    let tmpCtx = tmpCanvas.getContext('2d');
                    tmpCtx.clearRect(0, 0, tmpCanvas.width, tmpCanvas.height);
                    tmpCtx.save();
                    tmpCtx.translate(tileSize / 2, tileSize / 2);
                    tmpCtx.rotate(0.0174533 * cellSprites[s][1]);
                    let sx = spritePos[cellSprites[s][0]].x;
                    let sy = spritePos[cellSprites[s][0]].y;
                    tmpCtx.drawImage(spriteImage, sx, sy, tileSize, tileSize, -tileSize / 2, -tileSize / 2, tileSize, tileSize);
                    tmpCtx.restore();
                    let img = new Image;
                    ctx.drawImage(tmpCanvas, dx, dy, tileSize, tileSize);
                }
            } else {
                let cellSprite = data.terrain[h][w];
                let dx = w * tileSize;
                let dy = h * tileSize;
                let sx = cellSprite % 12 * tileSize;
                let sy = Math.floor(cellSprite / 12) * tileSize;
                ctx.drawImage(spriteImage, sx, sy, tileSize, tileSize, dx, dy, tileSize, tileSize);
            }
        }
    }
	
    /*отрисовка названия городов + данные*/
    ctx.font = "14px monospace";
    ctx.textAlign = "center";
    for (let id in data.places) {
        let place = data.places[id];
        let trade_economy = data.trade_economy[id][0] + "/" + data.trade_economy[id][1];
        let textX = (place.pos.x + 0.5) * tileSize;
        let textY = (place.pos.y + 1.45) * tileSize;
        let textName = trade_economy + " " + place.name;
        let te = ctx.measureText(textName);
        ctx.fillStyle = 'rgba(0, 0, 0, 0.8)';
        ctx.fillRect(textX - te.width / 2 - 5, textY - 15, te.width + 10, 20);
        ctx.fillStyle = '#dddddd';
        ctx.fillText(textName, textX, textY);
    }
    /*конец отрисовки*/
	
}

function processRegionResponse(responseData, map) {
    if (responseData.format_version != "0.1") {
        alert("Неизвестная версия карты \"" + responseData.format_version + "\"");
        return;
    }
    drawMap0_1(responseData, map);
}

function drawMap() {
    var node = document.getElementById('map-data'),
    textContent = node.innerHTML;
    var response = JSON.parse(textContent);
    processRegionResponse(response, "map");
/*
    var xhr = new XMLHttpRequest();
    xhr.open("get", "https://the-tale.org/game/map/api/region?api_client=map-v0.4&api_version=0.1", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                } catch (e) {
                    alert("Неожиданный ответ");
                }
                if (response.status == "ok") {
                    processRegionResponse(response.data.region, "map");
                } else {
                    console.log(response);
                    alert("Ошибка: " + response.error);
                }
            } else {
            	alert(`Ошибка ${xhr.status} на стороне сервера Сказки: ${xhr.statusText}`);
                // For future - it is cross domain CORS problem, u'll get 0 status
            }
        }
    };
    xhr.send();*/
}
(function() {
    canvas = document.getElementById("map");
    sprites = [];
    var loadSprites = 0;
    var sprite = new Image();
    sprite.src = "./images/map_ressurect_anim.png";
    sprite.mapType = map;
    sprite.addEventListener('load', function() {
        loadSprites++;
        if (loadSprites == sprites.length) {
            drawMap();
        }
    });
    sprites.push(sprite);
})();
