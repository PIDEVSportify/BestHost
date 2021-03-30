let $map =document.querySelector('#map')

    class LeafletMap{

    constructor() {
    this.map = null
    this.bounds=[]
}

    async load(element){
    return new Promise((resolve, reject) => {
    this.map = L.map(element)
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1Ijoic3BlYWt5OTgiLCJhIjoiY2ttbnA3NWtiMGpyZTJ1cGUzdG56bjN4cSJ9.SntRibYIa_8VaZnyFAZ-jA'
}).addTo(this.map);
    resolve()
})
}
    addmarker(lat,lng,parent){
    let point= [lat, lng]
    this.bounds.push(point)
    L.popup({
    autoClose:false,
    closeOnEscapeKey: false,
    closeOnClick: false,
    closeButton: false,
    className : 'marker',
    maxWidth: 480
})
    .setLatLng(point).setContent(parent).openOn(this.map)

}
    center(){
    this.map.fitBounds(this.bounds)
}
}

    const initMap = async function () {
    let map = new LeafletMap()
    await map.load($map)
    Array.from(document.querySelectorAll('.js-marker')).forEach((item) => {
    map.addmarker(item.dataset.lat,item.dataset.lng,item.dataset.parent)
})
    map.center()
}
    if($map !== null){
    initMap()
}