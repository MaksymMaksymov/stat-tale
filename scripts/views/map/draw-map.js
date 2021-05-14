class MapDrawer {
    constructor() {
        this._data = document.getElementById('map-data');
        this.setMode(this._data.getAttribute("data-mode"));
        this._canvas = document.getElementById("tale-map");
        this._sprite = new Image();
        this._sprite.src = "../views/map/sprites/" + this._data.getAttribute("data-sprite") + ".png";
        this._sprite.addEventListener('load', this.prepareAllData.bind(this));
        this._tileSize = 32;
    }

    setMode(mode) {
        let fileRef = document.createElement('script');
        fileRef.setAttribute("type", "text/javascript");
        fileRef.setAttribute("src", '../views/map/dictionary/' + mode + '.js');
        document.getElementsByTagName("head")[0].appendChild(fileRef);
    }

    prepareAllData() {
        if (typeof spritePos != "undefined") {
            this.drawMap();
        } else {
            setTimeout(this.prepareAllData.bind(this), 50);
        }
    }

    drawMap() {
        let data = JSON.parse(document.getElementById('map-data').innerHTML);
        if (data.format_version != "0.1") {
            alert("Unknown version map \"" + data.format_version + "\"");
            return;
        }
        this._canvas.width = this._tileSize * data.width;
        this._canvas.height = this._tileSize * data.height;
        this._canvas.setAttribute("style", "width:" + this._tileSize * data.width + "px; height: " + this._tileSize * data.height + "px;");
        this.drawMapByTemplateData(data);
    }

    drawMapByTemplateData(data) {
        const ctx = this._canvas.getContext('2d');

        for (let h = 0; h < data.height; h++) {
            for (let w = 0; w < data.width; w++) {
                if (typeof data.draw_info !== 'undefined') {
                    let cellSprites = data.draw_info[h][w];
                    for (let s = 0; s < cellSprites.length; s++) {
                        ctx.drawImage(this.getSprite(cellSprites[s]), w * this._tileSize, h * this._tileSize, this._tileSize, this._tileSize);
                    }
                } else {
                    alert("Undefined API!");
                    return;
                }
            }
        }

        /*draw data*/
        /*ctx.font = "14px monospace";
        ctx.textAlign = "center";
        for (let id in data.places) {
            let place = data.places[id];
            let trade_economy = data.trade_economy[id][0]; // + "/" + data.trade_economy[id][1];
            let textX = (place.pos.x + 0.5) * tileSize * 3;
            let textY = (place.pos.y + 1.45) * tileSize * 3;
            let textName = trade_economy; // + " " + place.name;
            let te = ctx.measureText(textName);
            ctx.fillStyle = 'rgba(0, 0, 0, 0.8)';
            ctx.fillRect(textX - te.width / 2 - 5, textY - 15, te.width + 10, 20);
            ctx.fillStyle = '#dddddd';
            ctx.fillText(textName, textX, textY);
        }*/
    }

    getSprite(cellSprite) {
        let canvas = document.createElement("canvas");
        canvas.width = this._tileSize;
        canvas.height = this._tileSize;
        let ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.translate(this._tileSize / 2, this._tileSize / 2);
        ctx.rotate(0.0174533 * cellSprite[1]);
        let sx = spritePos[cellSprite[0]].x * this._tileSize;
        let sy = spritePos[cellSprite[0]].y * this._tileSize;
        ctx.drawImage(this._sprite, sx, sy, this._tileSize, this._tileSize, -this._tileSize / 2, -this._tileSize / 2, this._tileSize, this._tileSize);
        return canvas;
    }
}

new MapDrawer();