class MapDrawer {
    constructor() {
        this._data = document.getElementById('map-data');
        this.setMode(this._data.getAttribute("data-mode"));
        this._canvas = document.getElementById("tale-map");
        this._sprite = new Image();
        this._sprite.src = "../views/maphex/sprites/" + this._data.getAttribute("data-sprite") + ".png";
        this._sprite.addEventListener('load', this.prepareAllData.bind(this));
        this._tileSize = 64;
        this._widhtDetailed = this._tileSize-16;
        this._heightDetailed = this._tileSize-8;
    }

    setMode(mode) {
        let fileRef = document.createElement('script');
        fileRef.setAttribute("type", "text/javascript");
        fileRef.setAttribute("src", '../views/maphex/dictionary/' + mode + '.js');
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
        this._canvas.width = this._widhtDetailed * data.width;
        this._canvas.height = this._heightDetailed * data.height + this._heightDetailed/2;
        this._canvas.setAttribute("style", "width:" + this._widhtDetailed * data.width + "px; height: " + (this._heightDetailed * data.height + this._heightDetailed/2) + "px;");
        this.drawMapByTemplateData(data);
    }

    drawMapByTemplateData(data) {
        const ctx = this._canvas.getContext('2d');

        for (let h = 0; h < data.height; h++) {
            for (let w = 0; w < data.width; w++) {
                if (typeof data.draw_info !== 'undefined') {
                    let cellSprites = data.draw_info[h][w];
                    cellSprites.forEach(sprite => {
                        if (sprite[0] >= 64 && sprite[0] <= 69) {
                            switch(sprite[0]) { // 1 up, 0 down
                                case 69:
                                    if (sprite[1] === 0 || sprite[1] === 180) {
                                        sprite[0] = (w%2 === 0 && sprite[1] === 0 || w%2 === 1 && sprite[1] === 180) ? 94 : 95;
                                    }
                                    break;
                                case 65:
                                    if (sprite[1] === 90 || sprite[1] === 270) {
                                        sprite[0] = (w%2 === 0 && sprite[1] === 90 || w%2 === 1 && sprite[1] === 270) ? 97 : 98;
                                    } else 
                                        if (w%2 === 0 && sprite[1] === 0 || w%2 === 1 && sprite[1] === 180)
                                            sprite[0] = 96;
                                    break;
                                case 67:
                                    if (w%2 === 0 && sprite[1] === 0 || w%2 === 1 && sprite[1] === 180)
                                        sprite[0] = 99;
                                    break;
                                case 64:
                                    if (w%2 === 0)
                                        sprite[1] = 180;
                                    break;
                                case 68:
                                    if (sprite[1] === 90 || sprite[1] === 270) {
                                        sprite[0] = (w%2 === 0 && sprite[1] === 90 || w%2 === 1 && sprite[1] === 270) ? 101 : 102;
                                    } else 
                                        if (w%2 === 0 && sprite[1] === 0 || w%2 === 1 && sprite[1] === 180)
                                            sprite[0] = 100;
                                    break;
                            }
                        }
                        ctx.drawImage(this.getSprite(sprite), w * this._widhtDetailed, (w%2 === 0) ? h * this._heightDetailed : h * this._heightDetailed + this._heightDetailed/2, this._tileSize, this._tileSize);
                    });
                } else {
                    alert("Undefined API!");
                    return;
                }
            }
        }

        /*draw data*/
        ctx.font = "14px monospace";
        ctx.textAlign = "center";
        for (let id in data.places) {
            let place = data.places[id];
            let textX = place.pos.x * this._widhtDetailed + this._tileSize/2;
            let textY = (place.pos.x%2 === 0) ? (place.pos.y + 1.45) * this._heightDetailed : (place.pos.y + 1.45) * this._heightDetailed + this._heightDetailed/2;
            let textName = place.name;
            let te = ctx.measureText(textName);
            ctx.fillStyle = 'rgba(0, 0, 0, 0.8)';
            ctx.fillRect(textX - te.width / 2 - 5, textY - 15, te.width + 10, 20);
            ctx.fillStyle = '#dddddd';
            ctx.fillText(textName, textX, textY);
        }
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