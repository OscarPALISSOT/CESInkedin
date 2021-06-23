//map

 
let $map = document.querySelector('#map');

class LeafLetMap{

    constructor () {
        this.bounds = []
    }

    async load(element){
        return new Promise((resolve, reject)=>{
            this.map = L.map(element)
            L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
	            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(this.map)
            resolve()
        })
    }

    addMarker(lat, lon, text){
        let point = [lat, lon];
        this.bounds.push(point);
        L.popup({
            autoClose: false,
            closeOnEscapeKey: false,
            closeOnClick: false,
            closeButton: false,
            className: 'marker',
        })
            .setLatLng(point)
            .setContent(text)
            .openOn(this.map);
    }

    center () {
        this.map.fitBounds(this.bounds);
    }
}

const initMap = async function () {
    let map = new LeafLetMap()
    await map.load($map);
    Array.from(document.querySelectorAll('.js-marker')).forEach((item) => {
        map.addMarker(item.dataset.lat, item.dataset.lon, '<p>' + item.dataset.text + '</p>')
    })
    map.center();
}

if ($map !== null){
    initMap();
}